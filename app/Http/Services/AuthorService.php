<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Models\Author;
use Illuminate\Support\Collection;

class AuthorService
{
    private IAuthorRepository $authorRepository;

    public function __construct(IAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function findAll(): Collection
    {
        return $this->authorRepository->findAll();
    }

    public function findById(int $id): Author
    {
        return $this->authorRepository->findById($id);
    }

    public function create(array $data): Author
    {
        return $this->authorRepository->persist($data);
    }

    public function update(array $data, int $id): Author
    {
        return $this->authorRepository->persist($data, $id);
    }

    public function delete(int $id): void
    {
        $author = $this->authorRepository->findById($id);
        $this->authorRepository->delete($author);
    }
}
