<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterToUserSeeder extends Seeder
{
    /**
     * 1.buat seeder via perintah terminal
     * php artisan make:seeder NamaSeeder
     * 2. isi file seeder seperti dibawah ini
     * 3. jalankan perintah seeder agar user di generate
     * php artisan db:seed --class=NamaSeeder
     */
    public function run(): void
    {
        // 1 bisa panggil model sebagai sumber data untuk generate user atau gunakan data static
        // $data = DataSumber::all();

        $data_master = [
            [
                'name' => 'fahmi',
                'email' => 'fahmi@gmail.com',
                'password' => '1234567890'
            ],
            [
                'name' => 'jihadul',
                'email' => 'jihadul@gmail.com',
                'password' => '1234567890'
            ],
        ];

        foreach ($data_master as $val) {
            // Cek apakah user dengan email tersebut sudah ada
            if (!User::where('email', $val['email'])->exists()) {
                User::create([
                    'name' => $val['name'],
                    'email' => $val['email'],
                    'password' => Hash::make($val['password']),
                ]);
                // Role "peneliti" akan otomatis diberikan jika ada booted() di model User
            }
        }
    }
}
