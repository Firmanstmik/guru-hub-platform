<?php

namespace App\Support;

class CategoryIcons
{
    public static function meta(string $slug): array
    {
        return self::map()[$slug] ?? [
            'icon' => 'book-open',
            'from' => '#0E7490',
            'to' => '#22D3EE',
            'tagline' => 'Jelajahi kursus dan pengajar terbaik.',
        ];
    }

    public static function map(): array
    {
        return [
            'matematika' => [
                'icon' => 'calculator',
                'from' => '#1E40AF',
                'to' => '#22D3EE',
                'tagline' => 'Kuasai konsep & latihan soal dari SD hingga SMA.',
            ],
            'bahasa-indonesia' => [
                'icon' => 'book-open',
                'from' => '#0A1A4F',
                'to' => '#3B82F6',
                'tagline' => 'Membaca, menulis, dan sastra dengan metode terstruktur.',
            ],
            'bahasa-inggris' => [
                'icon' => 'globe',
                'from' => '#0E7490',
                'to' => '#5EEAD4',
                'tagline' => 'Grammar, conversation, dan persiapan ujian internasional.',
            ],
            'bahasa-jepang' => [
                'icon' => 'translate',
                'from' => '#1E3A8A',
                'to' => '#60A5FA',
                'tagline' => 'Hiragana, kanji, dan persiapan JLPT.',
            ],
            'ipa' => [
                'icon' => 'flask',
                'from' => '#0F766E',
                'to' => '#2DD4BF',
                'tagline' => 'Eksperimen virtual & pemahaman sains menyeluruh.',
            ],
            'ips' => [
                'icon' => 'earth',
                'from' => '#134E4A',
                'to' => '#14B8A6',
                'tagline' => 'Sejarah, geografi, dan ilmu sosial interaktif.',
            ],
            'informatika' => [
                'icon' => 'monitor',
                'from' => '#312E81',
                'to' => '#6366F1',
                'tagline' => 'Coding, logika, dan literasi digital masa kini.',
            ],
            'mengaji' => [
                'icon' => 'moon',
                'from' => '#0A1A4F',
                'to' => '#0E7490',
                'tagline' => 'Tahsin, hafalan, dan pemahaman agama yang tenang.',
            ],
            'musik-vokal' => [
                'icon' => 'music',
                'from' => '#6D28D9',
                'to' => '#A78BFA',
                'tagline' => 'Vokal, piano, gitar dengan guru berpengalaman.',
            ],
            'seni-menggambar' => [
                'icon' => 'palette',
                'from' => '#BE185D',
                'to' => '#F472B6',
                'tagline' => 'Menggambar, tari, dan ekspresi kreatif.',
            ],
            'persiapan-utbk' => [
                'icon' => 'graduation-cap',
                'from' => '#0E7490',
                'to' => '#22D3EE',
                'tagline' => 'Strategi UTBK SNBT & simulasi soal terbaru.',
            ],
            'olimpiade' => [
                'icon' => 'trophy',
                'from' => '#B45309',
                'to' => '#FBBF24',
                'tagline' => 'OSN & olimpiade sains tingkat nasional.',
            ],
        ];
    }

    public static function levelIcon(string $slug): string
    {
        return match ($slug) {
            'sd' => 'backpack',
            'smp' => 'layers',
            'sma-smk' => 'graduation-cap',
            'persiapan-ujian' => 'clipboard-check',
            'minat-bakat' => 'star',
            default => 'book-open',
        };
    }
}
