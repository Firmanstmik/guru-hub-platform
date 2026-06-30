<?php

namespace App\Http\Controllers;

use App\Models\Categori;
use App\Models\Course;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\User;
use App\Support\StudentJenjang;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrowseController extends Controller
{
    public function category(Categori $category): View|RedirectResponse
    {
        abort_unless($category->is_active, 404);

        $levels = $this->levelsForCategory($category);

        if ($levels->isEmpty()) {
            return view('browse.empty', [
                'title' => $category->name,
                'message' => $this->jenjangMismatchMessage() ?? 'Kursus untuk mapel ini segera hadir. Daftar dulu untuk mendapat kabar terbaru.',
                'category' => $category,
            ]);
        }

        if ($studentLevel = StudentJenjang::forUser()) {
            if ($levels->contains('id', $studentLevel->id)) {
                return redirect()->route('browse.subjects', [
                    'category' => $category->slug,
                    'level' => $studentLevel->slug,
                ]);
            }
        }

        if ($levels->count() === 1) {
            return redirect()->route('browse.subjects', [
                'category' => $category->slug,
                'level' => $levels->first()->slug,
            ]);
        }

        return view('browse.levels', compact('category', 'levels'));
    }

    public function subjects(Categori $category, EducationLevel $level): View|RedirectResponse
    {
        abort_unless($category->is_active && $level->is_active, 404);
        $this->assertStudentJenjang($level);

        $subjects = Subject::active()
            ->ordered()
            ->where('category_id', $category->id)
            ->where('education_level_id', $level->id)
            ->withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->get();

        if ($subjects->count() === 1) {
            return redirect()->route('browse.teachers', [
                'category' => $category,
                'level' => $level,
                'subject' => $subjects->first(),
            ]);
        }

        return view('browse.subjects', compact('category', 'level', 'subjects'));
    }

    public function teachers(Categori $category, EducationLevel $level, Subject $subject): View
    {
        abort_unless($category->is_active && $level->is_active && $subject->is_active, 404);
        $this->assertStudentJenjang($level);

        $courses = Course::query()
            ->where('status', 'published')
            ->whereNotNull('teacher_id')
            ->where('education_level_id', $level->id)
            ->where(function ($q) use ($subject, $category) {
                $q->where('subject_id', $subject->id)
                    ->orWhere(function ($q2) use ($category) {
                        $q2->whereNull('subject_id')->where('category_id', $category->id);
                    });
            })
            ->whereHas('teacher', function ($q) use ($subject) {
                $q->where(function ($inner) use ($subject) {
                    $inner->whereHas('teachingSubjects', fn ($tq) => $tq->where('subjects.id', $subject->id))
                        ->orWhereDoesntHave('teachingSubjects');
                });
            })
            ->with(['teacher:id,name', 'category:id,name'])
            ->withCount(['students', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get()
            ->filter(fn (Course $course) => $course->teacher !== null);

        $teachers = $courses->groupBy('teacher_id')->map(function ($teacherCourses) use ($subject) {
            $course = $teacherCourses->first();
            $teacher = $course->teacher;

            return (object) [
                'user' => $teacher,
                'courses' => $teacherCourses,
                'course_count' => $teacherCourses->count(),
                'students_total' => (int) $teacherCourses->sum('students_count'),
                'rating' => (float) ($teacherCourses->avg('reviews_avg_rating') ?: 5.0),
                'primary_course' => $course,
                'subject' => $subject,
            ];
        })->values();

        return view('browse.teachers', compact('category', 'level', 'subject', 'teachers'));
    }

    public function teacherDetail(Categori $category, EducationLevel $level, Subject $subject, User $teacher): View
    {
        abort_unless($teacher->hasRole('guru'), 404);
        abort_unless($category->is_active && $level->is_active && $subject->is_active, 404);
        $this->assertStudentJenjang($level);

        $courses = Course::query()
            ->where('status', 'published')
            ->where('teacher_id', $teacher->id)
            ->where('education_level_id', $level->id)
            ->where(function ($q) use ($subject, $category) {
                $q->where('subject_id', $subject->id)
                    ->orWhere(function ($q2) use ($category) {
                        $q2->whereNull('subject_id')->where('category_id', $category->id);
                    });
            })
            ->with(['category:id,name'])
            ->withCount(['students', 'materials'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        abort_if($courses->isEmpty(), 404);

        $teacher->loadMissing('teacherProfile');

        return view('browse.teacher-detail', compact('category', 'level', 'subject', 'teacher', 'courses'));
    }

    private function levelsForCategory(Categori $category)
    {
        $levels = EducationLevel::active()
            ->ordered()
            ->whereHas('subjects', function ($q) use ($category) {
                $q->active()->where('category_id', $category->id);
            })
            ->get();

        if ($studentLevel = StudentJenjang::forUser()) {
            return $levels->where('id', $studentLevel->id)->values();
        }

        return $levels;
    }

    private function assertStudentJenjang(EducationLevel $level): void
    {
        $studentLevel = StudentJenjang::forUser();

        if ($studentLevel && $studentLevel->id !== $level->id) {
            abort(404);
        }
    }

    private function jenjangMismatchMessage(): ?string
    {
        $studentLevel = StudentJenjang::forUser();

        if (! $studentLevel) {
            return null;
        }

        return "Mapel ini tidak tersedia untuk jenjang {$studentLevel->name}. Silakan pilih mata pelajaran sesuai jenjang kamu.";
    }
}
