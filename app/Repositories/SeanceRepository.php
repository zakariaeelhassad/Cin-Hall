<?php

namespace App\Repositories;

use App\Models\Seance;
use App\Repositories\interface\RepositoryInterface;

class SeanceRepository implements RepositoryInterface
{
    public function all()
    {
        return Seance::all();
    }

    public function create(array $data)
    {
        return Seance::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = Seance::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = Seance::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return Seance::find($id);
    }
}