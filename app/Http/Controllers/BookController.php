<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateBookRequest;
use App\Http\Services\BookService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @OA\Get(
     *   tags={"Books"},
     *   path="/books",
     *   summary="Busca todos os livros",
     *   description="Buscar todos os livros cadastrados",
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
        return $this->bookService->findAll();
    }

    /**
     * @OA\Get(
     *   tags={"Books"},
     *   path="/books/{id}",
     *   summary="Busca livro pelo id",
     *   description="Buscar livro cadastrado pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do livro",
     *     in="path",
     *     name="id",
     *     required=true,
     *     @OA\Schema(
     *       type="integer",
     *       format="int"
     *     )
     *   ),
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
    public function show(int $id): JsonResponse
    {
        return $this->bookService->findById($id);
    }

    /**
     * @OA\Post(
     *   tags={"Books"},
     *   path="/books",
     *   summary="Cadastra um novo livro",
     *   description="Cadastrar livro",
     *     security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="publication_year", type="integer"),
     *       @OA\Property(property="authors", type="array",
     *          @OA\Items(
     *              @OA\Property(property="author_id",type="integer")
     *          )
     *       ),
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
    public function store(CreateOrUpdateBookRequest $request): JsonResponse
    {
        $data = $request->only('title', 'publication_year');
        $authors = $request->input('authors');
        return $this->bookService->create($data, $authors);
    }

    /**
     * @OA\Put(
     *   tags={"Books"},
     *   path="/books/{id}",
     *   summary="Altera livro pelo id",
     *   description="Alterar livro pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do livro",
     *     in="path",
     *     name="id",
     *     required=true,
     *     @OA\Schema(
     *       type="integer",
     *       format="int"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="publication_year", type="integer"),
     *       @OA\Property(property="authors", type="array",
     *           @OA\Items(
     *               @OA\Property(property="author_id",type="integer")
     *           )
     *       ),
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
    public function update(CreateOrUpdateBookRequest $request, int $id): JsonResponse
    {
        $data = $request->only('title', 'publication_year');
        $authors = $request->input('authors');
        return $this->bookService->update($data, $authors, $id);
    }

    /**
     * @OA\Delete(
     *   tags={"Books"},
     *   path="/books/{id}",
     *   summary="Deleta livro pelo id",
     *   description="Deletar livro pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do livro",
     *     in="path",
     *     name="id",
     *     required=true,
     *     @OA\Schema(
     *       type="integer",
     *       format="int"
     *     )
     *   ),
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
    public function destroy(int $id): JsonResponse
    {
        return $this->bookService->delete($id);
    }
}
