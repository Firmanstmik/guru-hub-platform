<?php

namespace Database\Seeders;

use App\Models\Categori;
use App\Models\EducationLevel;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EducationTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'SD', 'slug' => 'sd', 'icon' => '📘', 'sort_order' => 1],
            ['name' => 'SMP', 'slug' => 'smp', 'icon' => '📗', 'sort_order' => 2],
            ['name' => 'SMA / SMK', 'slug' => 'sma-smk', 'icon' => '📕', 'sort_order' => 3],
            ['name' => 'Persiapan Ujian', 'slug' => 'persiapan-ujian', 'icon' => '🎯', 'sort_order' => 4],
            ['name' => 'Minat & Bakat', 'slug' => 'minat-bakat', 'icon' => '🎨', 'sort_order' => 5],
        ];

        $levelIds = [];
        foreach ($levels as $level) {
            $model = EducationLevel::updateOrCreate(
                ['slug' => $level['slug']],
                array_merge($level, ['is_active' => true])
            );
            $levelIds[$level['slug']] = $model->id;
        }

        $featuredCategories = [
            ['name' => 'Matematika', 'icon' => 'calculator', 'sort_order' => 1],
            ['name' => 'Bahasa Indonesia', 'icon' => 'book-open', 'sort_order' => 2],
            ['name' => 'Bahasa Inggris', 'icon' => 'globe', 'sort_order' => 3],
            ['name' => 'Bahasa Jepang', 'icon' => 'translate', 'sort_order' => 4],
            ['name' => 'IPA', 'icon' => 'flask', 'sort_order' => 5],
            ['name' => 'IPS', 'icon' => 'earth', 'sort_order' => 6],
            ['name' => 'Informatika', 'icon' => 'monitor', 'sort_order' => 7],
            ['name' => 'Mengaji', 'icon' => 'moon', 'sort_order' => 8],
            ['name' => 'Musik / Vokal', 'icon' => 'music', 'sort_order' => 9],
            ['name' => 'Seni & Menggambar', 'icon' => 'palette', 'sort_order' => 10],
            ['name' => 'Persiapan UTBK', 'icon' => 'graduation-cap', 'sort_order' => 11],
            ['name' => 'Olimpiade', 'icon' => 'trophy', 'sort_order' => 12],
        ];

        $categoryIds = [];
        foreach ($featuredCategories as $cat) {
            $model = Categori::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                    'is_featured' => true,
                ]
            );
            $categoryIds[$cat['name']] = $model->id;
        }

        // [category_name, subject_name] per jenjang
        $taxonomy = [
            'sd' => [
                ['Matematika', 'Matematika'],
                ['Bahasa Indonesia', 'Bahasa Indonesia'],
                ['Bahasa Inggris', 'Bahasa Inggris'],
                ['IPA', 'IPA'],
                ['IPS', 'IPS'],
                ['Mengaji', 'Pendidikan Agama'],
                ['Mengaji', 'Mengaji'],
                ['Bahasa Indonesia', 'Calistung (Kelas 1–3)'],
                ['Bahasa Indonesia', 'Tematik SD'],
            ],
            'smp' => [
                ['Matematika', 'Matematika'],
                ['Bahasa Indonesia', 'Bahasa Indonesia'],
                ['Bahasa Inggris', 'Bahasa Inggris'],
                ['IPA', 'IPA'],
                ['IPS', 'IPS'],
                ['Bahasa Jepang', 'Bahasa Jepang'],
                ['Bahasa Arab', 'Bahasa Arab'],
                ['Informatika', 'Informatika'],
            ],
            'sma-smk' => [
                ['Matematika', 'Matematika'],
                ['Bahasa Indonesia', 'Bahasa Indonesia'],
                ['Bahasa Inggris', 'Bahasa Inggris'],
                ['IPA', 'Fisika'],
                ['IPA', 'Kimia'],
                ['IPA', 'Biologi'],
                ['IPS', 'Ekonomi'],
                ['IPS', 'Akuntansi'],
                ['IPS', 'Sejarah'],
                ['IPS', 'Geografi'],
                ['IPS', 'Sosiologi'],
                ['Informatika', 'Informatika'],
                ['Bahasa Jepang', 'Bahasa Jepang'],
                ['Bahasa Korea', 'Bahasa Korea'],
                ['Bahasa Mandarin', 'Bahasa Mandarin'],
            ],
            'persiapan-ujian' => [
                ['Persiapan UTBK', 'UTBK SNBT'],
                ['Olimpiade', 'Olimpiade Sains'],
                ['Olimpiade', 'OSN'],
                ['Persiapan UTBK', 'Persiapan ANBK'],
                ['Persiapan UTBK', 'Persiapan Ujian Sekolah'],
            ],
            'minat-bakat' => [
                ['Musik / Vokal', 'Musik / Vokal'],
                ['Musik / Vokal', 'Piano'],
                ['Musik / Vokal', 'Gitar'],
                ['Seni & Menggambar', 'Menggambar'],
                ['Seni & Menggambar', 'Tari'],
                ['Seni & Menggambar', 'Public Speaking'],
            ],
        ];

        $sort = 0;
        $activeSubjectKeys = [];

        foreach ($taxonomy as $levelSlug => $rows) {
            foreach ($rows as [$categoryName, $subjectName]) {
                if (! isset($categoryIds[$categoryName])) {
                    $model = Categori::updateOrCreate(
                        ['slug' => Str::slug($categoryName)],
                        [
                            'name' => $categoryName,
                            'is_active' => true,
                            'is_featured' => false,
                            'sort_order' => 99,
                        ]
                    );
                    $categoryIds[$categoryName] = $model->id;
                }

                $sort++;
                $subject = Subject::updateOrCreate(
                    [
                        'category_id' => $categoryIds[$categoryName],
                        'education_level_id' => $levelIds[$levelSlug],
                        'slug' => Str::slug($subjectName),
                    ],
                    [
                        'name' => $subjectName,
                        'sort_order' => $sort,
                        'is_active' => true,
                    ]
                );

                $activeSubjectKeys[] = $subject->id;
            }
        }

        Subject::query()
            ->whereNotIn('id', $activeSubjectKeys)
            ->update(['is_active' => false]);
    }
}
