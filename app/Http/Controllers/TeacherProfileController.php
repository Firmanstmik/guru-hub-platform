<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading data user terkait
        $query = TeacherProfile::with('user');

        // Filter berdasarkan Status Verifikasi (pending, approved, rejected)
        if ($request->has('status') && $request->status != '') {
            $query->where('verification_status', $request->status);
        }

        // Fitur Pencarian berdasarkan nama guru atau keahlian (skills_tags)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('skills_tags', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $profiles = $query->latest()->paginate(10)->withQueryString();

        return view('admin.teacher', compact('profiles'));
    }

    /**
     * Memperbarui status verifikasi akun mengajar guru
     */
    public function verify(Request $request, TeacherProfile $profile)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
        ]);

        $profile->update([
            'verification_status' => $request->verification_status
        ]);

        // Opsional: Jika disetujui, Anda bisa otomatis mengubah role user menjadi 'teacher' 
        // di tabel users jika diperlukan penyesuaian hak akses sistem.
        if ($request->verification_status === 'approved') {
            $profile->user->update(['role' => 'teacher']); 
        }

        return redirect()->back()->with('success', "Status akun pengajar {$profile->user->name} berhasil diperbarui menjadi " . strtoupper($request->verification_status));
    }
}