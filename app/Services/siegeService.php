<?php

namespace App\Services;

use App\Repositories\SiegeRepository;

class SiegeService
{
    private SiegeRepository $repository;

    public function __construct(SiegeRepository $repository) {
        $this->repository = $repository;
    }

    public function create(array $data )
    {
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

    public function generateSiegesForSalle($salle)
    {
        $sieges = [];

        for ($i = 1; $i <= $salle->capacite; $i++) {
            $type = ($salle->type_seance === 'VIP' && $i % 2 == 1) ? 'couple' : 'solo';
            $sieges[] = $this->repository->create([
                'salle_id' => $salle->id,
                'numero' => 'S' . $i,
                'type' => $type,
            ]);
        }

        return $sieges;
    }
}