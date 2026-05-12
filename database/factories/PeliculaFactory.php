<?php

namespace Database\Factories;

use App\Models\Pelicula;
use App\Models\Director;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeliculaFactory extends Factory
{
      
    protected $model = Pelicula::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo'      => $this->faker->sentence(3),
            'año'         => $this->faker->year(),
            'director_id' => Director::factory(), 
        ];
    }
}