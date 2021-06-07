<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
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
            'password' => 12345678,
            'password_confirmation' => 12345678
        ])->assertOk();

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

        User::factory(10)->create();

        $response = $this->getJson($this->baseUrl . 'user');
        $response->assertOk();
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
        ->assertOk();

    }

    /**
     * @test
     */
    public function a_user_can_be_update()
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
        ])->assertOk();
    }
}
