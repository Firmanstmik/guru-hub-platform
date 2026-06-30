<?php

namespace App\Http\Controllers;

use App\Models\Categori;
use App\Models\Course;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function category(Categori $category)
    {
        abort_unless($category->is_active, 404);

        $levels = EducationLevel::active()
            ->ordered()
            ->whereHas('subjects', function ($q) use ($category) {
                $q->active()->where('category_id', $category->id);
            })
            ->get();

        if ($levels->count() === 1) {
            return redirect()->route('browse.subjects', [
                'category' => $category->slug,
                'level' => $levels->first()->slug,
            ]);
        }

        return view('browse.levels', compact('category', 'levels'));
    }

    public function subjects(Categori $category, EducationLevel $level)
    {
        abort_unless($category->is_active && $level->is_active, 404);

        $subjects = Subject::active()
            ->ordered()
            ->where('category_id', $category->id)
            ->where('education_level_id', $level->id)
            ->withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->get();

        return view('browse.subjects', compact('category', 'level', 'subjects'));
    }

    public function teachers(Categori $category, EducationLevel $level, Subject $subject)
    {
        abort_unless(
            $category->is_active && $level->is_active && $subject->is_active
            && $subject->category_id === $category->id
            && $subject->education_level_id === $level->id,
            404
        );

        $courses = Course::query()
            ->where('status', 'published')
            ->where(function ($q) use ($subject, $category) {
                $q->where('subject_id', $subject->id)
                    ->orWhere(function ($q2) use ($category) {
                        $q2->whereNull('subject_id')->where('category_id', $category->id);
                    });
            })
            ->with(['teacher.teacherProfile', 'category'])
            ->withCount(['students', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $teachers = $courses->groupBy('teacher_id')->map(function ($teacherCourses) use ($subject) {
            $course = $teacherCourses->first();
            $teacher = $course->teacher;

            return (object) [
                'user' => $teacher,
                'courses' => $teacherCourses,
                'course_count' => $teacherCourses->count(),
                'students_total' => $teacherCourses->sum('students_count'),
                'rating' => $teacherCourses->avg('reviews_avg_rating') ?: 5.0,
                'primary_course' => $course,
                'subject' => $subject,
            ];
        })->values();

        return view('browse.teachers', compact('category', 'level', 'subject', 'teachers', 'courses'));
    }

    public function teacherDetail(Categori $category, EducationLevel $level, Subject $subject, User $teacher, Request $request)
    {
        abort_unless($teacher->hasRole('guru'), 404);

        $courses = Course::query()
            ->where('status', 'published')
            ->where('teacher_id', $teacher->id)
            ->where(function ($q) use ($subject, $category) {
                $q->where('subject_id', $subject->id)
                    ->orWhere(function ($q2) use ($category) {
                        $q2->whereNull('subject_id')->where('category_id', $category->id);
                    });
            })
            ->with(['category', 'reviews'])
            ->withCount(['students', 'materials'])
            ->withAvg('reviews', 'rating')
            ->get();

        abort_if($courses->isEmpty(), 404);

        $teacher->load('teacherProfile');

        return view('browse.teacher-detail', compact('category', 'level', 'subject', 'teacher', 'courses'));
    }
}
