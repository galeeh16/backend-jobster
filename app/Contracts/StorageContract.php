<?php declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageContract 
{
    public function upload(UploadedFile $file, string $dir, string $name, string $disk='public'): string|false;

    public function delete(string $path_to_file, string $disk='public'): bool;
}
