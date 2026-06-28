<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

trait AuthorizesStudentCourseAccess
{
    protected function assertStudentEnrolledInCourse(int $courseId): void
    {
        $enrolled = Booking::where('student_id', Auth::id())
            ->where('course_id', $courseId)
            ->whereIn('status', ['success', 'completed'])
            ->exists();

        if (!$enrolled) {
            abort(403, 'Anda tidak memiliki akses ke konten kelas ini.');
        }
    }
}
