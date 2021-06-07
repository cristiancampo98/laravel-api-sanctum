<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_user_can_register()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson($this->baseUrl . 'register',[
            'email' => 'example@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ])
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertJson([
            'success' => true,
            'data' => [
                'email' => 'example@mail.com',
                'admin' => true
            ],
            'message' => 'Registered successfully'
        ])
        ->assertOk();

        $this->assertDatabaseHas('users', [
            'email' => 'example@mail.com',
            'admin' => true
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_login()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->postJson($this->baseUrl . 'login', [
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'email'
                ],
                'Alerta',
                'token'
            ],
            'message'
        ])
         ->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'email' => $user->email
                ]
            ],
            'message' => 'User signed in'
        ])
        ->assertOk();

    }
}
