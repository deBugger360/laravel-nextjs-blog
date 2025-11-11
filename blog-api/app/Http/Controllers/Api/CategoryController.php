<?php
// app/Http/Controllers/Api/CategoryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    // Public: Get all categories
    public function index(): JsonResponse
    {
        $categories = Category::withCount('posts')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }

    // Admin: Create category
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category created successfully'
        ], 201);
    }

    // Admin: Update category
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Category updated successfully'
        ]);
    }

    // Admin: Delete category
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that has posts'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}