<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserService
{
    private IUserRepository $userRepository;
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(CreateOrUpdateUserRequest $request): UserResource
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        $this->userRepository->persist($user);

        return new UserResource($user);
    }

    public function findAll(): object
    {
        $users = $this->userRepository->findAll();
        return UserResource::collection($users);
    }

    public function findById(int $id): object
    {
        $user = $this->userRepository->findById($id);
        return new UserResource($user);
    }

    public function update(CreateOrUpdateUserRequest $request, int $id): UserResource
    {
        $user = $this->userRepository->findById($id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        $this->userRepository->persist($user);

        return new UserResource($user);
    }

    public function delete(int $id): void
    {
        $user = $this->userRepository->findById($id);
        $this->userRepository->delete($user);
    }
}
