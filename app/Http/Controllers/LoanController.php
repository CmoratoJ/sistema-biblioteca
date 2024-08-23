<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Services\LoanService;
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
        return $this->loanService->findAll();
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
        return $this->loanService->persist($request->toArray());
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
        return $this->loanService->update($request->toArray(), $id);
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
        return $this->loanService->delete($id);
    }
}
