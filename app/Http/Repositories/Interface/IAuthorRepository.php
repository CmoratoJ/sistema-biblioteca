<?php

namespace App\Http\Repositories\Interface;

use App\Models\Author;
use Illuminate\Support\Collection;

interface IAuthorRepository
{
    public function persist(array $data, int $id = null): Author;
    public function findById(int $id): ?Author;
    public function findAll(): Collection;
    public function delete(Author $author): void;
}
