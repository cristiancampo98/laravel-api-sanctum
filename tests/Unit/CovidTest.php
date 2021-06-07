<?php

namespace Tests\Unit;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CovidTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function a_user_has_a_red_alert()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->postJson($this->baseUrl . 'validate',[
            'temperatura' => 29
        ])
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertJson([
            'success' => true,
            'data' => 'rojo',
            'message' => 'Usted tiene sintomas no puede entrar a la sede, por favor visite su medico'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_has_a_yellow_alert()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->postJson($this->baseUrl . 'validate',[
            'contacto' => true
        ])
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertJson([
            'success' => true,
            'data' => 'amarillo',
            'message' => 'Estas suspendido. Comunicarse con el administrador'
        ])
        ->assertOk();
    }

    /**
     * @test
     */
    public function a_user_has_not_alert()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->postJson($this->baseUrl . 'validate',[])
        ->assertJsonStructure([
            'success',
            'data',
            'message'
        ])
        ->assertJson([
            'success' => true,
            'data' => 'verde',
            'message' => 'Response success'
        ])
        ->assertOk();
    }
}
