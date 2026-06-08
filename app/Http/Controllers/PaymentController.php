<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CompanyAccount;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;

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
            $banks = CompanyAccount::where('is_active', true)->get();
            return view('student.payment', compact('booking', 'studentPayments', 'banks'));
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

        $newPath = null;
        $oldPath = null;

        DB::beginTransaction();
        try {
            // 1. Cek apakah record payment untuk student dan kelas ini sudah pernah dibuat sebelumnya
            $existingPayment = Payment::where('student_id', Auth::id())
                ->where('course_id', $booking->course_id)
                ->first();

            // 2. Upload berkas fisik baru ke disk public jika ada
            if ($request->hasFile('payment_proof_path')) {
                $file = $request->file('payment_proof_path');
                $newPath = $file->store('payment_proofs', 'public');

                // Jika data sudah ada sebelumnya, catat path file lamanya untuk dihapus nanti
                if ($existingPayment && $existingPayment->payment_proof_path) {
                    $oldPath = $existingPayment->payment_proof_path;
                }
            }

            // 3. Gunakan nomor invoice lama jika update, buat baru jika data belum ada
            $invoiceNumber = $existingPayment ? $existingPayment->invoice_number : 'INV-' . date('Ym') . '-' . strtoupper(Str::random(5));

            // 4. Eksekusi Jaring Pengaman Otomatis: Update jika ada, Create jika kosong
            Payment::updateOrCreate(
                [
                    // Kondisi pencarian data unik
                    'student_id' => Auth::id(),
                    'course_id'  => $booking->course_id,
                ],
                [
                    // Data yang akan di-insert atau di-update
                    'invoice_number'     => $invoiceNumber,
                    'amount'             => $booking->total_amount,
                    'payment_proof_path' => $newPath ?? ($existingPayment ? $existingPayment->payment_proof_path : null),
                    'status'             => 'pending', // Set kembali ke pending agar divalidasi ulang oleh admin
                ]
            );

            DB::commit();

            // PEMBERSIHAN SUKSES: Jika ini proses update berkas, hapus fisik foto lama dari server
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return redirect('/history-bookings')->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi admin.');
        } catch (Exception $e) {
            DB::rollBack();

            // PEMBERSIHAN GAGAL: Hapus file baru yang gagal tercatat di DB agar tidak jadi sampah
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('Gagal menyimpan/memperbarui bukti pembayaran siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses bukti pembayaran karena kendala internal sistem.');
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

        // Mulai jaring pengaman Database Transaction
        DB::beginTransaction();
        try {
            // 1. Update status di tabel payments menjadi 'approved'
            $payment->update([
                'status'           => 'approved',
                'verified_at'      => now(),
                'verified_by'      => Auth::id(),
                'rejection_reason' => null
            ]);

            Booking::where('student_id', $payment->student_id)
                ->where('course_id', $payment->course_id)
                ->whereIn('status', ['pending', 'failed'])
                ->update([
                    'status' => 'success'
                ]);

            // 3. Daftarkan siswa ke kelas secara otomatis jika relasi model diset M2M
            if ($payment->course) {
                $payment->course->students()->syncWithoutDetaching([
                    $payment->student_id => ['status' => 'active']
                ]);
            }

            // Jika semua operasi di atas sukses tanpa error, komit data ke database
            DB::commit();

            return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} berhasil disetujui!");
        } catch (Exception $e) {
            // 🚨 GAGAL TOTAL: Jika salah satu proses di atas crash, batalkan semua perubahan!
            DB::rollBack();

            Log::error('Gagal menyetujui pembayaran ID ' . $payment->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses persetujuan pembayaran karena kendala internal sistem.');
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

        // Mulai transaksi database
        DB::beginTransaction();
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

            // Jika kedua proses di atas sukses, simpan permanen ke database
            DB::commit();

            return redirect()->back()->with('success', "Pembayaran invoice {$payment->invoice_number} telah ditolak.");
        } catch (Exception $e) {
            // 🚨 GAGAL: Batalkan semua perubahan jika salah satu query bermasalah
            DB::rollBack();

            Log::error('Gagal menolak pembayaran ID ' . $payment->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses penolakan pembayaran karena kendala internal sistem.');
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
