<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Requests\CreateOrUpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository implements IUserRepository
{

    public function persist(CreateOrUpdateUserRequest $request, int $id = null): User
    {
        Cache::forget('all_users');

        $user = new User();

        if ($id) {
            $user = $this->findById($id);
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        $user->save();

        return $user;
    }

    public function findById(int $id): User
    {
        return User::find($id);
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
