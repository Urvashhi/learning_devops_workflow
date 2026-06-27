<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
     return Post::with('user')
        ->where('user_id', Auth::id())
        ->latest()
        ->take(10)
        ->get();
// dd("gffggf");
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

         $imagePath = null;

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('posts', 'public');
        }
        // if ($request->hasFile('image')) {
        //     // $path = $request->file('image')->store(
        //     //     'posts',
        //     //     's3'
        //     // );

        //     // $imageUrl = Storage::disk('s3')->url($path);
        // }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'image_url' => $imagePath
        ]);

        return response()->json([
                'message' => 'Post created successfully',
                'post' => $post

            ]);
    }
}
