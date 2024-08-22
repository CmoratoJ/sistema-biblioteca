<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IBookRepository;
use App\Http\Requests\CreateOrUpdateBookRequest;
use App\Models\Book;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BookRepository implements IBookRepository
{

    public function persist(CreateOrUpdateBookRequest $request, int $id = null): Book
    {
        Cache::forget('all_books');

        if (is_null($id)) {
            $book = Book::create($request->only('title', 'publication_year'));
            $book->authors()->attach($request->input('authors'));
            return $book;
        }

        $book = $this->findById($id);
        $book->update($request->only('title', 'publication_year'));
        $book->authors()->sync($request->input('authors'));
        return $book;
    }

    public function findById(int $id): Book
    {
        return Book::with('authors')->find($id);
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
