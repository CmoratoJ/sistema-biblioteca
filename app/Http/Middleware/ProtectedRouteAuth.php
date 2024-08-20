<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProtectedRouteAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            return ApiResponse::error('Token is Invalid', 401);
        } catch (Exception $e) {
            return ApiResponse::error('Authorization Token not found', 401);
        }
        return $next($request);
    }
}
