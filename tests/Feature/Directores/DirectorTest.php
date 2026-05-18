<?php

namespace Tests\Feature\Directores;

use App\Models\Director;
use App\Models\Pelicula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_listar_directores_requiere_autenticacion()
    {
        $this->getJson('/api/directores')->assertStatus(401);
    }

    public function test_listar_directores_autenticado_devuelve_coleccion()
    {
        Director::factory(2)->create();
        $this->actingAs($this->user, 'api')->getJson('/api/directores')
            ->assertStatus(200)->assertJsonCount(2);
    }

    public function test_crear_director_con_datos_validos()
    {
        $data = ['nombre' => 'James Cameron', 'biografia' => 'Director de Titanic'];
        $this->actingAs($this->user, 'api')->postJson('/api/directores', $data)
            ->assertStatus(201);
        $this->assertDatabaseHas('directors', ['nombre' => 'James Cameron']);
    }

    public function test_crear_director_con_datos_invalidos_devuelve_422()
    {
        $this->actingAs($this->user, 'api')->postJson('/api/directores', [])
            ->assertStatus(422);
    }

    public function test_actualizar_director_existente()
    {
        $director = Director::factory()->create(['nombre' => 'Antiguo']);
        $this->actingAs($this->user, 'api')->putJson("/api/directores/{$director->id}", ['nombre' => 'Nuevo'])
            ->assertStatus(200);
        $this->assertDatabaseHas('directors', ['nombre' => 'Nuevo']);
    }

    public function test_actualizar_director_inexistente_devuelve_404()
    {
        $this->actingAs($this->user, 'api')->putJson('/api/directores/999', ['nombre' => 'X'])
            ->assertStatus(404);
    }

    public function test_eliminar_director_existente()
    {
        $director = Director::factory()->create();
        $this->actingAs($this->user, 'api')->deleteJson("/api/directores/{$director->id}")
            ->assertStatus(200);
        $this->assertDatabaseMissing('directors', ['id' => $director->id]);
    }

    public function test_eliminar_director_con_peliculas_asociadas()
    {
        $director = Director::factory()->create();
        Pelicula::factory()->create(['director_id' => $director->id]);
        $this->actingAs($this->user, 'api')->deleteJson("/api/directores/{$director->id}")
            ->assertStatus(200);
        $this->assertDatabaseMissing('directors', ['id' => $director->id]);
    }
}
