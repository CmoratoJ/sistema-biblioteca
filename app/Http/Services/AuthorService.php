<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Http\Resources\AuthorResource;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AuthorService
{
    private IAuthorRepository $authorRepository;

    public function __construct(IAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function findAll(): JsonResponse
    {
        try {
            $authors = $this->authorRepository->findAll();
            return ApiResponse::success(
                AuthorResource::collection($authors),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function findById(int $id): JsonResponse
    {
        try {
            $author = $this->authorRepository->findById($id);
            return ApiResponse::success(
                new AuthorResource($author),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $author = $this->authorRepository->findById($id);
            $this->authorRepository->delete($author);
            return ApiResponse::success(
                null,
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function create(array $data): JsonResponse
    {
        try {
            $author = $this->authorRepository->persist($data);
            return ApiResponse::success(
                new AuthorResource($author),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function update(array $data, int $id): JsonResponse
    {
        try {
            $author = $this->authorRepository->persist($data, $id);
            return ApiResponse::success(
                new AuthorResource($author),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
