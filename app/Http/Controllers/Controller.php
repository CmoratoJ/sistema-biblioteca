<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Server(url="http://localhost:8000/api"),
 * @OA\Info(title="Bamaq Api", version="0.0.1"),
 * @OA\SecurityScheme(
 *   type="http",
 *   scheme="bearer",
 *   securityScheme="bearerAuth",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
