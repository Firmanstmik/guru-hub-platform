<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\CourseMaterial;
use App\Models\UserProgress;
use App\Support\ProgressMorphType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GuruScheduleController extends Controller
{
    public function create(Request $request)
    {
        $materialId = $request->query('material_id');

        if (!$materialId) {
            return redirect()->back()->with('error', 'Materi tidak valid atau tidak ditemukan.');
        }

        $material = CourseMaterial::with('course')->findOrFail($materialId);
        $user = Auth::user();

        if (!$user->hasRole('admin') && $material->course->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk materi di kelas ini.');
        }

        $completedCount = UserProgress::where('progressable_type', ProgressMorphType::MATERIAL)
            ->where('progressable_id', $materialId)
            ->count();

        if ($completedCount < 5) {
            return redirect()->back()->with('error', 'Gagal memuat form. Minimal 5 siswa harus menyelesaikan materi ini terlebih dahulu.');
        }

        return view('guru.materi-schedule', compact('material', 'completedCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id'      => 'required|integer|exists:course_materials,id',
            'topic'            => 'required|string|max:255',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'platform'         => 'required|in:zoom,google_meet',
            'meeting_link'     => 'nullable|url|max:255',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
        ], [
            'end_time.after'   => 'Waktu selesai harus jatuh setelah waktu mulai sesi.',
            'meeting_link.url' => 'Format tautan link room meeting tidak valid.'
        ]);

        $startTime = Carbon::parse($request->start_time);
        if ($startTime->lt(now())) {
            return back()
                ->withErrors(['start_time' => 'Waktu mulai tidak boleh menggunakan waktu di masa lalu.'])
                ->withInput();
        }

        try {
            $material = CourseMaterial::findOrFail($request->material_id);

            $completedCount = UserProgress::where('progressable_type', ProgressMorphType::MATERIAL)
                ->where('progressable_id', $request->material_id)
                ->count();

            if ($completedCount < 5) {
                return redirect()->back()->with('error', 'Aksi ditolak. Ketentuan 5 siswa selesai belum terpenuhi.');
            }

            ClassSchedule::create([
                'course_id'        => $material->course_id,
                'material_id'      => $material->id,
                'topic'            => $request->topic,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'platform'         => $request->platform,
                'meeting_link'     => $request->meeting_link,
                'meeting_id'       => $request->meeting_id,
                'meeting_password' => $request->meeting_password,
            ]);

            return redirect('/materials')->with('success', 'Jadwal Live Class berhasil diterbitkan dan otomatis muncul di ruang belajar siswa.');
        } catch (Exception $e) {
            Log::error('Gagal membuat jadwal live class: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan internal sistem saat menyimpan jadwal.');
        }
    }
}
