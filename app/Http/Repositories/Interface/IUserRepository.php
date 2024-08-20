<?php

namespace App\Http\Repositories\Interface;

use App\Models\User;
use Illuminate\Support\Collection;

interface IUserRepository
{
    public function persist(User $user): User;
    public function findById(int $id): User;
    public function findAll(): Collection;
    public function delete(User $user): void;
}
