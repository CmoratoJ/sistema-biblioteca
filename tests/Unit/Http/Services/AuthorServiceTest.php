<?php

namespace Tests\Unit\Http\Services;


use App\Http\Repositories\Interface\IAuthorRepository;
use App\Http\Services\AuthorService;
use App\Models\Author;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AuthorServiceTest extends TestCase
{
    private AuthorService $authorService;
    private IAuthorRepository $authorRepository;
    public function setUp(): void
    {
        $this->authorRepository = $this->createMock(IAuthorRepository::class);
        $this->authorService = new AuthorService($this->authorRepository);
    }

    public function testCreateAuthor()
    {
        $data = ['name' => 'John Doe'];
        $author = new Author($data);
        $this->authorRepository->method('persist')->willReturn($author);
        $author = $this->authorService->create($data);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals($data['name'], $author->name);
    }

    public function testFindAllAuthors()
    {
        $john = ['name' => 'John Doe'];
        $jane = ['name' => 'Jane Doe'];

        $authorJohn = new Author($john);
        $authorJane = new Author($jane);

        $this->authorRepository->method('findAll')->willReturn(
            new Collection([$authorJohn, $authorJane])
        );
        $authors = $this->authorService->findAll();

        $this->assertInstanceOf(Collection::class, $authors);
        $this->assertCount(2, $authors);
        $this->assertEquals($john['name'], $authors->first()['name']);
        $this->assertEquals($jane['name'], $authors->last()['name']);
    }

    public function testFindAuthorById()
    {
        $john = ['name' => 'John Doe'];

        $authorJohn = new Author($john);
        $authorJohn->id = 1;
        $this->authorRepository->method('findById')->willReturn($authorJohn);
        $author = $this->authorService->findById(1);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals(1, $author->id);
        $this->assertEquals($john['name'], $author->name);
    }

    public function testUpdateAuthor()
    {
        $data = ['name' => 'Jane Doe'];

        $author = new Author($data);
        $author->id = 1;

        $this->authorRepository->method('persist')->willReturn($author);
        $author = $this->authorService->update($data, 1);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals(1, $author->id);
        $this->assertEquals($data['name'], $author->name);
    }

    public function testDeleteAuthor()
    {
        $data = ['name' => 'John Doe'];

        $author = new Author($data);
        $author->id = 1;

        $this->authorRepository->expects($this->once())
            ->method('findById')
            ->with($author->id)
            ->willReturn($author);

        $this->authorRepository->expects($this->once())
            ->method('delete')
            ->with($author);

        $this->authorService->delete(1);
    }
}
