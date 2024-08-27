<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IUserRepository;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository implements IUserRepository
{

    public function persist(array $data, int $id = null): User
    {
        Cache::forget('all_users');
        $user = $id ? $this->findById($id) : new User();
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function findById(int $id): User
    {
        if (Cache::has('all_users')) {
            return Cache::get('all_users')->firstWhere('id', $id);
        }

        return User::findOrFail($id);
    }

    public function findAll(): Collection
    {
        return Cache::remember('all_users', 3600, fn () => User::all());
    }

    public function delete(User $user): void
    {
        Cache::forget('all_users');
        $user->delete();
    }
}
