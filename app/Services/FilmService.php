<?php

namespace App\Services;

use App\Repositories\FilmRepository;
use Illuminate\Support\Facades\Auth;

class FilmService
{
    private FilmRepository $repository;

    public function __construct(FilmRepository $repository) {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter un Salle.'
            ], 403); 
        }

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