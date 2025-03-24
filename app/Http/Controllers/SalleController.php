<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Services\SalleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalleController extends Controller
{
    protected SalleService $salleService;

    public function __construct(SalleService $salleService)
    {
        $this->salleService = $salleService;
    }

    public function index()
    {
        $Salles = $this->salleService->all();

        return response()->json([
            'status' => 'success',
            'data' => $Salles
        ], 200);
    }

    public function store(Request $request , $salle_id)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un Salle.'
            ], 403); 
        }

        $data = $request->validate([
            'nom' => 'required|string',
            'capacite' => 'required|integer',
            'type' => 'in:Normale,VIP'
        ]);

        $data['admin_id'] = auth('api')->id();
        $Salle = $this->salleService->create($data);

        if (!$Salle instanceof Salle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Salle'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $Salle
        ], 201);
    }

    public function show(int $id)
    {
        $Salle = $this->salleService->find($id);

        if (!$Salle instanceof Salle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Salle not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $Salle
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'capacite' => 'required|integer',
            'type' => 'in:Normale,VIP'
        ]);

        $result = $this->salleService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Salle'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Salle updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->salleService->delete($id);

        if (!is_bool($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Salle'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Salle deleted successfully'
        ], 200);
    }
}
