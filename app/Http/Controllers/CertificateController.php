<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Eager loading data siswa, kelas, dan guru pengajarnya
        $query = Certificate::with(['student', 'course.teacher']);

        // PERBAIKAN UTAMA 1: Saring sertifikat berdasarkan hak akses akun Guru
        if (!$user->hasRole('admin')) {
            $query->whereHas('course', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        // Fitur Pencarian (PERBAIKAN UTAMA 2: Dibungkus closure agar tidak merusak filter role di atas)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($nestedQuery) use ($search) {
                $nestedQuery->where('certificate_code', 'like', "%{$search}%")
                            ->orWhereHas('student', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
            });
        }

        $certificates = $query->latest()->paginate(10)->withQueryString();
        
        // Data pendukung untuk kebutuhan modal Tambah Sertifikat Manual
        $students = User::role('siswa')->orderBy('name')->get();
        
        // PERBAIKAN DROPDOWN MODAL: Guru hanya boleh memilih kelas asuhannya sendiri
        if ($user->hasRole('admin')) {
            $courses = Course::where('status', 'published')->orderBy('title')->get();
        } else {
            $courses = Course::where('status', 'published')
                             ->where('teacher_id', $user->id)
                             ->orderBy('title')
                             ->get();
        }

        return view('admin.certificates', compact('certificates', 'students', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'file' => 'required|mimes:pdf|max:3072', // Batasan file PDF maksimal 3MB
            'issued_at' => 'required|date',
        ]);

        // Generate Kode Sertifikat Unik Otomatis (Format: GH-CERT-2026-RANDOM)
        $validated['certificate_code'] = 'GH-CERT-' . date('Y') . '-' . strtoupper(Str::random(6));

        // Upload File Sertifikat ke Storage Local / Public
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('certificates', 'public');
            $validated['file_path'] = $path;
        }

        Certificate::create($validated);

        return redirect()->back()->with('success', 'Sertifikat baru berhasil diterbitkan!');
    }

    public function destroy(Certificate $certificate)
    {
        // Hapus file fisik sertifikat dari storage agar hemat ruang penyimpanan
        if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        $certificate->delete();

        return redirect()->back()->with('success', 'Sertifikat berhasil dicabut dan dihapus dari sistem!');
    }
}
