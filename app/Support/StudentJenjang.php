<?php

namespace App\Support;

use App\Models\EducationLevel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentJenjang
{
    public static function forUser(?User $user = null): ?EducationLevel
    {
        $user ??= Auth::user();

        if (! $user || ! $user->hasRole('siswa')) {
            return null;
        }

        $user->loadMissing('studentBiodata.educationLevel');

        return $user->studentBiodata?->educationLevel;
    }

    public static function slug(?User $user = null): ?string
    {
        return self::forUser($user)?->slug;
    }

    public static function id(?User $user = null): ?int
    {
        return self::forUser($user)?->id;
    }
}
