<?php
// app/Http/Controllers/Api/DashboardController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        $stats = [
            'total_posts' => BlogPost::count(),
            'published_posts' => BlogPost::published()->count(),
            'draft_posts' => BlogPost::where('published', false)->count(),
            'total_users' => User::count(),
            'total_categories' => Category::count(),
        ];

        // Recent activity
        $recentPosts = BlogPost::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // User-specific stats for authors
        if (!$user->isAdmin()) {
            $stats['user_posts'] = $user->blogposts()->count();
            $stats['user_published_posts'] = $user->blogposts()->published()->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_posts' => $recentPosts,
                'recent_users' => $recentUsers,
            ],
            'message' => 'Dashboard data retrieved successfully'
        ]);
    }
}