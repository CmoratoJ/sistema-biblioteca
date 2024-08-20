<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{

    public function persist(User $user): User
    {
        $user->save();
        return $user;
    }

    public function findById(int $id): object
    {
        return User::find($id);
    }

    public function findAll(): object
    {
        return User::all();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
