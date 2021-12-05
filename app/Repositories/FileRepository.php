<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Contracts\File as FileContract;
use App\Models\File;
use App\Repositories\Dto\File\UploadFileDto;
use App\Services\FileManager;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileRepository extends BaseRepository
{
    public function __construct(private FileManager $fileManager)
    {
        parent::__construct();
    }

    public function upload(UploadFileDto $dto): FileContract
    {
        return \DB::transaction(function () use ($dto) {
            $file = $this->newModel();
            $file->fill($dto->fields());
            $file->save();

            $this->fileManager->put($file, $dto->uploadedFile()->get());

            return $file;
        });
    }

    public function download(File $file): StreamedResponse
    {
        return $this->fileManager->download($file, $file->original_name);
    }

    public function newModel(): File
    {
        return new File();
    }
}
