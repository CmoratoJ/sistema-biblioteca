<?php

namespace Tests\Feature\Http\Services;

use App\Http\Repositories\Interface\IUserRepository;
use App\Http\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private IUserRepository $repository;
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(IUserRepository::class);
        $this->authService = new AuthService();
    }

    public function testLoginAuthorized()
    {
        $this->repository->persist([
            'name' => 'John Doe',
            'email' => 'q6ZQg@example.com',
            'password' => bcrypt('password')
        ]);

        $auth = $this->authService->login(['email' => 'q6ZQg@example.com', 'password' => 'password']);
        $this->assertArrayHasKey('access_token', $auth);
    }

    public function testLoginUnauthorized()
    {
        $this->expectExceptionMessage('Unauthorized.');
        $auth = $this->authService->login(['email' => 'q6ZQg@example.com', 'password' => 'password']);
        $this->assertArrayNotHasKey('access_token', $auth);
    }
}
