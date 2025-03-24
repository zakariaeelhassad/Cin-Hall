<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Seance;
use App\Services\SeanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeanceController extends Controller
{
    protected SeanceService $seanceService;

    public function __construct(SeanceService $seanceService)
    {
        $this->seanceService = $seanceService;
    }

    public function index()
    {
        $seances = $this->seanceService->all();

        return response()->json([
            'status' => 'success',
            'data' => $seances
        ], 200);
    }

    public function store(Request $request , $salle_id , $film_id)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un seance.'
            ], 403); 
        }

        $salle = Salle::find($salle_id);
        $film = Salle::find($film_id);
        if (!$salle || !$film) {
            return response()->json([
                'message' => 'Salle ou film non trouvée.'
            ], 404);
        }

        $data = $request->validate([
            'salle_id' => $salle_id,
            'film_id' => $film_id,
            'date_heure' => 'required|date',
            'type' => 'in:VIP,normale'
        ]);

        $data['admin_id'] = auth('api')->id();
        $seance = $this->seanceService->create($data);

        if (!$seance instanceof Seance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create seance'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $seance
        ], 201);
    }

    public function show(int $id)
    {
        $seance = $this->seanceService->find($id);

        if (!$seance instanceof Seance) {
            return response()->json([
                'status' => 'error',
                'message' => 'seance not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $seance
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'numero' => 'required|integer',
            'type' => 'in:solo,couple'
        ]);

        $result = $this->seanceService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update seance'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'seance updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->seanceService->delete($id);

        if (!is_bool($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete seance'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'seance deleted successfully'
        ], 200);
    }
}
