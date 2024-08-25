<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IBookRepository;
use App\Models\Book;
use Illuminate\Support\Collection;

class BookService
{
    private IBookRepository $bookRepository;
    public function __construct(IBookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function findAll(): Collection
    {
        return $this->bookRepository->findAll();
    }

    public function findById(int $id): Book
    {
        return $this->bookRepository->findById($id);
    }

    public function create(array $data, array $authors): Book
    {
        return $this->bookRepository->persist($data, $authors);
    }

    public function update(array $data, array $authors, int $id): Book
    {
        return $this->bookRepository->update($data, $authors, $id);
    }

    public function delete(int $id): void
    {
        $book = $this->bookRepository->findById($id);
        $this->bookRepository->delete($book);
    }
}
