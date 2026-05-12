<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelicula extends Model
{
    use HasFactory;

    // Esto permite que el test cree películas asociadas a un director
    protected $fillable = ['titulo', 'año', 'director_id'];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }
}
