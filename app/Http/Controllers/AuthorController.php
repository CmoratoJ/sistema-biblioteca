<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateAuthorRequest;
use App\Http\Services\AuthorService;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    private AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @OA\Get(
     *   tags={"Authors"},
     *   path="/authors",
     *   summary="Busca todos os autores",
     *   description="Buscar todos os autores cadastrados",
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
        return $this->authorService->findAll();
    }

    /**
     * @OA\Get(
     *   tags={"Authors"},
     *   path="/authors/{id}",
     *   summary="Busca um autor pelo ID",
     *   description="Buscar um autor pelo ID",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID do autor",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *    ),
     *    @OA\Response(
     *      response=500,
     *      description="Internal Server Error",
     *    )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return $this->authorService->findById($id);
    }

    /**
     * @OA\Post(
     *   tags={"Authors"},
     *   path="/authors",
     *   summary="Cria um novo autor",
     *   description="Cria um novo autor",
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="name", type="string"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
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
    public function store(CreateOrUpdateAuthorRequest $request): JsonResponse
    {
        return $this->authorService->create($request->toArray());
    }

    /**
     * @OA\Put(
     *   tags={"Authors"},
     *   path="/authors/{id}",
     *   summary="Altera um autor pelo ID",
     *   description="Altera um autor pelo ID",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID do autor",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="name", type="string"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
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
    public function update(CreateOrUpdateAuthorRequest $request, int $id): JsonResponse
    {
        return $this->authorService->update($request->toArray(), $id);
    }

    /**
     * @OA\Delete(
     *   tags={"Authors"},
     *   path="/authors/{id}",
     *   summary="Deleta um autor pelo ID",
     *   description="Deleta um autor pelo ID",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID do autor",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=204,
     *     description="No Content",
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
    public function destroy(int $id): JsonResponse
    {
        return $this->authorService->delete($id);
    }
}
