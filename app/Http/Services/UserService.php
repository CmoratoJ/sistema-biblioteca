<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UserService
{
    private IUserRepository $userRepository;
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(CreateOrUpdateUserRequest $request): JsonResponse
    {
        try {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));

            $this->userRepository->persist($user);

            return ApiResponse::success(
                new UserResource($user),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }

    }

    public function findAll(): object
    {
        try {
            $users = $this->userRepository->findAll();
            return ApiResponse::success(
                UserResource::collection($users),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function findById(int $id): object
    {
        try {
            $user = $this->userRepository->findById($id);
            return  ApiResponse::success(
                new UserResource($user),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }

    }

    public function update(CreateOrUpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->findById($id);

            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));

            $this->userRepository->persist($user);

            return ApiResponse::success(
                new UserResource($user),
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->findById($id);
            $this->userRepository->delete($user);
            return ApiResponse::success(
                null,
                'success',
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
