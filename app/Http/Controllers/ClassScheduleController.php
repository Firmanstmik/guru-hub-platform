<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Course;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading data kursus dan guru pengajarnya
        $query = ClassSchedule::with(['course.teacher']);

        // Filter berdasarkan Platform (Zoom / Google Meet)
        if ($request->has('platform') && $request->platform != '') {
            $query->where('platform', $request->platform);
        }

        // Filter berdasarkan status waktu (Mendatang / Selesai)
        if ($request->has('time_status') && $request->time_status != '') {
            if ($request->time_status === 'upcoming') {
                $query->where('start_time', '>=', now());
            } elseif ($request->time_status === 'past') {
                $query->where('end_time', '<', now());
            }
        }

        $schedules = $query->orderBy('start_time', 'asc')->paginate(10)->withQueryString();
        
        // Data pendukung untuk kebutuhan form tambah/edit modal jika admin membuatkan jadwal
        $courses = Course::where('status', 'published')->orderBy('title')->get();

        return view('admin.schedules', compact('schedules', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'topic'            => 'required|string|max:255',
            'start_time'       => 'required|date|after_or_equal:now',
            'end_time'         => 'required|date|after:start_time',
            'platform'         => 'required|in:zoom,google_meet',
            'meeting_link'     => 'nullable|url',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
        ]);

        ClassSchedule::create($validated);

        return redirect()->back()->with('success', 'Jadwal sesi live class baru berhasil ditambahkan!');
    }

    public function update(Request $request, ClassSchedule $schedule)
    {
        $validated = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'topic'            => 'required|string|max:255',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'platform'         => 'required|in:zoom,google_meet',
            'meeting_link'     => 'nullable|url',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
        ]);

        $schedule->update($validated);

        return redirect()->back()->with('success', 'Detail jadwal sesi berhasil diperbarui!');
    }

    public function destroy(ClassSchedule $schedule)
    {
        // Proteksi: Jika sudah ada siswa yang melakukan booking sesi ini, cegah penghapusan sembarangan
        if ($schedule->bookings()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Sudah ada murid yang melakukan booking pada jadwal sesi ini.');
        }

        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal sesi berhasil dihapus dari sistem!');
    }
}
