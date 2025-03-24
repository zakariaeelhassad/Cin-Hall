<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\interface\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, int $id)
    {
        $user = User::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }

    public function find(int $id)
    {
        return User::find($id);
    }
}