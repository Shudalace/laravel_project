<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Specify which fields can be mass-assigned
    protected $fillable = [
        'title', 
        'content', 
        'author', 
        'published_at' // Add your actual columns here
    ];

    // Example scope for searching
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where('title', 'like', $term)
              ->orWhere('content', 'like', $term)
              ->orWhere('author', 'like', $term);
    }
}
