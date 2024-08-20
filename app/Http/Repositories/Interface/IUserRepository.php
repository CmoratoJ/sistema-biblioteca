<?php

namespace App\Http\Repositories\Interface;

use App\Models\User;

interface IUserRepository
{
    public function persist(User $user): User;
    public function findById(int $id): object;
    public function findAll(): object;
    public function delete(User $user): void;
}
