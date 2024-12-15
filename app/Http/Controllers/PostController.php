<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Student; // Replace with your model

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Fetch query parameters for search and pagination
        $search = $request->input('search'); // Search keyword
        $perPage = $request->input('limit', 10); // Items per page, default to 10

        // Build the query
        $query = Post::query();

        // Add search filter if the search parameter is provided
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%') // Example field
                  ->orWhere('content', 'like', '%' . $search . '%'); // Example field
        }

        // Paginate the results
        $posts = $query->paginate($perPage);

        // Return paginated and filtered data as JSON
        return response()->json($posts);
    }
}
