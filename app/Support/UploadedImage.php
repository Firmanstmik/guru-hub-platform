<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadedImage
{
    public const MAX_KB = 5120;

    public const ALLOWED_MIMES = ['jpeg', 'png', 'jpg', 'webp'];

    /**
     * Detect PHP-level upload failures before Laravel validation runs.
     * Returns an Indonesian error message, or null when upload is OK / absent.
     */
    public static function failedUploadMessage(Request $request, string $field): ?string
    {
        if (! array_key_exists($field, $request->allFiles())) {
            return null;
        }

        $file = $request->file($field);

        if ($file instanceof UploadedFile && $file->isValid()) {
            return null;
        }

        $error = self::uploadErrorCode($field);

        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran gambar terlalu besar. Maksimal 5 MB. Gunakan JPG, PNG, atau WebP yang dikompres.',
            UPLOAD_ERR_PARTIAL => 'Upload gambar terputus. Silakan coba lagi.',
            UPLOAD_ERR_NO_FILE => null,
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE, UPLOAD_ERR_EXTENSION => 'Server tidak dapat menerima file saat ini. Hubungi administrator.',
            default => 'Gagal mengunggah gambar. Pastikan format JPG, PNG, atau WebP dan ukuran maksimal 5 MB.',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function validationMessages(string $attributeLabel = 'gambar sampul'): array
    {
        return [
            'cover_image.image' => "Berkas {$attributeLabel} harus berupa gambar.",
            'cover_image.mimes' => 'Format gambar tidak didukung. Gunakan JPG, PNG, atau WebP.',
            'cover_image.max' => 'Ukuran gambar terlalu besar. Maksimal 5 MB.',
            'cover_image.uploaded' => 'Gagal mengunggah gambar. Pastikan format JPG, PNG, atau WebP dan ukuran maksimal 5 MB.',
        ];
    }

    public static function storeOnPublicDisk(UploadedFile $file, string $directory): string
    {
        Storage::disk('public')->makeDirectory($directory);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        if (! in_array($extension, self::ALLOWED_MIMES, true)) {
            $extension = 'jpg';
        }

        $filename = Str::uuid()->toString().'.'.$extension;

        return $file->storeAs($directory, $filename, 'public');
    }

    private static function uploadErrorCode(string $field): int
    {
        $files = $_FILES ?? [];

        if (! isset($files[$field]['error'])) {
            return UPLOAD_ERR_OK;
        }

        $error = $files[$field]['error'];

        return is_array($error) ? (int) ($error[0] ?? UPLOAD_ERR_NO_FILE) : (int) $error;
    }
}
