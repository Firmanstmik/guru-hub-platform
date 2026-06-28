<?php

namespace App\Support;

class MediaDefaults
{
  public const COVER_TYPES = [
    'course',
    'material',
    'video',
    'category',
    'certificate',
  ];

  public static function coverUrl(?string $path, string $type = 'course'): string
  {
    if (filled($path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
      return asset('storage/' . $path);
    }

    $type = in_array($type, self::COVER_TYPES, true) ? $type : 'course';

    return asset('assets/covers/default-' . $type . '.png');
  }
}
