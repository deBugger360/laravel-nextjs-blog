<?php
// app/Http/Controllers/Api/UserController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Admin: Get all users
    public function index(): JsonResponse
    {
        $users = User::withCount('blogposts')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    // Admin: Create user
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,author,user'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    // Admin: Get specific user
    public function show($id): JsonResponse
    {
        $user = User::with('blogposts')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    // Admin: Update user
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'role' => 'sometimes|in:admin,author,user'
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User updated successfully'
        ]);
    }

    // Admin: Delete user
    public function destroy($id): JsonResponse
    {
        // Prevent users from deleting themselves
        if (auth()->id() == $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    // Update user profile
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id)
            ]
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }

    // Update user password
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}