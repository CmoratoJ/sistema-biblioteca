<?php

namespace Http\Repositories;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Http\Repositories\Interface\IBookRepository;
use App\Http\Repositories\Interface\ILoanRepository;
use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Services\AuthService;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LoanRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private ILoanRepository $repository;
    private Book $book;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(ILoanRepository::class);
        $bookRepository = $this->app->make(IBookRepository::class);
        $userRepository = $this->app->make(IUserRepository::class);
        $authorRepository = $this->app->make(IAuthorRepository::class);
        $authService = new AuthService();

        $dataUser = [
            'name' => 'User 1',
            'email' => '5kLpM@example.com',
            'password' => bcrypt('12345678')
        ];

        $dataBook = ['title' => 'Book 1', 'publication_year' => 2020];

        $author = $authorRepository->persist(['name' => 'author']);

        $authors = ['author_id' => $author->id];

        $this->book = $bookRepository->persist($dataBook, $authors);

        $this->user = $userRepository->persist($dataUser);

        $authService->login([
            'email' => '5kLpM@example.com',
            'password' => '12345678'
        ]);
    }

    public function testPersistLoan()
    {
        $dataLoan = ['book_id' => $this->book->id, 'user_id' => $this->user->id, 'loan_date' => '2022-01-01', 'due_date' => '2022-01-02'];
        $loan = $this->repository->persist($dataLoan);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertDatabaseHas('loans', ['book_id' => 1, 'user_id' => 1]);
    }

    public function testUpdateLoan()
    {
        $dataLoan = ['book_id' => $this->book->id, 'user_id' => $this->user->id, 'loan_date' => '2022-01-01', 'due_date' => '2022-01-02'];

        $loan = $this->repository->persist($dataLoan);

        $dataLoan = ['return_date' => '2022-01-03'];

        $loan = $this->repository->update($dataLoan, $loan->id);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertDatabaseHas('loans', ['return_date' => '2022-01-03']);
    }

    public function testFindAllLoans()
    {
        $this->repository->persist(['book_id' => $this->book->id, 'user_id' => $this->user->id, 'loan_date' => '2022-01-01', 'due_date' => '2022-01-02']);

        $loans = $this->repository->findAll();

        $this->assertInstanceOf(Collection::class, $loans);
        $this->assertCount(1, $loans);
    }

    public function testDeleteLoan()
    {
        $dataLoan = ['book_id' => $this->book->id, 'user_id' => $this->user->id, 'loan_date' => '2022-01-01', 'due_date' => '2022-01-02'];
        $loan = $this->repository->persist($dataLoan);
        $this->repository->delete($loan->id);
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }

    public function testIsBookLoaned()
    {
        $dataLoan = ['book_id' => $this->book->id, 'user_id' => $this->user->id, 'loan_date' => '2022-01-01', 'due_date' => '2022-01-02'];
        $this->repository->persist($dataLoan);
        $isBookLoanedTrue = $this->repository->isBookLoaned($this->book->id);
        $isBookLoanedFalse = $this->repository->isBookLoaned($this->book->id + 1);
        $this->assertTrue($isBookLoanedTrue);
        $this->assertFalse($isBookLoanedFalse);
    }
}
