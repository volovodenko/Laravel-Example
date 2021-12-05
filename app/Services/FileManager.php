<?php

namespace App\Services;

use App\Models\Contracts\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileManager
{
    public function get(File $file): string
    {
        return \Storage::disk($file->storage())->get($file->path());
    }

    public function put(File $file, string $content): bool
    {
        return \Storage::disk($file->storage())->put($file->path(), $content);
    }

    public function download(File $file, ?string $name = null, bool $inline = true, array $headers = []): StreamedResponse
    {
        $disposition = $inline ? 'inline' : 'attachment';

        return \Storage::disk($file->storage())
            ->response(
                $file->path(),
                $name ?? $file->fileName(),
                $headers,
                $disposition
            );
    }

    public function delete(File $file): bool
    {
        return \Storage::disk($file->storage())->delete($file->path());
    }

    public function getSize(File $file): int
    {
        return \Storage::disk($file->storage())->size($file->path());
    }
}
