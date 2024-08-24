<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IUserRepository;
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

    public function create(array $data): User
    {
        return $this->userRepository->persist($data);
    }

    public function findAll(): object
    {
        return $this->userRepository->findAll();
    }

    public function findById(int $id): object
    {
        return $this->userRepository->findById($id);
    }

    public function update(array $data, int $id): User
    {
        return $this->userRepository->persist($data, $id);
    }

    public function delete(int $id): void
    {
        $user = $this->userRepository->findById($id);
        $this->userRepository->delete($user);
    }
}
