<?php

namespace Database\Seeders;

use App\Models\HomepageTestimonial;
use Illuminate\Database\Seeder;

class HomepageTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Alya Putri',
                'role_title' => 'Siswa SMA · Matematika',
                'quote' => 'Materi terstruktur dan mudah diikuti. Progress belajarku jadi terpantau jelas setiap minggu.',
                'rating' => 5,
                'gradient_from' => '#14B8A6',
                'gradient_to' => '#0E7490',
                'sort_order' => 1,
            ],
            [
                'name' => 'Rizky Pratama',
                'role_title' => 'Mahasiswa · Pemrograman Web',
                'quote' => 'Kelas live-nya interaktif. Saya bisa langsung tanya ke pengajar dan praktik di sesi yang sama.',
                'rating' => 5,
                'gradient_from' => '#3B82F6',
                'gradient_to' => '#22D3EE',
                'sort_order' => 2,
            ],
            [
                'name' => 'Dewi Lestari, S.Pd.',
                'role_title' => 'Pengajar Bahasa Inggris',
                'quote' => 'Dashboard pengajar lengkap — dari materi, jadwal, hingga pendapatan. Sangat membantu operasional kelas.',
                'rating' => 5,
                'gradient_from' => '#0E7490',
                'gradient_to' => '#0A1A4F',
                'sort_order' => 3,
            ],
            [
                'name' => 'Fajar Nugroho',
                'role_title' => 'Siswa SMP · IPA Terpadu',
                'quote' => 'Setelah menyelesaikan kursus, sertifikat langsung tersedia. Orang tua saya juga bisa pantau progresnya.',
                'rating' => 5,
                'gradient_from' => '#5EEAD4',
                'gradient_to' => '#14B8A6',
                'sort_order' => 4,
            ],
            [
                'name' => 'Siti Rahmawati',
                'role_title' => 'Kepala TU · SMK Negeri 2',
                'quote' => 'GuruHub memudahkan kami mengelola kelas digital dan komunikasi antara siswa dengan pengajar.',
                'rating' => 5,
                'gradient_from' => '#1E40AF',
                'gradient_to' => '#22D3EE',
                'sort_order' => 5,
            ],
            [
                'name' => 'Bagus Hermawan',
                'role_title' => 'Siswa · Aljabar Linear',
                'quote' => 'UI-nya rapi dan enak dipakai di HP. Belajar jadi konsisten karena ada reminder tugas dan live class.',
                'rating' => 5,
                'gradient_from' => '#14B8A6',
                'gradient_to' => '#3B82F6',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            HomepageTestimonial::updateOrCreate(
                ['name' => $item['name'], 'role_title' => $item['role_title']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
