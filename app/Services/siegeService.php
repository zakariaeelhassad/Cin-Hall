<?php

namespace App\Services;

use App\Models\Salle;
use App\Repositories\SiegeRepository;
use Illuminate\Support\Facades\Auth;

class SiegeService
{
    private SiegeRepository $repository;

    public function __construct(SiegeRepository $repository) {
        $this->repository = $repository;
    }

    public function create(array $data , $salle_id)
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
        $data['salle_id'] = $salle_id; 
        $data['admin_id'] = auth('api')->id();
        return $this->repository->create($data);
    }

    public function update(array $data, int $id)
    {
        return $this->repository->update($data, $id);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}