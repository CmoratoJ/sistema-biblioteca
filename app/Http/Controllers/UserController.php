<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *   tags={"Users"},
     *   path="/users",
     *   summary="Busca todos os usuários",
     *   description="Buscar todos os usuários cadastrados",
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
        return $this->userService->findAll();
    }

    /**
     * @OA\Get(
     *   tags={"Users"},
     *   path="/users/{id}",
     *   summary="Busca usuário pelo id",
     *   description="Buscar usuário cadastrado pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do usuário",
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
        return $this->userService->findById($id);
    }

    /**
     * @OA\Post(
     *   tags={"Users"},
     *   path="/users",
     *   summary="Cadastra um novo usuário",
     *   description="Cadastrar usuário",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string"),
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
    public function store(CreateOrUpdateUserRequest $request): JsonResponse
    {
        return $this->userService->create($request->toArray());
    }

    /**
     * @OA\Put(
     *   tags={"Users"},
     *   path="/users/{id}",
     *   summary="Altera usuário pelo id",
     *   description="Alterar usuário pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do usuário",
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
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string"),
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
    public function update(CreateOrUpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->userService->update($request->toArray(), $id);
    }

    /**
     * @OA\Delete(
     *   tags={"Users"},
     *   path="/users/{id}",
     *   summary="Deleta usuário pelo id",
     *   description="Deletar usuário pelo id",
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     description="Id do usuário",
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
        return $this->userService->delete($id);
    }
}
