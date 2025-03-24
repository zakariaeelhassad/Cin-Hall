<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Siege;
use App\Services\SiegeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiegeController extends Controller
{
    protected SiegeService $siegeService;

    public function __construct(SiegeService $siegeService)
    {
        $this->siegeService = $siegeService;
    }

    public function index()
    {
        $sieges = $this->siegeService->all();

        return response()->json([
            'status' => 'success',
            'data' => $sieges
        ], 200);
    }

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

        $data = $request->validate([
            'salle_id' => $salle_id,
            'numero' => 'required|integer',
            'type' => 'in:solo,couple'
        ]);

        $data['admin_id'] = auth('api')->id();
        $siege = $this->siegeService->create($data);

        if (!$siege instanceof Siege) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create siege'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $siege
        ], 201);
    }

    public function show(int $id)
    {
        $siege = $this->siegeService->find($id);

        if (!$siege instanceof Siege) {
            return response()->json([
                'status' => 'error',
                'message' => 'siege not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $siege
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'numero' => 'required|integer',
            'type' => 'in:solo,couple'
        ]);

        $result = $this->siegeService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update siege'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'siege updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->siegeService->delete($id);

        if (!is_bool($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete siege'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'siege deleted successfully'
        ], 200);
    }
}
