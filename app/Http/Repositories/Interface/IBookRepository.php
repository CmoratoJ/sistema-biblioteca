<?php

namespace App\Http\Repositories\Interface;

use App\Http\Requests\CreateOrUpdateBookRequest;
use App\Models\Book;
use Illuminate\Support\Collection;

interface IBookRepository
{
    public function persist(CreateOrUpdateBookRequest $request, int $id = null): Book;
    public function findById(int $id): Book;
    public function findAll(): Collection;
    public function delete(Book $book): void;
}
