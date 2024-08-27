<?php

namespace Tests\Feature\Http\Repositories;

use App\Http\Repositories\Interface\IUserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private IUserRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(IUserRepository::class);
    }

    public function testPersistCreateUser()
    {
        $user = $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'q6ZQg@example.com']);
        $this->assertEquals('John Doe', $user->name);
        $this->assertTrue(Cache::missing('all_users'));
    }

    public function testPersistUpdateUser()
    {
        $user = $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $user = $this->repository->persist([
            'name' => 'Jane Doe',
            'email' => 'qsdfgg@example.com',
            'password' => bcrypt('password')
        ], $user->id);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'qsdfgg@example.com']);
        $this->assertEquals('Jane Doe', $user->name);
        $this->assertTrue(Cache::missing('all_users'));
    }

    public function testFindUserById()
    {
        $user = $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $user = $this->repository->findById($user->id);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('q6ZQg@example.com', $user->email);
    }

    public function testFindAllUsers()
    {
        $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $this->repository->persist([
            'name' => 'Jane Doe',
            'email' => 'qsdfgg@example.com',
            'password' => bcrypt('password')
        ]);

        $users = $this->repository->findAll();

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertCount(2, $users);
        $this->assertEquals('q6ZQg@example.com', $users->first()->email);
        $this->assertEquals('qsdfgg@example.com', $users->last()->email);
        $this->assertTrue(Cache::has('all_users'));
        $this->assertEquals(2, Cache::get('all_users')->count());
    }

    public function testDeleteUser()
    {
        $user = $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $this->repository->delete($user);

        $this->assertDatabaseMissing('users', ['email' => 'q6ZQg@example.com']);
        $this->assertTrue(Cache::missing('all_users'));
    }
}
