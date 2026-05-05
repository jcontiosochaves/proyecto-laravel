<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_login_exitoso_devuelve_token_y_estructura_correcta()
{
    // Creamos un usuario de prueba usando el modelo que ya configuraste
    $user = \App\Models\User::factory()->create([
        'password' => bcrypt($password = 'secret123'),
    ]);

    // Hacemos la petición a la ruta que acabas de configurar
    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    // Verificamos el cumplimiento de la Tarea 2
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'access_token', 
                 'token_type', 
                 'expires_in'
             ]);
}
}
