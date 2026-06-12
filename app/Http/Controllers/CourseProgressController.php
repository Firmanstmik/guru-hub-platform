<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseProgressController extends Controller
{
    public function toggleProgress(Request $request)
    {
        // 1. Validasi input request data dari AJAX
        $request->validate([
            'item_id'   => 'required|integer',
            'item_type' => 'required|in:video,material',
            'status'    => 'required|boolean', // true jika dicentang, false jika uncheck
        ]);

        try {
            $userId = Auth::id();
            $itemId = $request->item_id;

            // Konversi teks tipe data dari blade menjadi Namespace Model yang sah untuk Polymorphic DB
            $itemType = $request->item_type === 'video' 
                ? 'App\Models\Video' 
                : 'App\Models\Material';

            // 2. Eksekusi kondisi berdasarkan status centang checkbox
            if ($request->status) {
                // Jika dicentang (true), simpan ke database
                // Menggunakan updateOrCreate untuk mengantisipasi double-click spam dari user
                UserProgress::updateOrCreate(
                    [
                        'user_id'           => $userId,
                        'progressable_id'   => $itemId,
                        'progressable_type' => $itemType,
                    ]
                );
                $message = 'Materi berhasil ditandai sebagai selesai.';
            } else {
                // Jika centang dihapus (false), hapus baris datanya dari database
                UserProgress::where('user_id', $userId)
                    ->where('progressable_id', $itemId)
                    ->where('progressable_type', $itemType)
                    ->delete();
                $message = 'Tanda selesai pada materi berhasil dihapus.';
            }

            // 3. Mengembalikan respons sukses berformat JSON ke browser
            return response()->json([
                'success'         => true,
                'message'         => $message,
                'class_completed' => false // Bisa dikembangkan jika ingin otomatis memicu kelulusan
            ], 200);

        } catch (Exception $e) {
            // Mengembalikan respons error jika terjadi kegagalan sistem internal
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui progress karena kendala sistem.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
