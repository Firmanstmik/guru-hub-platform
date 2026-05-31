<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherProfileController extends Controller
{
    public function index(Request $request)
    {
        // 1. Eager loading data user terkait untuk tabel
        $query = TeacherProfile::with('user');

        // Filter berdasarkan Status Verifikasi
        if ($request->has('status') && $request->status != '') {
            $query->where('verification_status', $request->status);
        }

        // Fitur Pencarian berdasarkan nama guru atau keahlian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('skills_tags', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $profiles = $query->latest()->paginate(10)->withQueryString();

        // 2. TAMBAHKAN KODE INI: Ambil user yang BELUM memiliki profil guru untuk isi Modal Create
        $users = User::whereDoesntHave('teacherProfile')->get();

        // 3. Masukkan variabel $users ke dalam compact()
        return view('admin.teacher', compact('profiles', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,id|unique:teacher_profiles,user_id',
            'title'               => 'required|string|max:255',
            'bio'                 => 'nullable|string',
            'skills_tags'         => 'required|string', // Contoh format input: "PHP, Laravel, Vue"
            'verification_status' => 'required|in:pending,approved,rejected',
            'cv_file'             => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Maksimal 2MB
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:255',
        ]);

        $data = $request->except('cv_file');
        $data['average_rating'] = 0; // Nilai awal rating untuk guru baru

        // Proses unggah file CV jika ada
        if ($request->hasFile('cv_file')) {
            $data['cv_file'] = $request->file('cv_file')->store('cv_teachers', 'public');
        }

        $profile = TeacherProfile::create($data);

        // Jika admin menyetel langsung ke 'approved', ubah role user ke 'teacher'
        if ($request->verification_status === 'approved') {
            $profile->user->update(['role' => 'teacher']);
        }

        return redirect('/teachers')->with('success', 'Profil pengajar baru berhasil ditambahkan.');
    }

    public function show(TeacherProfile $profile)
    {
        $profile->load('user');
        return view('admin.teacher-show', compact('profile'));
    }

    public function update(Request $request, TeacherProfile $profile)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'bio'                 => 'nullable|string',
            'skills_tags'         => 'required|string',
            'verification_status' => 'required|in:pending,approved,rejected',
            'cv_file'             => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:255',
        ]);

        $data = $request->except('cv_file');

        // Penanganan penggantian berkas CV lama
        if ($request->hasFile('cv_file')) {
            // Hapus berkas CV yang lama jika ada di dalam storage berkas publik
            if ($profile->cv_file && Storage::disk('public')->exists($profile->cv_file)) {
                Storage::disk('public')->delete($profile->cv_file);
            }
            // Simpan berkas yang baru
            $data['cv_file'] = $request->file('cv_file')->store('cv_teachers', 'public');
        }

        $profile->update($data);

        // Sinkronisasi status role user berdasarkan pembaruan status verifikasi
        if ($request->verification_status === 'approved') {
            $profile->user->update(['role' => 'teacher']);
        } else {
            // Opsional: Jika status diubah kembali ke pending/rejected, kembalikan role ke 'user' biasa
            $profile->user->update(['role' => 'user']);
        }

        return redirect('/teachers')->with('success', "Profil pengajar {$profile->user->name} berhasil diperbarui.");
    }

    public function destroy(TeacherProfile $profile)
    {
        // Hapus file CV fisik terlebih dahulu dari folder storage
        if ($profile->cv_file && Storage::disk('public')->exists($profile->cv_file)) {
            Storage::disk('public')->delete($profile->cv_file);
        }

        // Kembalikan role user menjadi user biasa sebelum profil gurunya dihapus
        $profile->user->update(['role' => 'user']);

        $namaUser = $profile->user->name;
        $profile->delete();

        return redirect('/teachers')->with('success', "Profil pengajar dan berkas CV atas nama {$namaUser} telah berhasil dihapus sepenuhnya.");
    }

    public function verify(Request $request, TeacherProfile $profile)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
        ]);

        $profile->update([
            'verification_status' => $request->verification_status
        ]);

        if ($request->verification_status === 'approved') {
            $profile->user->update(['role' => 'teacher']);
        } else {
            $profile->user->update(['role' => 'user']);
        }

        return redirect()->back()->with('success', "Status akun pengajar {$profile->user->name} berhasil diperbarui menjadi " . strtoupper($request->verification_status));
    }
}
