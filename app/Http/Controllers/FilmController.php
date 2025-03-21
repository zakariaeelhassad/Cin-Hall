<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $films = Film::with('user')->get(); 
        return response()->json($films);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un film.'
            ], 403); 
        }

        $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'durée' => 'required|integer',
            'langue' => 'required|string',
            'age_min' => 'required|integer',
            'bande_annonce' => 'nullable|string',
            'genre' => 'required|string',
        ]);

        $film = Film::create([
            'admin_id' => Auth::id(), 
            'titre' => $request->titre,
            'description' => $request->description,
            'image' => $request->image,
            'durée' => $request->durée,
            'langue' => $request->langue,
            'age_min' => $request->age_min,
            'bande_annonce' => $request->bande_annonce,
            'genre' => $request->genre,
        ]);

        return response()->json([
            'message' => 'Film ajouté avec succès',
            'film' => $film
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
