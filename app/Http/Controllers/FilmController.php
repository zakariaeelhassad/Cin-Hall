<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use App\Services\FilmService;

class FilmController extends Controller
{
    protected FilmService $filmService;

    public function __construct(FilmService $filmService)
    {
        $this->filmService = $filmService;
    }

    public function index()
    {
        $films = $this->filmService->all();

        return response()->json([
            'status' => 'success',
            'data' => $films
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'durée' => 'required|integer',
            'langue' => 'required|string',
            'age_min' => 'required|integer',
            'bande_annonce' => 'nullable|string',
            'genre' => 'required|string',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('films', 'public');
                $data['image'] = $imagePath;
            }

            $film = $this->filmService->create($data);

            if (!$film instanceof Film) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create film'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Film créé avec succès',
                'data' => $film
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function show(int $id)
    {
        $film = $this->filmService->find($id);

        if (!$film instanceof Film) {
            return response()->json([
                'status' => 'error',
                'message' => 'Film not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $film
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'durée' => 'required|integer',
            'langue' => 'required|string',
            'age_min' => 'required|integer',
            'bande_annonce' => 'nullable|string',
            'genre' => 'required|string',
        ]);

        $result = $this->filmService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update film'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'film updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->filmService->delete($id);

        // if (!is_bool($result)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to delete film'
        //     ], 500);
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'film deleted successfully'
        ], 200);
    }
}

