<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IBookRepository;
use App\Http\Requests\CreateOrUpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class BookService
{
    private  IBookRepository $bookRepository;
    public function __construct(IBookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function findAll(): JsonResponse
    {
        try {
            $books = $this->bookRepository->findAll();
            return ApiResponse::success(
                BookResource::collection($books),
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
            $book = $this->bookRepository->findById($id);
            return ApiResponse::success(
                new BookResource($book),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function create(CreateOrUpdateBookRequest $request): JsonResponse
    {
        try {
            $book = $this->bookRepository->persist($request);
            return ApiResponse::success(
                new BookResource($book),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function update(CreateOrUpdateBookRequest $request, int $id): JsonResponse
    {
        try {
            $book = $this->bookRepository->persist($request, $id);
            return ApiResponse::success(
                new BookResource($book),
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
            $book = $this->bookRepository->findById($id);
            $this->bookRepository->delete($book);
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
}
