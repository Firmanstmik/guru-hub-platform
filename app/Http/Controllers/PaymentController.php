<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading data siswa, kelas, dan admin pembantu yang memverifikasi
        $query = Payment::with(['student', 'course', 'verifier']);

        // Filter berdasarkan Status Pembayaran (pending, success, failed)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Fitur Pencarian berdasarkan Nomor Invoice atau Nama Siswa
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.payment', compact('payments'));
    }

    /**
     * Menyetujui Pembayaran Berdasarkan Bukti yang Valid
     */
    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $payment->update([
            'status' => 'success',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => null
        ]);

        // Opsional: Daftarkan siswa ke kelas secara otomatis (M2M table course_students)
        // jika belum terdaftar saat checkout pending.
        $payment->course->students()->syncWithoutDetaching([
            $payment->student_id => ['status' => 'active']
        ]);

        return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} berhasil disetujui!");
    }

    /**
     * Menolak Pembayaran Transaksi
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->update([
            'status' => 'failed',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} resmi ditolak.");
    }
}