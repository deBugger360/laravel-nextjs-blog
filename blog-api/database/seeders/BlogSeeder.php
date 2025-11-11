<?php
// database/seeders/BlogSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Blog Admin',
            'email' => 'admin@blog.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Author User
        $author = User::create([
            'name' => 'John Doe',
            'email' => 'author@blog.com',
            'password' => Hash::make('password123'),
            'role' => 'author',
        ]);

        // Create Categories
        $categories = [
            'Technology',
            'Web Development',
            'Laravel',
            'JavaScript',
            'PHP',
            'Vue.js',
            'React',
            'Next.js'
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        // Create Sample Blog Posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel',
                'content' => 'Laravel is a web application framework with expressive, elegant syntax...',
                'excerpt' => 'Learn the basics of Laravel framework',
                'published' => true,
            ],
            [
                'title' => 'Building APIs with Laravel Sanctum',
                'content' => 'Laravel Sanctum provides a featherweight authentication system for SPAs and simple APIs...',
                'excerpt' => 'Learn how to build secure APIs with Laravel Sanctum',
                'published' => true,
            ],
            [
                'title' => 'Next.js for React Developers',
                'content' => 'Next.js is a React framework that enables server-side rendering and static site generation...',
                'excerpt' => 'Explore the features of Next.js framework',
                'published' => false,
            ]
        ];

        foreach ($posts as $postData) {
            BlogPost::create(array_merge($postData, [
                'user_id' => $author->id,
                'author' => $author->name,
                'category_id' => Category::inRandomOrder()->first()->id,
                'tags' => 'laravel,php,api',
                'slug' => Str::slug($postData['title'])
            ]));
        }

        $this->command->info('Sample data seeded successfully!');
        $this->command->info('Admin Login: admin@blog.com / password123');
        $this->command->info('Author Login: author@blog.com / password123');
    }
}