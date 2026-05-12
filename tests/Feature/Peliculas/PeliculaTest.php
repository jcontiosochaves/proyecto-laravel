<?php

namespace Tests\Feature\Peliculas;

use App\Models\Director;
use App\Models\Pelicula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeliculaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_listar_peliculas_autenticado_devuelve_coleccion() {
        Pelicula::factory(3)->create();
        $this->actingAs($this->user, 'api')->getJson('/api/peliculas')
             ->assertStatus(200)->assertJsonCount(3);
    }

    public function test_crear_pelicula_asociada_a_director_existente() {
        $director = Director::factory()->create();
        $data = ['titulo' => 'Inception', 'año' => 2010, 'director_id' => $director->id];
        $this->actingAs($this->user, 'api')->postJson('/api/peliculas', $data)
             ->assertStatus(201);
        $this->assertDatabaseHas('peliculas', ['titulo' => 'Inception', 'director_id' => $director->id]);
    }

    public function test_crear_pelicula_con_director_inexistente_devuelve_422() {
        $data = ['titulo' => 'Error', 'año' => 2024, 'director_id' => 999];
        $this->actingAs($this->user, 'api')->postJson('/api/peliculas', $data)
             ->assertStatus(422);
    }

    public function test_actualizar_pelicula() {
        $pelicula = Pelicula::factory()->create(['titulo' => 'Original']);
        $this->actingAs($this->user, 'api')->putJson("/api/peliculas/{$pelicula->id}", [
            'titulo' => 'Editado', 
            'año' => 2020, 
            'director_id' => $pelicula->director_id
        ])->assertStatus(200);
        $this->assertDatabaseHas('peliculas', ['titulo' => 'Editado']);
    }

    public function test_eliminar_pelicula() {
        $pelicula = Pelicula::factory()->create();
        $this->actingAs($this->user, 'api')->deleteJson("/api/peliculas/{$pelicula->id}")
             ->assertStatus(200);
        $this->assertDatabaseMissing('peliculas', ['id' => $pelicula->id]);
    }

    public function test_mostrar_pelicula_incluye_datos_del_director() {
        $director = Director::factory()->create(['nombre' => 'Quentin Tarantino']);
        $pelicula = Pelicula::factory()->create(['director_id' => $director->id]);
        
        $this->actingAs($this->user, 'api')->getJson("/api/peliculas/{$pelicula->id}")
             ->assertStatus(200)
             ->assertJsonFragment(['nombre' => 'Quentin Tarantino']);
    }
}