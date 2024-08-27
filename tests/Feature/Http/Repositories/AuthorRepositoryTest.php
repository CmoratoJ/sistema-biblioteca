<?php

namespace Tests\Feature\Http\Repositories;

use App\Http\Repositories\Interface\IAuthorRepository;
use App\Models\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private IAuthorRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(IAuthorRepository::class);
    }

    public function testPersistCreateAuthor()
    {
        $author = $this->repository->persist(['name' => 'John Doe']);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('John Doe', $author->name);
        $this->assertDatabaseHas('authors', ['name' => 'John Doe']);
        $this->assertTrue(Cache::missing('all_authors'));
    }

    public function testPersistUpdateAuthor()
    {
        $author = $this->repository->persist(['name' => 'John Doe']);
        $author = $this->repository->persist(['name' => 'Jane Doe'], $author->id);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('Jane Doe', $author->name);
        $this->assertDatabaseHas('authors', ['name' => 'Jane Doe']);
        $this->assertTrue(Cache::missing('all_authors'));
    }

    public function testFindAuthorById()
    {
        $author = $this->repository->persist(['name' => 'John Doe']);
        $author = $this->repository->findById($author->id);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('John Doe', $author->name);
    }

    public function testFindAllAuthors()
    {
        $this->repository->persist(['name' => 'John Doe']);
        $this->repository->persist(['name' => 'Jane Doe']);

        $authors = $this->repository->findAll();

        $this->assertCount(2, $authors);
        $this->assertInstanceOf(Collection::class, $authors);
        $this->assertTrue(Cache::has('all_authors'));
        $this->assertEquals(2, Cache::get('all_authors')->count());
    }

    public function testDeleteAuthor()
    {
        $author = $this->repository->persist(['name' => 'John Doe']);
        $this->repository->delete($author);

        $this->assertSoftDeleted('authors', ['id' => $author->id]);
        $this->assertTrue(Cache::missing('all_authors'));
    }
}
