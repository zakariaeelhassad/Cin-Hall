<?php

namespace App\Repositories;

use App\Models\Film;
use App\Repositories\interface\RepositoryInterface;

class FilmRepository implements RepositoryInterface
{
    public function all()
    {
        return Film::all();
    }

    public function create(array $data)
    {
        return Film::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = Film::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = Film::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return Film::find($id);
    }
}