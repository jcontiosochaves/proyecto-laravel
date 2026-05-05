<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    public function director()
    {
        return $this->belongsTo(Director::class);
    }
}
