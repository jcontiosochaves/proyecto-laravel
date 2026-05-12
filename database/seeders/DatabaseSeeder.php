<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
{
    User::factory()->create([
        'name' => 'Jose Test',
        'email' => 'jose@example.com',
        'password' => bcrypt('123456'), 
    ]);

    
    \App\Models\Director::factory(5)->create()->each(function ($director) {
        \App\Models\Pelicula::factory(3)->create([
            'director_id' => $director->id,
        ]);
    });
}
}
