<?php

namespace App\Support;

use App\Models\User;

class AvatarDefaults
{
    public static function pathFor(User $user): string
    {
        $gender = $user->profileGender();

        if (! in_array($gender, ['L', 'P'], true)) {
            return 'assets/avatar/default-neutral.avif';
        }

        $suffix = $gender === 'P' ? 'p' : 'l';

        if ($user->hasRole('guru')) {
            return "assets/avatar/default-guru-{$suffix}.avif";
        }

        if ($user->hasRole('siswa')) {
            return "assets/avatar/default-siswa-{$suffix}.avif";
        }

        return 'assets/avatar/default-neutral.avif';
    }

    public static function urlFor(User $user): string
    {
        return asset(self::pathFor($user));
    }
}
