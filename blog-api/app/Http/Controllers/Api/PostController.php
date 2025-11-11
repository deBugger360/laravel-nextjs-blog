<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Public: Get all published posts
    public function index(): JsonResponse
    {
        $posts = BlogPost::with(['user', 'category'])
            ->published()
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Posts retrieved successfully'
        ]);
    }

    // Public: Get single post by slug
    public function show($slug): JsonResponse
    {
        $post = BlogPost::with(['user', 'category'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Post retrieved successfully'
        ]);
    }

    // Public: Filter posts
    public function filtered(Request $request): JsonResponse
    {
        $posts = BlogPost::with(['user', 'category'])
            ->published()
            ->filter($request->only(['search', 'category', 'author', 'tag']))
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'filters' => $request->all(),
            'message' => 'Filtered posts retrieved successfully'
        ]);
    }

    // Admin: Get posts for management
    public function manage(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $posts = BlogPost::with(['user', 'category'])->latest()->paginate(10);
        } else {
            $posts = $user->blogposts()->with('category')->latest()->paginate(10);
        }

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Posts for management retrieved successfully'
        ]);
    }

    // Admin: Create new post
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'published' => 'boolean',
            'featured_image' => 'nullable|string' // For base64 or URL
        ]);

        $validated['author'] = $request->user()->name;
        $validated['user_id'] = $request->user()->id;

        $post = BlogPost::create($validated);

        return response()->json([
            'success' => true,
            'data' => $post->load(['user', 'category']),
            'message' => 'Post created successfully'
        ], 201);
    }

    // Admin: Update post
    public function update(Request $request, $id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);

        // Authorization check
        if (!$request->user()->isAdmin() && $post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this post'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'excerpt' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'tags' => 'nullable|string',
            'published' => 'sometimes|boolean',
            'featured_image' => 'nullable|string'
        ]);

        $post->update($validated);

        return response()->json([
            'success' => true,
            'data' => $post->load(['user', 'category']),
            'message' => 'Post updated successfully'
        ]);
    }

    // Admin: Delete post
    public function destroy(Request $request, $id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);

        // Authorization check
        if (!$request->user()->isAdmin() && $post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this post'
            ], 403);
        }

        // Delete featured image if exists
        if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    // Public: Get authors list
    public function getAuthors(): JsonResponse
    {
        $authors = User::whereHas('blogposts')
            ->select('id', 'name', 'email')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $authors,
            'message' => 'Authors retrieved successfully'
        ]);
    }
}