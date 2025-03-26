<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Repositories\interface\RepositoryInterface;

class ReservationRepository implements RepositoryInterface
{
    public function all()
    {
        return Reservation::all();
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = Reservation::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = Reservation::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return Reservation::find($id);
    }
}