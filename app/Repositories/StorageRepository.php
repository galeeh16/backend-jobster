<?php declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use App\Contracts\StorageContract;
use Illuminate\Support\Facades\Storage;

final class StorageRepository implements StorageContract
{
    public function upload(UploadedFile $file, string $dir, string $name, string $disk='public'): string|false
    {
        return Storage::disk($disk)->putFileAs($dir, $file, $name);
    }

    public function delete(string $path_to_file, string $disk='public'): bool
    {
        return Storage::disk($disk)->delete($path_to_file);
    }
}