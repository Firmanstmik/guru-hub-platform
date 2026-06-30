<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    public function subjectsByLevel(EducationLevel $level): JsonResponse
    {
        $subjects = Subject::query()
            ->active()
            ->where('education_level_id', $level->id)
            ->with('category:id,name,slug')
            ->ordered()
            ->get()
            ->map(fn (Subject $subject) => [
                'id' => $subject->id,
                'name' => $subject->name,
                'slug' => $subject->slug,
                'category_id' => $subject->category_id,
                'category_name' => $subject->category?->name,
                'education_level_id' => $subject->education_level_id,
            ]);

        return response()->json($subjects);
    }

    public function subjectsByTeacher(User $teacher): JsonResponse
    {
        abort_unless($teacher->hasRole('guru'), 404);

        $subjects = $teacher->teachingSubjects()
            ->active()
            ->with(['category:id,name', 'educationLevel:id,name,slug'])
            ->ordered()
            ->get()
            ->map(fn (Subject $subject) => [
                'id' => $subject->id,
                'name' => $subject->name,
                'category_name' => $subject->category?->name,
                'level_name' => $subject->educationLevel?->name,
                'education_level_id' => $subject->education_level_id,
                'category_id' => $subject->category_id,
            ]);

        return response()->json($subjects);
    }

    public function groupedSubjects(Request $request): JsonResponse
    {
        $levelId = $request->integer('education_level_id') ?: null;
        $teacherId = $request->integer('teacher_id') ?: null;

        $query = Subject::query()
            ->active()
            ->with(['category:id,name', 'educationLevel:id,name,slug'])
            ->ordered();

        if ($levelId) {
            $query->where('education_level_id', $levelId);
        }

        if ($teacherId) {
            $query->whereHas('teachers', fn ($q) => $q->where('users.id', $teacherId));
        }

        $grouped = $query->get()
            ->groupBy(fn (Subject $s) => $s->educationLevel?->name ?? 'Lainnya')
            ->map(fn ($items, $levelName) => [
                'level' => $levelName,
                'subjects' => $items->map(fn (Subject $subject) => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'category_name' => $subject->category?->name,
                ])->values(),
            ])
            ->values();

        return response()->json($grouped);
    }
}
