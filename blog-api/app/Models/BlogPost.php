<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'content',
        'excerpt',
        'author',
        'featured_image',
        'category_id',
        'tags',
        'published',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            // Ensure slug is unique
            $originalSlug = $post->slug;
            $counter = 1;
            while (static::where('slug', $post->slug)->exists()) {
                $post->slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        });
    }

    // Accessor for excerpt
    public function getExcerptAttribute()
    {
        if (!empty($this->attributes['excerpt'])) {
            return $this->attributes['excerpt'];
        }
        return Str::words(strip_tags($this->content), 20);
    }

    // Scope for filtering
    public function scopeFilter($query, array $filters)
    {
        // Search filter
        if ($filters['search'] ?? false) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('content', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('tags', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('author', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Category filter
        if ($filters['category'] ?? false) {
            $query->whereHas('category', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%')
                  ->orWhere('id', $filters['category']);
            });
        }

        // Author filter
        if ($filters['author'] ?? false) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }

        // Tag filter
        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . $filters['tag'] . '%');
        }

        return $query;
    }

    // Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}