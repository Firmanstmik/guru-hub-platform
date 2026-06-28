<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Categori;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use App\Support\MediaDefaults;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $stats = $this->buildStats();
        $featuredCourses = $this->buildFeaturedCourses();
        $categories = Categori::orderBy('name')->pluck('name')->prepend('Semua')->values();
        $dashboard = $this->buildDashboardPreview();
        $testimonials = $this->buildTestimonials();
        $partners = $this->buildLearningDomains();

        return view('landingpage.home', compact(
            'stats',
            'featuredCourses',
            'categories',
            'dashboard',
            'testimonials',
            'partners',
        ));
    }

    private function buildStats(): array
    {
        $avgRating = Review::avg('rating');

        return [
            'students' => User::role('siswa')->count(),
            'teachers' => User::role('guru')->count(),
            'courses' => Course::where('status', 'published')->count(),
            'certificates' => Certificate::count(),
            'rating' => $avgRating ? number_format((float) $avgRating, 1) : null,
        ];
    }

    private function buildFeaturedCourses()
    {
        $courses = Course::query()
            ->where('status', 'published')
            ->with(['category:id,name', 'teacher:id,name'])
            ->withCount(['students', 'materials', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(8)
            ->get();

        if ($courses->isEmpty()) {
            return collect();
        }

        $topStudents = $courses->max('students_count');

        return $courses->map(function (Course $course) use ($topStudents) {
            $completed = DB::table('course_students')
                ->where('course_id', $course->id)
                ->where('status', 'completed')
                ->count();
            $enrolled = max(1, (int) $course->students_count);
            $progress = (int) round(($completed / $enrolled) * 100);

            $badge = null;
            if ($course->created_at && $course->created_at->gte(now()->subDays(30))) {
                $badge = 'Baru';
            } elseif ($course->students_count === $topStudents && $topStudents > 0) {
                $badge = 'Bestseller';
            }

            return (object) [
                'id' => $course->id,
                'title' => $course->title,
                'category' => $course->category,
                'category_name' => $course->category?->name ?? 'Kursus',
                'cover_url' => MediaDefaults::coverUrl($course->cover_image, 'course'),
                'instructor' => $course->teacher?->name ?? 'Pengajar terverifikasi',
                'modules' => (int) $course->materials_count,
                'rating' => $course->reviews_avg_rating
                    ? number_format((float) $course->reviews_avg_rating, 1)
                    : '5.0',
                'students_count' => (int) $course->students_count,
                'progress' => min(100, $progress),
                'badge' => $badge,
            ];
        });
    }

    private function buildDashboardPreview(): array
    {
        $showcaseStudent = User::role('siswa')->orderBy('id')->first();
        $studentName = $showcaseStudent?->name
            ? explode(' ', trim($showcaseStudent->name))[0]
            : 'Siswa';

        $avgProgress = 0;
        if ($showcaseStudent) {
            $enrolled = DB::table('course_students')->where('student_id', $showcaseStudent->id)->count();
            $completed = DB::table('course_students')
                ->where('student_id', $showcaseStudent->id)
                ->where('status', 'completed')
                ->count();
            $avgProgress = $enrolled > 0 ? (int) round(($completed / $enrolled) * 100) : 0;
        }

        if ($avgProgress === 0) {
            $totalEnrolled = DB::table('course_students')->count();
            $totalCompleted = DB::table('course_students')->where('status', 'completed')->count();
            $avgProgress = $totalEnrolled > 0
                ? (int) round(($totalCompleted / $totalEnrolled) * 100)
                : 0;
        }

        $activeCourses = Course::query()
            ->where('status', 'published')
            ->withCount('students')
            ->orderByDesc('students_count')
            ->orderByDesc('id')
            ->take(3)
            ->get()
            ->map(function (Course $course) {
                $completed = DB::table('course_students')
                    ->where('course_id', $course->id)
                    ->where('status', 'completed')
                    ->count();
                $enrolled = max(1, (int) $course->students_count);

                return [
                    'title' => $course->title,
                    'progress' => min(100, (int) round(($completed / $enrolled) * 100)),
                    'cover' => MediaDefaults::coverUrl($course->cover_image, 'course'),
                ];
            })
            ->values()
            ->all();

        $nextLive = ClassSchedule::query()
            ->with(['course.teacher'])
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->first();

        $latestCert = Certificate::query()
            ->with('course:id,title')
            ->latest('issued_at')
            ->first();

        $weeklyStudents = User::role('siswa')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $prevWeekStudents = User::role('siswa')
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();

        $weeklyGrowth = $prevWeekStudents > 0
            ? (int) round((($weeklyStudents - $prevWeekStudents) / $prevWeekStudents) * 100)
            : ($weeklyStudents > 0 ? 100 : 0);

        $avgRating = Review::avg('rating');

        return [
            'student_name' => $studentName,
            'avg_progress' => $avgProgress,
            'active_courses' => $activeCourses,
            'next_live' => $nextLive ? [
                'title' => $nextLive->topic ?: ($nextLive->course?->title ?? 'Kelas Live'),
                'teacher' => $nextLive->course?->teacher?->name ?? 'Pengajar GuruHub',
            ] : null,
            'latest_certificate' => $latestCert ? [
                'title' => $latestCert->course?->title ?? 'Sertifikat Kursus',
                'label' => $latestCert->issued_at?->isToday()
                    ? 'Diperoleh hari ini'
                    : 'Diperoleh ' . $latestCert->issued_at?->diffForHumans(),
            ] : null,
            'weekly_students' => $weeklyStudents,
            'weekly_growth' => $weeklyGrowth,
            'platform_rating' => $avgRating ? number_format((float) $avgRating, 1) : '5.0',
        ];
    }

    private function buildTestimonials()
    {
        return Review::query()
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with(['student:id,name', 'teacher:id,name', 'course:id,title'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function (Review $review, int $i) {
                $gradients = [
                    ['from' => '#14B8A6', 'to' => '#0E7490'],
                    ['from' => '#0E7490', 'to' => '#0A1A4F'],
                    ['from' => '#5EEAD4', 'to' => '#14B8A6'],
                ];
                $g = $gradients[$i % 3];

                return [
                    'name' => $review->student?->name ?? 'Siswa GuruHub',
                    'role' => $review->course?->title
                        ? 'Siswa · ' . $review->course->title
                        : 'Pengguna GuruHub',
                    'quote' => $review->comment,
                    'from' => $g['from'],
                    'to' => $g['to'],
                ];
            });
    }

    private function buildLearningDomains(): array
    {
        $palette = [
            ['from' => '#3B82F6', 'to' => '#0E7490'],
            ['from' => '#1E40AF', 'to' => '#22D3EE'],
            ['from' => '#14B8A6', 'to' => '#0E7490'],
            ['from' => '#3B82F6', 'to' => '#22D3EE'],
            ['from' => '#0E7490', 'to' => '#14B8A6'],
        ];

        $categories = Categori::orderBy('name')->take(5)->get();

        if ($categories->isEmpty()) {
            return [];
        }

        return $categories->values()->map(function ($cat, int $i) use ($palette) {
            return [
                'name' => $cat->name,
                'from' => $palette[$i % count($palette)]['from'],
                'to' => $palette[$i % count($palette)]['to'],
            ];
        })->all();
    }
}
