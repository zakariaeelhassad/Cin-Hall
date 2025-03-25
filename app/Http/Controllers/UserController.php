<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->all();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'in:admin,client'
        ]);

        $user = $this->userService->create($data);
        $token = JWTAuth::fromUser($user);

        if (!$user instanceof User) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user'
            ],500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'token'   => $token
        ], 201);
    }

    public function show(int $id)
    {
        $user = $this->userService->find($id);

        if (!$user instanceof User) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'sometimes|confirmed',
            'role' => 'in:admin,client'
        ]);

        $result = $this->userService->update($data, $id);

        if ($result <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully'
        ],200);
    }

    public function destroy(int $id)
    {
        $result = $this->userService->delete($id);

        if (!is_bool($result)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }
}

