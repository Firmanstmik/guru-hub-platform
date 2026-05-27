<?php

namespace Database\Seeders;

use App\Models\Categori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Bahasa Jepang', 
            'Bahasa Inggris', 
            'Matematika', 
            'Mengaji', 
            'Musik / Vokal', 
            'Skill Kerja Jepang'
        ];

        foreach ($categories as $cat) {
            Categori::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }
    }
}
