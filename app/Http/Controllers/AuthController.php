<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/login",
     *   summary="Realiza autenticação",
     *   description="Realizar autenticação",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *   )
     * )
     */
    public function store(Request $request): JsonResponse|array
    {
        return $this->authService->login($request->only('email', 'password'));
    }
}
