<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

trait AuthorizesCourseOwnership
{
    protected function authorizeOwnsCourse(Course $course): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return;
        }

        if (!$user->hasRole('guru') || (int) $course->teacher_id !== (int) $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk kelas ini.');
        }
    }

    protected function authorizeOwnsCourseId(int $courseId): void
    {
        $this->authorizeOwnsCourse(Course::findOrFail($courseId));
    }
}
