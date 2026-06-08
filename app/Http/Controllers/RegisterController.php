<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function siswaRegister()
    {
        return view('auth.register-student');
    }

    public function storeSiswaRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'], // Tambahan Validasi HP
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, // Tambahan Simpan DB
                'password' => Hash::make($request->password),
            ]);

            // Assign role Spatie
            $user->assignRole('siswa');

            // Jika semua aman, komit transaksi
            DB::commit();
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
        } catch (Exception $e) {
            // Batalkan semua perubahan jika ada kegagalan query
            DB::rollBack();

            // Catat error ke file log untuk debugging admin
            Log::error('Gagal registrasi siswa: ' . $e->getMessage());

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Gagal melakukan registrasi karena kendala internal sistem.');
        }
    }

    public function guruRegister()
    {
        return view('auth.register-teacher');
    }

public function storeGuruRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'], // Diubah ke phone_number
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, // Diubah ke phone_number
                'password' => Hash::make($request->password),
            ]);

            // Assign role Spatie
            $user->assignRole('guru');

            // Jika semua aman, komit transaksi
            DB::commit();
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
            
        } catch (Exception $e) {
            // Batalkan semua perubahan jika ada kegagalan query
            DB::rollBack();

            // Catat error ke file log
            Log::error('Gagal registrasi guru: ' . $e->getMessage());

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Gagal melakukan registrasi karena kendala internal sistem.');
        }
    }
}
