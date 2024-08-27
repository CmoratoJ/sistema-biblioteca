<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Models\Author;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AuthorRepository implements IAuthorRepository
{

    public function persist(array $data, int $id = null): Author
    {
        Cache::forget('all_authors');

        $author = $id ? Author::findOrFail($id) : new Author();
        $author->fill($data);
        $author->save();
        return $author;
    }

    public function findById(int $id): ?Author
    {
        if (Cache::has('all_authors')) {
            return Cache::get('all_authors')->firstWhere('id', $id);
        }

        return Author::with('books')->findOrFail($id);
    }

    public function findAll(): Collection
    {
        return Cache::remember('all_authors', 3600, fn () => Author::with('books')->get());
    }

    public function delete(Author $author): void
    {
        Cache::forget('all_authors');
        Cache::forget('all_books');
        $author->delete();
    }
}
