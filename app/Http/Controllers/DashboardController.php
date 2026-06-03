<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function dashboardAdmin()
    {
        try {
            // Tempatkan kueri data/statistik dashboard admin di sini ke depannya
            return view('admin.dashboard');
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Admin: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat memuat halaman dashboard admin.');
        }
    }

    public function dashboardGuru()
    {
        try {
            // Tempatkan kueri data/statistik khusus kelas milik guru di sini ke depannya
            return view('guru.dashboard');
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Guru: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat memuat halaman dashboard guru.');
        }
    }

    public function dashboardSiswa()
    {
        try {
            // Tempatkan kueri data/statistik kelas yang diikuti siswa di sini ke depannya
            return view('student.dashboard');
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Siswa: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat memuat halaman dashboard siswa.');
        }
    }
}
