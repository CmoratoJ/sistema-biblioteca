<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\UserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        return $this->userService->findAll();
    }

    public function show(int $id): JsonResponse
    {
        return $this->userService->findById($id);
    }

    public function store(CreateOrUpdateUserRequest $request): JsonResponse
    {
        return $this->userService->create($request);
    }

    public function update(CreateOrUpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->userService->update($request, $id);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->userService->delete($id);
    }
}
