<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;   
    /**
     *@test
     */
    public function a_user_can_create()
    {
        $this->withoutExceptionHandling();

        //Authenticate user with sanctum
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        //Create user 
        $response = $this->postJson($this->baseUrl . 'user',[
            'email' => 'example@mail.com',
            'password' => "12345678",
            'password_confirmation' => "12345678"
        ])
        ->assertJsonStructure([
            'success',
            'data' => [
                'Correo',
                'Estado del usuario',
                'Estado de alerta'
            ],
            'message'
        ])
        ->assertOk();

        $this->assertDatabaseHas('users', [
            'email' => 'example@mail.com',
        ]);
    }

    /**
     * @test
     */
    public function list_of_users_retrieved()
    {
       $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        User::factory(2)->create();

        $response = $this->getJson($this->baseUrl . 'user')
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'Correo',
                    'Estado del usuario',
                    'Estado de alerta'
                ]
            ],
            'message'
        ])->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->getJson($this->baseUrl . 'user/' . $user->id)
        ->assertJsonStructure([
            'success',
            'data' => [
                'Correo',
                'Estado del usuario',
                'Estado de alerta'
            ],
            'message'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_be_updated()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->putJson($this->baseUrl . 'user/' . $user->id, [
            'email' => 'update@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ])
        ->assertJsonStructure([
        'success',
            'data' => [
                'Correo',
                'Estado del usuario',
                'Estado de alerta'
            ],
            'message'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->DeleteJson($this->baseUrl . 'user/' . $user->id)
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_be_suspended()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->putJson($this->baseUrl . 'suspend/' . $user->id)
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertOk();

    }

    /**
     * @test
     */
    public function a_user_can_see_profile()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::factory()->create(),
            ['profile']
        );

        $response = $this->getJson($this->baseUrl . 'profile')
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_can_change_password()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            User::factory()->create(),
            ['change-password']
        );

        $response = $this->postJson($this->baseUrl . 'change-password',[
            'current_password' => 'password',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ])
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertOk();
    }
}
