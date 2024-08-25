<?php

namespace Http\Services;

use App\Http\Repositories\Interface\ILoanRepository;
use App\Http\Services\LoanService;
use App\Models\Loan;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    private ILoanRepository $loanRepository;
    private LoanService $loanService;
    protected function setUp(): void
    {
        $this->loanRepository = $this->createMock(ILoanRepository::class);
        $this->loanService = new LoanService($this->loanRepository);
    }

    public function testCreateLoan()
    {
        $data = [
            'book_id' => 1,
            'user_id' => 1,
            'loan_date' => '2022-01-01',
            'due_date' => '2022-01-02',
            'return_date' => null
        ];

        $loan = new Loan($data);
        $this->loanRepository->method('persist')->willReturn($loan);
        $loan = $this->loanService->create($data);
        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($data['book_id'], $loan->book_id);
        $this->assertEquals($data['user_id'], $loan->user_id);
        $this->assertEquals($data['loan_date'], $loan->loan_date);
        $this->assertEquals($data['due_date'], $loan->due_date);
        $this->assertEquals($data['return_date'], $loan->return_date);

    }

    public function testFindAllLoans()
    {
        $one = [
            'book_id' => 1,
            'user_id' => 1,
            'loan_date' => '2022-01-01',
            'due_date' => '2022-01-02',
            'return_date' => null
        ];
        $two = [
            'book_id' => 2,
            'user_id' => 1,
            'loan_date' => '2022-01-01',
            'due_date' => '2022-01-02',
            'return_date' => null
        ];

        $loanOne = new Loan($one);
        $loanTwo = new Loan($two);

        $this->loanRepository->method('findAll')->willReturn(
            new Collection([$loanOne, $loanTwo])
        );
        $loans = $this->loanService->findAll();

        $this->assertInstanceOf(Collection::class, $loans);
        $this->assertCount(2, $loans);
        $this->assertEquals($one['book_id'], $loans->first()['book_id']);
        $this->assertEquals($two['book_id'], $loans->last()['book_id']);
        $this->assertEquals($one['user_id'], $loans->first()['user_id']);
        $this->assertEquals($two['user_id'], $loans->last()['user_id']);
        $this->assertEquals($one['loan_date'], $loans->first()['loan_date']);
        $this->assertEquals($two['loan_date'], $loans->last()['loan_date']);
        $this->assertEquals($one['due_date'], $loans->first()['due_date']);
        $this->assertEquals($two['due_date'], $loans->last()['due_date']);
        $this->assertEquals($one['return_date'], $loans->first()['return_date']);
        $this->assertEquals($two['return_date'], $loans->last()['return_date']);
    }

    public function testUpdateLoan()
    {
        $data = [
            'book_id' => 1,
            'user_id' => 1,
            'loan_date' => '2022-01-01',
            'due_date' => '2022-01-02',
            'return_date' => '2022-01-02'
        ];

        $loan = new Loan($data);
        $loan->id = 1;

        $this->loanRepository->method('update')->willReturn($loan);
        $loan = $this->loanService->update($data, 1);
        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($data['book_id'], $loan->book_id);
        $this->assertEquals($data['user_id'], $loan->user_id);
        $this->assertEquals($data['loan_date'], $loan->loan_date);
        $this->assertEquals($data['due_date'], $loan->due_date);
        $this->assertEquals($data['return_date'], $loan->return_date);
    }

    public function testDeleteLoan()
    {
        $loan = new Loan([
            'book_id' => 1,
            'user_id' => 1,
            'loan_date' => '2022-01-01',
            'due_date' => '2022-01-02',
            'return_date' => '2022-01-02'
        ]);
        $loan->id = 1;

        $this->loanRepository->expects($this->once())
            ->method('delete')
            ->with($loan->id);

        $this->loanService->delete(1);
    }
}
