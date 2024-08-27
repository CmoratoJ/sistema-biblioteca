<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interface\ILoanRepository;
use App\Models\Book;
use App\Models\Loan;
use App\Notifications\UserNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class LoanRepository implements ILoanRepository
{

    public function persist(array $data): Loan
    {
        Cache::forget('all_loans');
        return Loan::create([
            'user_id' => auth()->user()->id,
            'book_id' => $data['book_id'],
            'loan_date' => $data['loan_date'],
            'due_date' => $data['due_date'],
        ]);
    }

    public function update(array $data, int $id): Loan
    {
        Cache::forget('all_loans');
        $loan = Loan::findOrFail($id);
        $loan->update([
            'return_date' => $data['return_date'],
        ]);
        return $loan;
    }

    public function findAll(): Collection
    {
        return  Cache::remember('all_loans', 3600, fn () => Loan::with(['user', 'book'])->whereNull('return_date')->get());
    }

    public function delete(int $id): void
    {
        Cache::forget('all_loans');
        $loan = Loan::findOrFail($id);
        $loan->delete();
    }

    public function isBookLoaned(int $bookId): bool
    {
        if (Cache::has('all_loans')) {
            return Cache::get('all_loans')->where('book_id', $bookId)->whereNull('return_date')->exists();
        }

        return Loan::where('book_id', $bookId)->whereNull('return_date')->exists();
    }

    public function sendNotification(Loan $loan, Book $book): void
    {
        Notification::send(auth()->user(), new UserNotification($loan, $book));
    }
}
