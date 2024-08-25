<?php

namespace Tests\Unit\Http\Services;

use App\Http\Services\AuthService;
use Database\Factories\UserFactory;
use Tests\TestCase;


class AuthServiceTest extends TestCase
{
    public function testLoginUnauthorized()
    {
        $user = UserFactory::new()->make();

        $this->expectExceptionMessage('Unauthorized.');

        $authService = new AuthService();
        $auth = $authService->login([
            'email' => $user->email,
            'password' => $user->password
        ]);

        $this->assertEquals(401, $auth->status());
    }
}
