<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    /**
     * TAMPILKAN HALAMAN LOGIN
     */
    public function viewLogin()
    {
        try {
            // Jika user sudah login, langsung alihkan ke dashboard masing-masing tanpa perlu melihat form login lagi
            if (Auth::check()) {
                return $this->redirectUserBasedOnRole();
            }

            return view('auth.login');
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman login: ' . $e->getMessage());
            return view('auth.login');
        }
    }

    /**
     * PROSES OTENTIKASI / LOGIN
     */
    public function login(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi (password) tidak boleh dikosongkan.',
        ]);

        try {
            // Lakukan percobaan login ke sistem
            if (Auth::attempt($credentials, $request->has('remember'))) {

                $user = Auth::user();

                // PROTEKSI TAMBAHAN: Cek jika akun dinonaktifkan oleh Admin
                if (isset($user->is_active) && !$user->is_active) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin sistem.');
                }

                // Regenerasi ID sesi untuk mencegah serangan Session Fixation
                $request->session()->regenerate();

                // Alihkan ke halaman dashboard yang sesuai dengan peran
                return $this->redirectUserBasedOnRole();
            }

            // Gagal otentikasi (Email atau password tidak cocok)
            return back()->withInput()->with('error', 'Alamat email atau kata sandi yang Anda masukkan salah.');
        } catch (Exception $e) {
            Log::error('Terjadi kesalahan fatal pada proses login: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memproses masuk sistem karena kendala internal server.');
        }
    }

    /**
     * PROSES KELUAR SISTEM / LOGOUT
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            // Hancurkan seluruh data sesi dan token lama demi keamanan
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
        } catch (Exception $e) {
            Log::error('Gagal memproses logout: ' . $e->getMessage());
            return redirect('/login');
        }
    }

    /**
     * METODE PEMBANTU: Pengalihan Rute Berdasarkan Peran (Spatie Permissions)
     */
    protected function redirectUserBasedOnRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect('/admin-dashboard');
        } elseif ($user->hasRole('guru')) {
            return redirect('/guru-dashboard');
        } elseif ($user->hasRole('siswa')) {
            return redirect('/siswa-dashboard');
        }

        // Fallback: Jika pengguna tidak memiliki peran apa pun, keluarkan secara aman
        Auth::logout();
        return redirect('/login')->with('error', 'Akun Anda tidak memiliki peran akses terdaftar di sistem.');
    }
}
