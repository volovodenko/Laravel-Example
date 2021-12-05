<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\File;

use App\Enums\FileStorage;
use Illuminate\Http\UploadedFile;

class UploadFileDto
{
    public function __construct(
        private UploadedFile $uploadedFile,
        private string $directory,
        private ?FileStorage $storage = null,
    ) {
        if (!$this->storage) {
            $this->storage = FileStorage::LOCAL();
        }
    }

    public function uploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    public function storage(): FileStorage
    {
        return $this->storage;
    }

    public function directory(): string
    {
        return $this->directory;
    }

    public function fileName(): string
    {
        return unique_filename('', $this->uploadedFile->getClientOriginalExtension());
    }

    public function originalName(): string
    {
        return $this->uploadedFile->getClientOriginalName();
    }

    public function mimeType(): string
    {
        return $this->uploadedFile->getMimeType();
    }

    public function size(): int
    {
        return $this->uploadedFile->getSize();
    }

    public function fields(): array
    {
        return [
            'file_name'     => $this->fileName(),
            'original_name' => $this->originalName(),
            'storage'       => $this->storage(),
            'directory'     => $this->directory(),
            'mime_type'     => $this->mimeType(),
            'size'          => $this->size(),
        ];
    }
}
