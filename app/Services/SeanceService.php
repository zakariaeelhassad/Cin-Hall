<?php

namespace App\Services;

use App\Models\Film;
use App\Models\Salle;
use App\Repositories\SeanceRepository;
use Illuminate\Support\Facades\Auth;

class SeanceService
{
    private SeanceRepository $repository;

    public function __construct(SeanceRepository $repository) {
        $this->repository = $repository;
    }

    public function create(array $data , $salle_id, $film_id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un seance.'
            ], 403); 
        }

        $salle = Salle::find($salle_id);
        $film = Film::find($film_id);

        if (!$salle || !$film) {
            return null;
        }

        $data['salle_id'] = $salle_id;
        $data['film_id'] = $film_id;
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