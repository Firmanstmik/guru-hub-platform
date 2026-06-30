<?php

namespace App\Support;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Collection;

class CourseCatalog
{
    public static function subjectsFor(User $user): Collection
    {
        $query = Subject::query()
            ->active()
            ->with(['category:id,name', 'educationLevel:id,name,slug,icon'])
            ->ordered();

        if ($user->hasRole('guru') && ! $user->hasRole('admin')) {
            $user->loadMissing('teachingSubjects');

            if ($user->teachingSubjects->isEmpty()) {
                return collect();
            }

            $query->whereIn('id', $user->teachingSubjects->pluck('id'));
        }

        return $query->get();
    }

    public static function assertTeacherMayUseSubject(User $user, int $subjectId): Subject
    {
        $subject = Subject::query()->active()->findOrFail($subjectId);

        if ($user->hasRole('admin')) {
            return $subject;
        }

        $allowed = $user->teachingSubjects()
            ->where('subjects.id', $subjectId)
            ->exists();

        abort_unless($allowed, 403, 'Mapel ini belum Anda daftarkan di profil pengajar.');

        return $subject;
    }

    public static function courseLabel(Course $course): string
    {
        $course->loadMissing(['subject', 'educationLevel', 'category']);

        $meta = collect([
            $course->educationLevel?->name,
            $course->subject?->name ?? $course->category?->name,
        ])->filter()->implode(' · ');

        return $meta ? "{$course->title} — {$meta}" : $course->title;
    }
}
