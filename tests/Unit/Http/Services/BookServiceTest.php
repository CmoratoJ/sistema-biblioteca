<?php

namespace Http\Services;

use App\Http\Repositories\Interface\IBookRepository;
use App\Http\Services\BookService;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    private BookService $bookService;
    private IBookRepository $bookRepository;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(IBookRepository::class);
        $this->bookService = new BookService($this->bookRepository);
    }

    public function testCreateBook()
    {
        $data = [
            'title' => 'title',
            'publication_year' => 2020
        ];
        $authors = ['author_id' => 1];
        $book = new Book($data);
        $this->bookRepository->method('persist')->willReturn($book);
        $book = $this->bookService->create($data, $authors);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['publication_year'], $book->publication_year);
    }

    public function testFindAllBooks()
    {
        $laravel = ['title' => 'Laravel', 'publication_year' => 2020];
        $vue = ['title' => 'Vue', 'publication_year' => 2021];

        $bookLaravel = new Book($laravel);
        $bookVue = new Book($vue);

        $this->bookRepository->method('findAll')->willReturn(
            new Collection([$bookLaravel, $bookVue])
        );
        $books = $this->bookService->findAll();

        $this->assertInstanceOf(Collection::class, $books);
        $this->assertCount(2, $books);
        $this->assertEquals($laravel['title'], $books->first()['title']);
        $this->assertEquals($vue['title'], $books->last()['title']);
        $this->assertEquals($laravel['publication_year'], $books->first()['publication_year']);
        $this->assertEquals($vue['publication_year'], $books->last()['publication_year']);
    }

    public function testFindBookById()
    {
        $data = ['title' => 'Laravel', 'publication_year' => 2020];

        $book = new Book($data);

        $this->bookRepository->method('findById')->willReturn($book);
        $book = $this->bookService->findById(1);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['publication_year'], $book->publication_year);
    }

    public function testUpdateBook()
    {
        $data = ['title' => 'Laravel', 'publication_year' => 2020];
        $authors = ['author_id' => 1];
        $book = new Book($data);
        $this->bookRepository->method('update')->willReturn($book);
        $book = $this->bookService->update($data, $authors, 1);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($data['title'], $book->title);
        $this->assertEquals($data['publication_year'], $book->publication_year);
    }

    public function testDeleteBook()
    {
        $book = new Book(
            [
                'title' => 'Laravel',
                'publication_year' => 2020
            ]
        );
        $book->id = 1;

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($book->id)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('delete')
            ->with($book);

        $this->bookService->delete(1);
    }
}
