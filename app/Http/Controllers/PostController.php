<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
     return Post::with('user')
        ->where('user_id', Auth::id())
        ->latest()
        ->take(10)
        ->get();

        // return Post::with('user')
        // ->latest()
        // ->paginate(10);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

         $imageUrl = null;


        
        // if ($request->hasFile('image_url')) {
        //     $imagePath = $request->file('image_url')->store('posts', 'public');
        // }
        if ($request->hasFile('image_url')) {

            //store in s3
            
            $path = $request->file('image_url')->store(
                'posts',
                's3'
            );
    
            if (!$path) {
                return response()->json([
                            'hasFile' => $request->hasFile('image_url'),
                            'path' => $path ?? null,
                            'url' => isset($path) ? Storage::disk('s3')->url($path) : null,
                        ]);
            }
            $imageUrl = Storage::disk('s3')->url($path);
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'image_url' => $imageUrl
        ]);

        return response()->json([
                'message' => 'Post created successfully',
                'post' => $post,
                'path' => $path
            ]);
    }
}
