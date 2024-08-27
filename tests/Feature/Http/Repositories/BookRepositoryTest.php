<?php

namespace Tests\Feature\Http\Repositories;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Http\Repositories\Interface\IBookRepository;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private IBookRepository $repository;
    private IAuthorRepository $authorRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(IBookRepository::class);
        $this->authorRepository = $this->app->make(IAuthorRepository::class);
    }

    public function testPersistBook()
    {
        $author = $this->authorRepository->persist(['name' => 'author']);
        $data = ['title' => 'The book', 'publication_year' => 2020];
        $authors = ['author_id' => $author->id];

        $book = $this->repository->persist($data, $authors);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('The book', $book->title);
        $this->assertEquals(2020, $book->publication_year);
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'author']);
        $this->assertDatabaseHas('author_book', ['book_id' => $book->id, 'author_id' => $author->id]);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'The book']);
        $this->assertTrue(Cache::missing('all_books'));
    }

    public function testUpdateBook()
    {
        $author = $this->authorRepository->persist(['name' => 'author']);
        $data = ['title' => 'The book', 'publication_year' => 2020];
        $authors = ['author_id' => $author->id];

        $book = $this->repository->persist($data, $authors);

        $data = ['title' => 'The new book', 'publication_year' => 2021];
        $authors = ['author_id' => $author->id];

        $book = $this->repository->update($data, $authors, $book->id);

        $this->assertEquals('The new book', $book->title);
        $this->assertEquals(2021, $book->publication_year);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'The new book']);
        $this->assertDatabaseHas('author_book', ['book_id' => $book->id, 'author_id' => $author->id]);
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'author']);
        $this->assertTrue(Cache::missing('all_books'));
    }

    public function testFindBookById()
    {
        $author = $this->authorRepository->persist(['name' => 'author']);
        $data = ['title' => 'The book', 'publication_year' => 2020];
        $authors = ['author_id' => $author->id];

        $book = $this->repository->persist($data, $authors);
        $book = $this->repository->findById($book->id);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('The book', $book->title);
        $this->assertEquals(2020, $book->publication_year);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'The book']);
    }

    public function testFindAllBooks()
    {
        $author = $this->authorRepository->persist(['name' => 'author']);
        $dataOne = ['title' => 'The book', 'publication_year' => 2020];
        $dataTwo = ['title' => 'The new book', 'publication_year' => 2021];
        $authors = ['author_id' => $author->id];

        $this->repository->persist($dataOne, $authors);
        $this->repository->persist($dataTwo, $authors);
        $books = $this->repository->findAll();

        $this->assertCount(2, $books);
        $this->assertInstanceOf(Collection::class, $books);
        $this->assertTrue(Cache::has('all_books'));
        $this->assertEquals(2, Cache::get('all_books')->count());
        $this->assertEquals('The book', $books->first()->title);
        $this->assertEquals('The new book', $books->last()->title);
        $this->assertEquals(2020, $books->first()->publication_year);
        $this->assertEquals(2021, $books->last()->publication_year);
    }

    public function testDeleteBook()
    {
        $author = $this->authorRepository->persist(['name' => 'author']);
        $data = ['title' => 'The book', 'publication_year' => 2020];
        $authors = ['author_id' => $author->id];

        $book = $this->repository->persist($data, $authors);
        $this->repository->delete($book);

        $this->assertSoftDeleted('books', ['id' => $book->id]);
        $this->assertTrue(Cache::missing('all_books'));
    }
}
