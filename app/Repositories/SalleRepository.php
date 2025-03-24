<?php

namespace App\Repositories;

use App\Models\Salle;
use App\Repositories\interface\RepositoryInterface;

class SalleRepository implements RepositoryInterface
{
    public function all()
    {
        return Salle::all();
    }

    public function create(array $data)
    {
        return Salle::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = Salle::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = Salle::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return Salle::find($id);
    }
}