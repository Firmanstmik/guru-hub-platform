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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CertificateController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Eager loading data siswa, kelas, dan guru pengajarnya
            $query = Certificate::with(['student', 'course.teacher']);

            // Spatie: Saring sertifikat berdasarkan hak akses akun Guru
            if (!$user->hasRole('admin')) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }

            // Fitur Pencarian dibungkus closure agar tidak merusak filter role di atas
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

            // Spatie: Hanya mengambil user dengan peran murid/siswa
            $students = User::role('siswa')->orderBy('name')->get();

            // Spatie: Guru hanya boleh memilih kelas asuhannya sendiri untuk dropdown modal
            if ($user->hasRole('admin')) {
                $courses = Course::where('status', 'published')->orderBy('title')->get();
            } else {
                $courses = Course::where('status', 'published')
                    ->where('teacher_id', $user->id)
                    ->orderBy('title')
                    ->get();
            }

            if ($user->hasRole('admin')) {
                return view('admin.certificates', compact('certificates', 'students', 'courses'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.certificates', compact('certificates', 'students', 'courses'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman sertifikat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data sertifikat.');
        }
    }

    /**
     * STORE (Penerbitan Sertifikat & Update Kelulusan)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia (Maksimal di-set ke 5MB agar aman di Docker)
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id'  => 'required|exists:courses,id',
            'file'       => 'required|mimes:pdf|max:5120', 
            'issued_at'  => 'required|date',
        ], [
            'student_id.required' => 'Siswa penerima wajib ditentukan.',
            'student_id.exists'   => 'Siswa yang dipilih tidak terdaftar di sistem.',
            'course_id.required'  => 'Kelas/Kursus asal wajib ditentukan.',
            'course_id.exists'    => 'Kelas yang dipilih tidak valid.',
            'file.required'       => 'Dokumen sertifikat dalam bentuk PDF wajib diunggah.',
            'file.mimes'          => 'Format dokumen harus berupa file PDF.',
            'file.max'            => 'Gagal menerbitkan! Ukuran file PDF sertifikat terlalu besar (Maksimal 5 MB).',
            'issued_at.required'  => 'Tanggal terbit sertifikat wajib diisi.',
            'issued_at.date'      => 'Format tanggal terbit tidak valid.'
        ]);

        $uploadedPath = null;

        // Memulai DB Transaction manual agar sinkron dengan rollback file fisik di blok catch
        DB::beginTransaction();

        try {
            // Generate Kode Sertifikat Unik Otomatis (Format: GH-CERT-2026-RANDOM)
            $validated['certificate_code'] = 'GH-CERT-' . date('Y') . '-' . strtoupper(Str::random(6));

            // Upload File Sertifikat ke Storage Disk Public
            if ($request->hasFile('file')) {
                $uploadedPath = $request->file('file')->store('certificates', 'public');
                $validated['file_path'] = $uploadedPath;
            }

            // 1. Simpan data ke tabel certificates
            Certificate::create($validated);

            // 2. Update status kelulusan siswa di tabel course_students menjadi 'completed'
            DB::table('course_students')->updateOrInsert(
                [
                    'course_id'  => $validated['course_id'],
                    'student_id' => $validated['student_id'],
                ],
                [
                    'status'     => 'completed',
                    'updated_at' => now(),
                ]
            );

            // Jika semua operasi database berhasil, kunci perubahan data
            DB::commit();

            return redirect()->back()->with('success', 'Sertifikat baru berhasil diterbitkan dan status siswa diperbarui menjadi Lulus!');

        } catch (Exception $e) {
            // Rollback basis data jika salah satu kueri insert/update bermasalah
            DB::rollBack();

            // Rollback berkas fisik dari storage jika gagal entri data
            if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
                Storage::disk('public')->delete($uploadedPath);
            }

            Log::error('Gagal menerbitkan sertifikat baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menerbitkan sertifikat baru karena terjadi kendala internal sistem.');
        }
    }

    /**
     * DESTROY (Hapus Sertifikat)
     */
    public function destroy(Certificate $certificate)
    {
        try {
            $filePath = $certificate->file_path;

            // Hapus rekam data di database terlebih dahulu
            $certificate->delete();

            // Jika hapus data sukses, barulah hapus file fisik PDF dari storage server
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('success', 'Sertifikat berhasil dicabut dan dihapus dari sistem!');

        } catch (Exception $e) {
            Log::error('Gagal menghapus sertifikat ID ' . $certificate->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencabut sertifikat dari sistem karena kendala database.');
        }
    }
}