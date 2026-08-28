<?php

use App\Models\Categori;
use App\Models\EducationLevel;
use App\Models\Subject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        EducationLevel::query()
            ->whereIn('slug', ['persiapan-ujian', 'minat-bakat'])
            ->get()
            ->each(function (EducationLevel $level) {
                $level->update(['sort_order' => $level->sort_order + 1]);
            });

        $umum = EducationLevel::updateOrCreate(
            ['slug' => 'umum'],
            [
                'name' => 'Umum',
                'icon' => '📚',
                'sort_order' => 4,
                'is_active' => true,
            ]
        );

        $categoryIds = [];
        $featured = [
            ['name' => 'Pengembangan Diri', 'icon' => 'sparkles', 'sort_order' => 13],
        ];
        foreach ($featured as $cat) {
            $model = Categori::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['is_active' => true, 'is_featured' => true])
            );
            $categoryIds[$cat['name']] = $model->id;
        }

        $rows = [
            ['Pengembangan Diri', 'Soft Skills & Kepemimpinan'],
            ['Pengembangan Diri', 'Persiapan Karir'],
            ['Bahasa Inggris', 'Bahasa Inggris Umum'],
            ['Informatika', 'Literasi Digital'],
            ['Bahasa Indonesia', 'Komunikasi & Presentasi'],
            ['IPS', 'Kewirausahaan'],
            ['Matematika', 'Literasi Finansial'],
        ];

        $sort = (int) Subject::query()->max('sort_order');
        foreach ($rows as [$categoryName, $subjectName]) {
            if (! isset($categoryIds[$categoryName])) {
                $model = Categori::firstOrCreate(
                    ['slug' => Str::slug($categoryName)],
                    ['name' => $categoryName, 'is_active' => true, 'is_featured' => false, 'sort_order' => 99]
                );
                $categoryIds[$categoryName] = $model->id;
            }

            $sort++;
            Subject::updateOrCreate(
                [
                    'category_id' => $categoryIds[$categoryName],
                    'education_level_id' => $umum->id,
                    'slug' => Str::slug($subjectName),
                ],
                [
                    'name' => $subjectName,
                    'sort_order' => $sort,
                    'is_active' => true,
                ]
            );
        }
    }

    public function down(): void
    {
        $umum = EducationLevel::query()->where('slug', 'umum')->first();
        if ($umum) {
            Subject::query()->where('education_level_id', $umum->id)->delete();
            $umum->delete();
        }

        EducationLevel::query()
            ->whereIn('slug', ['persiapan-ujian', 'minat-bakat'])
            ->get()
            ->each(function (EducationLevel $level) {
                $level->update(['sort_order' => max(1, $level->sort_order - 1)]);
            });
    }
};
