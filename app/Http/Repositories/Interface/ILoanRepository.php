<?php

namespace App\Http\Repositories\Interface;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Collection;

interface ILoanRepository
{
    public function persist(array $data): Loan;

    public function update(array $data, int $id): Loan;
    public function findAll(): Collection;

    public function delete(int $id): void;

    public function isBookLoaned(int $bookId): bool;

    public function sendNotification(Loan $loan, Book $book): void;
}
