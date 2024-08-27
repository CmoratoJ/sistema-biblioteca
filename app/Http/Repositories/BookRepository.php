<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IBookRepository;
use App\Models\Book;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BookRepository implements IBookRepository
{

    public function persist(array $data, array $authors): Book
    {
        Cache::forget('all_books');
        $book = Book::create($data);
        $book->authors()->attach($authors);
        return $book;
    }

    public function update(array $data, array $authors, int $id): Book
    {
        Cache::forget('all_books');
        $book = $this->findById($id);
        $book->update($data);
        $book->authors()->sync($authors);
        return $book;
    }

    public function findById(int $id): Book
    {
        return Book::with('authors')->findOrFail($id);
    }

    public function findAll(): Collection
    {
        return Cache::remember('all_books', 3600, fn () => Book::with('authors')->get());
    }

    public function delete(Book $book): void
    {
        Cache::forget('all_books');
        $book->delete();
    }
}
