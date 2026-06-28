<?php

namespace App\Support;

final class ProgressMorphType
{
    public const VIDEO = 'App\Models\CourseVideo';

    public const MATERIAL = 'App\Models\CourseMaterial';

    public static function forItemType(string $itemType): string
    {
        return $itemType === 'video' ? self::VIDEO : self::MATERIAL;
    }
}
