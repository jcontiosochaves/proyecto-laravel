<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_exitoso_devuelve_token() {
        $user = User::factory()->create(['password' => bcrypt('123456')]);
        $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => '123456'])
             ->assertStatus(200)
             ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function test_login_con_credenciales_invalidas_devuelve_401() {
        $user = User::factory()->create();
        $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'error'])
             ->assertStatus(401)
             ->assertJsonStructure(['error']);
    }

   public function test_login_con_campos_faltantes_devuelve_422() {
        $this->postJson('/api/auth/login', ['email' => 'test@test.com'])
             ->assertStatus(422);
    }

    public function test_logout_invalida_el_token() 
{
    $user = User::factory()->create();
    $token = auth('api')->login($user);
    
    
    $this->withHeader('Authorization', "Bearer $token")
         ->postJson('/api/auth/logout')
         ->assertStatus(200);

        $response = $this->getJson('/api/auth/me', [
        'Authorization' => "Bearer $token"
    ]);

    $response->assertStatus(401);
}
  public function test_refresh_devuelve_nuevo_token_valido() {
    $user = User::factory()->create();
    $token = auth('api')->login($user);
    
    
    $res = $this->withHeader('Authorization', "Bearer $token")
                ->postJson('/api/auth/refresh');
    
    $res->assertStatus(200);
    $newToken = $res['access_token'];

    
    auth('api')->logout(); 
    auth('api')->forgetUser();

  
    $response = $this->json('GET', '/api/auth/me', [], [
        'Authorization' => "Bearer $newToken",
        'Accept' => 'application/json'
    ]);

    $response->assertStatus(200);
    $this->assertEquals($user->email, $response->json('email'));
}

    
    public function test_me_devuelve_datos_del_usuario_autenticado() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->getJson('/api/auth/me')
             ->assertStatus(200)
             ->assertJsonMissing(['password']);
    }

    
    public function test_acceso_sin_token_devuelve_401() {
        $this->getJson('/api/directores')->assertStatus(401);
    }

    public function test_acceso_con_token_malformado_devuelve_401() {
        $this->withHeader('Authorization', 'Bearer token_inventado')
             ->getJson('/api/directores')->assertStatus(401);
    }
}