<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Siege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiegeController extends Controller
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
    public function store(Request $request , $salle_id)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un Salle.'
            ], 403); 
        }

        $salle = Salle::find($salle_id);
        if (!$salle) {
            return response()->json([
                'message' => 'Salle non trouvée.'
            ], 404);
        }

        $request->validate([
            'salle_id' => $salle_id,
            'numero' => 'required|integer',
            'type' => 'in:solo,couple'
        ]);

        $siege = Siege::create([
            'salle_id' => $request->salle_id,
            'numero' => $request->numero,
            'type' => $request->type,
        ]);

        return response()->json([
            'message' => 'Siège ajouté avec succès',
            'siege' => $siege
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
