<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class PaymentController extends Controller
{
    /**
     * TAMPILKAN FORM PEMBAYARAN (Siswa)
     */
    public function showPaymentForm($transaction_code)
    {
        try {
            // Cari booking berdasarkan kode unik transaksi, pastikan milik siswa yang login
            $booking = Booking::with('course')
                ->where('transaction_code', $transaction_code)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            // Riwayat pembayaran manual siswa
            $studentPayments = Payment::with('course')
                ->where('student_id', Auth::id())
                ->latest()
                ->get();

            return view('student.payment', compact('booking', 'studentPayments'));

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman form pembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * SIMPAN BUKTI PEMBAYARAN (Siswa)
     */
    public function storeStudentPayment(Request $request, $transaction_code)
    {
        // Validasi data booking induk terlebih dahulu
        $booking = Booking::where('transaction_code', $transaction_code)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        // Validasi upload bukti bayar dengan custom pesan Bahasa Indonesia (Max 5MB sesuai Docker)
        $request->validate([
            'payment_proof_path' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'payment_proof_path.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof_path.image'    => 'Berkas yang diunggah harus berupa gambar.',
            'payment_proof_path.mimes'    => 'Format gambar bukti pembayaran harus jpeg, png, atau jpg.',
            'payment_proof_path.max'      => 'Gagal mengunggah! Ukuran gambar bukti pembayaran terlalu besar (Maksimal 5 MB).'
        ]);

        $path = null;

        try {
            // Pembuatan Nomor Invoice Otomatis
            $invoiceNumber = 'INV-' . date('Ym') . '-' . strtoupper(Str::random(5));

            // Upload berkas fisik ke disk public
            if ($request->hasFile('payment_proof_path')) {
                $file = $request->file('payment_proof_path');
                $path = $file->store('payment_proofs', 'public');
            }

            // Simpan log ke tabel payments
            Payment::create([
                'student_id'         => Auth::id(),
                'course_id'          => $booking->course_id,
                'invoice_number'     => $invoiceNumber,
                'amount'             => $booking->total_amount,
                'payment_proof_path' => $path,
                'status'             => 'pending',
            ]);

            return redirect('/history-bookings')->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi admin.');

        } catch (Exception $e) {
            // Rollback bukti pembayaran jika gagal simpan database
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Gagal menyimpan bukti pembayaran siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirimkan bukti pembayaran karena kendala internal sistem.');
        }
    }

    /**
     * INDEX LOG TRANSAKSI (Admin)
     */
    public function index(Request $request)
    {
        try {
            $query = Payment::with(['student', 'course', 'verifier']);

            // Filter status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Pencarian data
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            }

            $payments = $query->latest()->paginate(10)->withQueryString();

            return view('admin.payment', compact('payments'));

        } catch (Exception $e) {
            Log::error('Gagal memuat index pembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data riwayat transaksi.');
        }
    }

    /**
     * APPROVE PEMBAYARAN (Admin)
     */
    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        try {
            // 1. Update status di tabel payments menjadi 'approved'
            $payment->update([
                'status'           => 'approved',
                'verified_at'      => now(),
                'verified_by'      => Auth::id(),
                'rejection_reason' => null
            ]);

            // 2. SINKRONISASI: Update status di tabel bookings menjadi 'success'
            Booking::where('student_id', $payment->student_id)
                ->where('course_id', $payment->course_id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'success'
                ]);

            // 3. Daftarkan siswa ke kelas secara otomatis jika relasi model diset M2M
            if ($payment->course) {
                $payment->course->students()->syncWithoutDetaching([
                    $payment->student_id => ['status' => 'active']
                ]);
            }

            return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} berhasil disetujui!");

        } catch (Exception $e) {
            Log::error('Gagal menyetujui pembayaran ID ' . $payment->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses persetujuan pembayaran karena kendala database.');
        }
    }

    /**
     * REJECT PEMBAYARAN (Admin)
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        // Validasi alasan penolakan wajib diisi
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi agar dipahami oleh siswa.',
            'rejection_reason.max'      => 'Alasan penolakan terlalu panjang, maksimal 255 karakter.'
        ]);

        try {
            // 1. Update status di tabel payments menjadi 'rejected'
            $payment->update([
                'status'           => 'rejected',
                'verified_at'      => now(),
                'verified_by'      => Auth::id(),
                'rejection_reason' => $request->rejection_reason
            ]);

            // 2. SINKRONISASI: Update status di tabel bookings menjadi 'failed'
            Booking::where('student_id', $payment->student_id)
                ->where('course_id', $payment->course_id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'failed'
                ]);

            return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} telah ditolak.");

        } catch (Exception $e) {
            Log::error('Gagal menolak pembayaran ID ' . $payment->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses penolakan pembayaran karena kendala sistem.');
        }
    }

    /**
     * DESTROY LOG TRANSAKSI (Admin)
     */
    public function destroy(Payment $payment)
    {
        try {
            $filePath = $payment->payment_proof_path;

            // Hapus rekam data database terlebih dahulu
            $payment->delete();

            // Jika sukses hapus data, bersihkan file gambar dari storage lokal agar tidak menumpuk
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('success', 'Data log transaksi pembayaran berhasil dibersihkan dari sistem!');

        } catch (Exception $e) {
            Log::error('Gagal menghapus log pembayaran ID ' . $payment->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membersihkan data transaksi dari sistem.');
        }
    }
}