<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
     /**
     * Store an uploaded file in the given folder under the public disk.
     */
    public static function store(UploadedFile $file, string $folder = 'images'): string
    {
        return $file->store($folder, 'public');
    }

     /**
     * Replace an existing image with a new one.
     * Deletes the old file first to avoid orphaned files in storage.
     */
    public static function update(UploadedFile $file, ?string $oldPath, string $folder = 'images'): string
    {
        self::delete($oldPath);

        return $file->store($folder, 'public');
    }

     /**
     * Delete an image from storage if it exists.
     * Null-safe — does nothing if path is null or file is missing.
     */
    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}