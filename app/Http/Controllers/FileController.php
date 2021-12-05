<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Repositories\Criteria\Common\WhereCriteria;
use App\Repositories\Criteria\CriteriaBuilder;
use App\Repositories\Dto\File\UploadFileDto;
use App\Repositories\FileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController
{
    public function __construct(
        private FileRepository $fileRepository
    ) {
    }

    public function store(FileUploadRequest $request): JsonResponse
    {
        $user  = auth()->user();
        $today = now()->toDateString();

        $responseFiles = array_map(
            fn (UploadedFile $file) => $this->fileRepository->upload(
                new UploadFileDto(
                    uploadedFile: $file,
                    directory: "users/{$user->id}/files/{$today}/",
                )
            ),
            $request->file('files')
        );

        return response()->json(FileResource::collection($responseFiles), 201);
    }

    public function getByToken(string $id): StreamedResponse
    {
        $criteriaBuilder = new CriteriaBuilder();
        $criteriaBuilder->add(new WhereCriteria('id', $id));

        $file  = $this->fileRepository->findOrFail($criteriaBuilder);
        $token = request()->query('token');

        if (!\JWT::isValidToken($token, $file)) {
            abort(403);
        }

        return $this->fileRepository->download($file);
    }
}
