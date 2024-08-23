<?php

namespace App\Http\Repositories\Interface;

use App\Models\Book;
use Illuminate\Support\Collection;

interface IBookRepository
{
    public function persist(array $data, array $authors): Book;
    public function update(array $data, array $authors, int $id): Book;
    public function findById(int $id): Book;
    public function findAll(): Collection;
    public function delete(Book $book): void;
}
