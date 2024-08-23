<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\ILoanRepository;
use App\Http\Resources\LoanResource;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class LoanService
{
    private ILoanRepository $loanRepository;
    public function __construct(ILoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function findAll(): JsonResponse
    {
        try {
            $loans = $this->loanRepository->findAll();
            return ApiResponse::success(
                LoanResource::collection($loans),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function persist(array $data): JsonResponse
    {
        try {
            if ($this->loanRepository->isBookLoaned($data['book_id'])) {
                return ApiResponse::error('Book already loaned', 400);
            }
            $loan = $this->loanRepository->persist($data);
            return ApiResponse::success(
                new LoanResource($loan),
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
            $loan = $this->loanRepository->update($data, $id);
            return ApiResponse::success(
                new LoanResource($loan),
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
            $this->loanRepository->delete($id);
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
