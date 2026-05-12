<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_expirado_devuelve_401() {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        
        Carbon::setTestNow(Carbon::now()->addHours(2));

        $this->withHeader('Authorization', "Bearer $token")
             ->getJson('/api/directores')
             ->assertStatus(401);
             
        Carbon::setTestNow();
    }

    public function test_respuestas_de_error_no_exponen_stack_trace() {
        config(['app.debug' => false]);
        
        $response = $this->getJson('/api/ruta-que-no-existe');
        
        $response->assertJsonMissing(['exception', 'file', 'line', 'trace']);
    }

    public function test_password_no_aparece_en_respuesta_me() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api')->getJson('/api/auth/me')
             ->assertStatus(200)
             ->assertJsonMissingPath('password');
    }
}