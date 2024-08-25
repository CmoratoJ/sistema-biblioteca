<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\LoanService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    private LoanService $loanService;
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * @OA\Get(
     *   tags={"Loans"},
     *   path="/loans",
     *   summary="Busca todos os empréstimos",
     *   description="Buscar todos os empréstimos ativos",
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $loans = $this->loanService->findAll();
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

    /**
     * @OA\Post(
     *   tags={"Loans"},
     *   path="/loans",
     *   summary="Cadastra um novo empréstimo",
     *   description="Cadastrar empréstimo",
     *     security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="book_id", type="integer"),
     *       @OA\Property(property="loan_date", type="date"),
     *       @OA\Property(property="due_date", type="date"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Unprocessable Content",
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Book already loaned",
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *   )
     * )
     */
    public function store(CreateLoanRequest $request): JsonResponse
    {
        try {
            $loan = $this->loanService->create($request->toArray());
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

    /**
     * @OA\Put(
     *   tags={"Loans"},
     *   path="/loans/{id}",
     *   summary="Altera empréstimo pelo id",
     *   description="Alterar empréstimo pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do empréstimo",
     *     in="path",
     *     name="id",
     *     required=true,
     *     @OA\Schema(
     *       type="integer",
     *       format="int"
     *     )
     *    ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="return_date", type="date"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Unprocessable Content",
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *   )
     * )
     */
    public function update(UpdateLoanRequest $request, int $id): JsonResponse
    {
        try {
            $loan = $this->loanService->update($request->toArray(), $id);
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

    /**
     * @OA\Delete(
     *   tags={"Loans"},
     *   path="/loans/{id}",
     *   summary="Deleta empréstimo",
     *   description="Deletar empréstimo",
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(
     *     response=204,
     *     description="No Content",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found",
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *   )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->loanService->delete($id);
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
