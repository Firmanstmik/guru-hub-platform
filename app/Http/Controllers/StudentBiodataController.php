<?php

namespace App\Http\Controllers;

use App\Models\StudentBiodata;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentBiodataController extends Controller
{

    // FUNGSI UNTUK ROLE ADMIN
    public function index()
    {
        // Menggunakan eager loading (with) agar tidak terkena masalah N+1 Query pada relasi user
        $students = StudentBiodata::with('user')->latest()->get();

        return view('admin.student-biodata', compact('students'));
    }

    public function destroy($id)
    {
        try {
            $biodata = StudentBiodata::with('user')->findOrFail($id);
            $user = $biodata->user;

            // Jika user memiliki avatar, hapus file gambarnya dari storage lokal
            if ($user && $user->avatar) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            // Hapus data biodata siswa
            $biodata->delete();

            return redirect()->back()->with('success', 'Data biodata siswa berhasil dihapus dari sistem.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus data siswa ID ' . $id . ': ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data. Terjadi kesalahan pada sistem.');
        }
    }


    // FUNGSI UNTUK ROLE SISWA
    public function siswaForm()
    {
        $user = Auth::user();
        $biodata = $user->studentBiodata;
        return view('student.biodata-form', compact('user', 'biodata'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi NISN unik harus dikecualikan untuk ID biodata milik user ini sendiri saat update
        // Menggunakan nama relasi asli Anda: studentBiodata
        $biodataId = $user->studentBiodata->id ?? 'NULL';

        $request->validate([
            // Validasi untuk tabel Users
            'phone_number' => ['required', 'string', 'max:15'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            // Validasi untuk tabel Student Biodatas
            'nisn' => ['required', 'string', 'max:20', 'unique:student_biodatas,nisn,' . $biodataId],
            'institution_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'address' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // 1. Update data pada tabel Users (phone_number dan avatar)
            $user->phone_number = $request->phone_number;

            if ($request->hasFile('avatar')) {
                // Hapus avatar lama di storage jika user mengunggah berkas baru
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                // Simpan gambar baru ke folder storage/app/public/avatars
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }
            $user->save();

            // 2. Simpan atau Update pada tabel Student Biodatas menggunakan relasi studentBiodata()
            $user->studentBiodata()->updateOrCreate(
                ['user_id' => $user->id], // Kunci pencarian data
                [
                    'nisn' => $request->nisn,
                    'institution_name' => $request->institution_name,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]
            );

            DB::commit();

            return redirect('/biodata')->with('success', 'Biodata dan informasi akun berhasil diperbarui!');
        } catch (Exception $e) {
            DB::rollBack();

            // Menghapus file avatar baru yang telanjur terupload apabila transaksi DB gagal di tengah jalan
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Gagal menyimpan/update biodata serta akun siswa ID ' . $user->id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat memperbarui profil Anda.');
        }
    }
}
