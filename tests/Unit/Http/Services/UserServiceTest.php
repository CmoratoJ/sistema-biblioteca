<?php

namespace Tests\Unit\Http\Services;

use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private IUserRepository $userRepository;
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(IUserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testCreateUser()
    {
        $data = ['name' => 'John Doe','email' => '5H5hK@example.com'];
        $user = new User($data);
        $this->userRepository->method('persist')->willReturn($user);
        $user = $this->userService->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    public function testFindAllUsers()
    {
        $john = ['name' => 'John Doe', 'email' => '5H5hK@example.com'];
        $jane = ['name' => 'Jane Doe', 'email' => 'j5H5hK@example.com'];

        $userJohn = new User($john);
        $userJane = new User($jane);

        $this->userRepository->method('findAll')->willReturn(new Collection([$userJohn, $userJane]));
        $users = $this->userService->findAll();

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertCount(2, $users);
        $this->assertEquals('John Doe', $users->first()['name']);
        $this->assertEquals('5H5hK@example.com', $users->first()['email']);
        $this->assertEquals('Jane Doe', $users->last()['name']);
        $this->assertEquals('j5H5hK@example.com', $users->last()['email']);
    }

    public function testFindUserById()
    {
        $john = ['name' => 'John Doe', 'email' => '5H5hK@example.com'];

        $user = new User($john);
        $user->id = 1;

        $this->userRepository->method('findById')->willReturn($user);
        $user = $this->userService->findById(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals($john['name'], $user->name);
        $this->assertEquals($john['email'], $user->email);
    }

    public function testUpdateUser()
    {
        $data = ['name' => 'Jane Doe', 'email' => 'j5H5hK@example.com'];

        $user = new User($data);
        $user->id = 1;

        $this->userRepository->method('persist')->willReturn($user);
        $user = $this->userService->update($data, 1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('Jane Doe', $user->name);
        $this->assertEquals('j5H5hK@example.com', $user->email);
    }

    public function testDeleteUser()
    {
        $data = ['name' => 'John Doe', 'email' => '5H5hK@example.com'];
        $user = new User($data);
        $user->id = 1;

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with($user->id)
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method('delete')
            ->with($user);

        $this->userService->delete(1);
    }
}
