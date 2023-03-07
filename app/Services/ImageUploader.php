<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class ImageUploader
{
    const CDN_PATH = 'https://image-restoration.fra1.cdn.digitaloceanspaces.com/';

    function uploadAndGetUrl(UploadedFile $file, $directory = 'uploads')
    {
        $file = Storage::putFile($directory, $file, 'public');

        if (!$file) {
            throw ValidationException::withMessages([
                'file' => 'File could not be uploaded'
            ]);
        }

        return self::CDN_PATH . $file;
    }

    public function fetchAndUpload($url, string $directory)
    {
        $path = sprintf('%s/%s.png', $directory, Str::uuid());

        $file = Storage::put($path, file_get_contents($url), 'public');

        if (!$file) {
            throw new InvalidArgumentException("File could not be uploaded");
        }

        return self::CDN_PATH . $path;
    }
}
