<?php

namespace Tests\Feature\Controller\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_login_fail(): void
    {
        $response = $this->post('/api/auth/login');

        $response->assertStatus(302);
        $response->assertInvalid(["email", "password"]);
    }

    public function test_login_fail_password(): void
    {
        $loginData = ['email' => 'hieu@gmail.com', 'password' => 'password1'];
        $response = $this->post('/api/auth/login', $loginData);
        $response->assertStatus(401);
        $response->assertExactJson([
            'status' => 'error',
            'message' => 'Unauthorized',
        ]);
    }

    public function test_login_success(): void {
        $loginData = ['email' => 'hieu@gmail.com', 'password' => 'password'];
        $response = $this->post('/api/auth/login', $loginData);
        $response->assertStatus(200);
        $response->assertJsonStructure(["status","token","type","expires_in"]);
    }
}