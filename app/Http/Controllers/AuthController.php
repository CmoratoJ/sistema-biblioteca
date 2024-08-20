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

    public function store(Request $request): JsonResponse|array
    {
        $credentials = $request->only('email', 'password');
        return $this->authService->login($credentials);
    }
}
