<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Director extends Model
{
    use HasFactory;

    // Esto permite que el test pueda crear directores con nombre y biografía
    protected $fillable = ['nombre', 'biografia'];

    public function peliculas()
    {
        return $this->hasMany(Pelicula::class);
    }
}