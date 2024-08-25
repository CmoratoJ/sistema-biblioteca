<?php

namespace App\Http\Services;

use App\Http\Repositories\Interface\ILoanRepository;
use App\Models\Loan;
use Exception;
use Illuminate\Support\Collection;

class LoanService
{
    private ILoanRepository $loanRepository;
    public function __construct(ILoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function findAll(): Collection
    {
        return $this->loanRepository->findAll();
    }

    public function create(array $data): Loan
    {
        if ($this->loanRepository->isBookLoaned($data['book_id'])) {
            throw new Exception('Book already loaned', 400);
        }

        return $this->loanRepository->persist($data);
    }

    public function update(array $data, int $id): Loan
    {
        return $this->loanRepository->update($data, $id);
    }

    public function delete(int $id): void
    {
        $this->loanRepository->delete($id);
    }
}
