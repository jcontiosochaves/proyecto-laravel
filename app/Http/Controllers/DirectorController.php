<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Director;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return response()->json(Director::all(), 200);
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'nullable|string'
        ]);

        $director = Director::create($validated);
        return response()->json($director, 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        return response()->json($director, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'biografia' => 'nullable|string'
        ]);

        $director->update($validated);
        return response()->json($director, 200);
    }


    public function destroy(string $id)
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        $director->delete();
        return response()->json(['message' => 'Director eliminado correctamente'], 200);
    }
}
