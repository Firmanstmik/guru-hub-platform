<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar dengan Eager Loading bertingkat agar hemat database query
        $query = Booking::with([
            'student', 
            'schedule.course.teacher'
        ]);

        // Fitur Filter Status (Jika admin ingin menyaring data tertentu)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bookings', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:booked,attended,absent,cancelled',
        ]);

        $booking->update($validated);

        // Opsional: Integrasi notifikasi WhatsApp ke Siswa/Guru bisa dipicu di sini

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Data booking berhasil dihapus dari sistem!');
    }
}
