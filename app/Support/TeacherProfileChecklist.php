<?php

namespace App\Support;

use App\Models\TeacherProfile;
use App\Models\User;

class TeacherProfileChecklist
{
    public static function build(User $user, TeacherProfile $profile): array
    {
        $hasProfile = (bool) $profile->id;
        $subjectCount = $user->relationLoaded('teachingSubjects')
            ? $user->teachingSubjects->count()
            : $user->teachingSubjects()->count();

        $items = [
            [
                'key' => 'profile',
                'label' => 'Data profil dasar',
                'hint' => 'Headline & kontak',
                'done' => $hasProfile && filled($profile->title),
                'modal' => $hasProfile ? 'editProfileModal' : 'addProfileModal',
                'icon' => 'user',
            ],
            [
                'key' => 'subjects',
                'label' => 'Mapel & jenjang',
                'hint' => 'Minimal 1 mapel',
                'done' => $subjectCount > 0,
                'modal' => $hasProfile ? 'editProfileModal' : 'addProfileModal',
                'icon' => 'book-open',
            ],
            [
                'key' => 'bio',
                'label' => 'Biografi pengajar',
                'hint' => 'Deskripsi singkat',
                'done' => $hasProfile && filled($profile->bio),
                'modal' => $hasProfile ? 'editProfileModal' : 'addProfileModal',
                'icon' => 'file-text',
            ],
            [
                'key' => 'bank',
                'label' => 'Rekening pencairan',
                'hint' => 'Untuk pendapatan',
                'done' => $hasProfile && filled($profile->bank_account_number) && filled($profile->bank_name),
                'modal' => $hasProfile ? 'editProfileModal' : 'addProfileModal',
                'icon' => 'credit-card',
            ],
            [
                'key' => 'photo',
                'label' => 'Foto profil',
                'hint' => 'Tampil di katalog',
                'done' => $user->hasCustomAvatar(),
                'modal' => 'uploadMediaModal',
                'icon' => 'upload',
            ],
            [
                'key' => 'cv',
                'label' => 'Berkas CV',
                'hint' => 'PDF portfolio',
                'done' => $hasProfile && filled($profile->cv_file),
                'modal' => 'uploadMediaModal',
                'icon' => 'file-text',
            ],
        ];

        $doneCount = collect($items)->where('done', true)->count();
        $total = count($items);

        return [
            'items' => $items,
            'done' => $doneCount,
            'total' => $total,
            'percent' => $total > 0 ? (int) round(($doneCount / $total) * 100) : 0,
            'is_complete' => $doneCount === $total,
        ];
    }

    public static function verificationLabel(?string $status): string
    {
        return match ($status) {
            'approved' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu verifikasi',
        };
    }

    public static function verificationVariant(?string $status): string
    {
        return match ($status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }

    public static function genderLabel(?string $gender): string
    {
        return match ($gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '—',
        };
    }
}
