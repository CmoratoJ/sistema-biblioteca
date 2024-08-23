<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\ILoanRepository;
use App\Models\Loan;
use Illuminate\Support\Collection;

class LoanRepository implements ILoanRepository
{

    public function persist(array $data): Loan
    {
        return Loan::create([
            'user_id' => auth()->user()->id,
            'book_id' => $data['book_id'],
            'loan_date' => $data['loan_date'],
            'due_date' => $data['due_date'],
        ]);
    }

    public function update(array $data, int $id): Loan
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'return_date' => $data['return_date'],
        ]);
        return $loan;
    }

    public function findAll(): Collection
    {
        return Loan::with(['user', 'book'])->whereNull('return_date')->get();
    }

    public function delete(int $id): void
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
    }

    public function isBookLoaned(int $bookId): bool
    {
        return Loan::where('book_id', $bookId)->whereNull('return_date')->exists();
    }
}
