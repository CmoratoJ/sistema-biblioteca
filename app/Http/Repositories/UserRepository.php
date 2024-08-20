<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IUserRepository;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository implements IUserRepository
{

    public function persist(User $user): User
    {
        $user->save();
        return $user;
    }

    public function findById(int $id): User
    {
        return User::find($id);
    }

    public function findAll(): Collection
    {
        return User::all();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
