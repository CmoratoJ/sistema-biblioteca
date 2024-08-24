<?php

namespace App\Http\Services;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthService
{
    public function login (array $credentials): JsonResponse|array
    {
        if (!$token = auth()->setTTL(6 * 60)->attempt($credentials)) {
            throw new Exception('Unauthorized.', 401);
        }

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => auth()->user(),
        ];
    }
}
