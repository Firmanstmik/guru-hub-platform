<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\TeacherEarning;
use Illuminate\Http\Request;

class TeacherEarningController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading data guru dan detail transaksi pembayaran asal
        $query = TeacherEarning::with(['teacher', 'payment.course']);

        // Filter berdasarkan Status Pencairan (pending, paid)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Fitur Pencarian berdasarkan Nama Guru
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('teacher', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $earnings = $query->latest()->paginate(10)->withQueryString();

        // Menghitung ringkasan total dana untuk mempermudah monitoring Admin
        $totalPending = TeacherEarning::where('status', 'pending')->sum('amount_earned');
        $totalPaid = TeacherEarning::where('status', 'paid')->sum('amount_earned');

        return view('admin.earning', compact('earnings', 'totalPending', 'totalPaid'));
    }

    /**
     * Memproses transfer / mencairkan dana komisi ke rekening guru
     */
    public function updateStatus(Request $request, TeacherEarning $earning)
    {
        $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        if ($earning->status === 'paid' && $request->status === 'pending') {
            return redirect()->back()->with('error', 'Transaksi yang sudah dibayarkan tidak dapat diubah kembali menjadi pending.');
        }

        $earning->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', "Status pendapatan untuk {$earning->teacher->name} berhasil diperbarui menjadi " . strtoupper($request->status));
    }
}
