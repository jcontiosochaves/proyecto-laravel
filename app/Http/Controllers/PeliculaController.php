<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;

class PeliculaController extends Controller
{
    public function index()
    {
        return response()->json(Pelicula::with('director')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'año' => 'required|integer',
            'director_id' => 'required|exists:directors,id'
        ]);

        $pelicula = Pelicula::create($validated);
        return response()->json($pelicula, 201);
    }

    public function show(string $id)
    {
        $pelicula = Pelicula::with('director')->find($id);
        return $pelicula ? response()->json($pelicula, 200) : response()->json(['m' => 'No encontrada'], 404);
    }

    public function update(Request $request, $id)
    {
        $pelicula = Pelicula::findOrFail($id);

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'año' => 'required|integer',
            'director_id' => 'required|exists:directors,id',
        ]);

        $pelicula->update($data);
        return response()->json($pelicula, 200);
    }

    public function destroy($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        $pelicula->delete();

        return response()->json(['message' => 'Pelicula eliminada'], 200);
    }
}