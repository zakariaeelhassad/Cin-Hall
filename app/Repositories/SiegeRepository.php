<?php

namespace App\Repositories;

use App\Models\Siege;
use App\Repositories\interface\RepositoryInterface;

class SiegeRepository implements RepositoryInterface
{
    public function all()
    {
        return Siege::all();
    }

    public function create(array $data)
    {
        return Siege::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = Siege::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = Siege::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return Siege::find($id);
    }
}