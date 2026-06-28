<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesCourseOwnership;
use App\Http\Controllers\Concerns\AuthorizesStudentCourseAccess;
use App\Models\CourseMaterial;
use App\Models\CourseVideo;
use App\Models\UserProgress;
use App\Support\ProgressMorphType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseProgressController extends Controller
{
    use AuthorizesStudentCourseAccess;

    public function toggleProgress(Request $request)
    {
        $request->validate([
            'item_id'   => 'required|integer',
            'item_type' => 'required|in:video,material',
            'status'    => 'required|boolean',
        ]);

        $userId = Auth::id();
        $itemId = (int) $request->item_id;
        $itemType = ProgressMorphType::forItemType($request->item_type);

        $courseId = $this->resolveCourseIdForProgressItem($request->item_type, $itemId);
        $this->assertStudentEnrolledInCourse($courseId);

        try {
            if ($request->status) {
                UserProgress::updateOrCreate(
                    [
                        'user_id'           => $userId,
                        'progressable_id'   => $itemId,
                        'progressable_type' => $itemType,
                    ]
                );
                $message = 'Materi berhasil ditandai sebagai selesai.';
            } else {
                UserProgress::where('user_id', $userId)
                    ->where('progressable_id', $itemId)
                    ->where('progressable_type', $itemType)
                    ->delete();
                $message = 'Tanda selesai pada materi berhasil dihapus.';
            }

            return response()->json([
                'success'         => true,
                'message'         => $message,
                'class_completed' => false,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui progress karena kendala sistem.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function resolveCourseIdForProgressItem(string $itemType, int $itemId): int
    {
        if ($itemType === 'video') {
            $courseId = CourseVideo::whereKey($itemId)->value('course_id');
        } else {
            $courseId = CourseMaterial::whereKey($itemId)->value('course_id');
        }

        if (!$courseId) {
            abort(404, 'Konten pembelajaran tidak ditemukan.');
        }

        return (int) $courseId;
    }
}
