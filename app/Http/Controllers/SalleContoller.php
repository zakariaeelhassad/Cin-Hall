<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalleContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un Salle.'
            ], 403); 
        }
        
        $request->validate([
            'nom' => 'required|string',
            'capacite' => 'required|integer',
            'type' => 'in:Normale,VIP'
        ]);

        $salle = Salle::create([
            "nom" => $request->nom,
            "capacite" => $request->capacite,
            "type" => $request->type,
        ]);

        return response()->json([
            'message' => "Salle ajouté avec succès",
            'salle' => $salle 
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
