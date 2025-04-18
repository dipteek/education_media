<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Log;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /*public function store(Request $request)
    {
        //dd("hii");
        //dd($request);
        $request->validate([
            'caption' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $imagePath = $request->file('image')->store('post_images', 'public');
        //dd($imagePath);

        $post = Post::create([
            'user_id' => $request->user_id,
            'image' => $imagePath,
            'caption' => $request->caption,
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'post' => $post,
        ]);

        return response()->json(['success' => false,
        'aaaa' => $post], 405);
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,mp4,pdf|max:90480',
        ]);

        if (!$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded',
            ], 400);
        }

        $filePath = $request->file('file')->store('uploads', 'public');

        if (!$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed',
            ], 500);
        }

        $post = Post::create([
            'user_id' => $request->user_id,
            'caption' => $request->caption,
            'file_path' => $filePath,
        ]);

        //Log::info('🔹 Received Request: ', $request->all());
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'post' => $post,
        ]);
        /*
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'post' => $post,
        ], 201); */
    }



    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function toggleLike(Post $post)
    {
        //$user = auth()->user();
        $user = Auth::user();
        
        // Check if the user already liked this post
        $existingLike = Like::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->first();
        
        if ($existingLike) {
            // If already liked, remove the like
            $existingLike->delete();
            return response()->json([
                'success' => true,
                'liked' => false,
                'likes_count' => $post->likes()->count()
            ]);
        } else {
            // If not liked, add a like
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            
            return response()->json([
                'success' => true,
                'liked' => true,
                'likes_count' => $post->likes()->count()
            ]);
        }
    }


    public function getPosts()
    {
        $posts = Post::with('user')->inRandomOrder()->get(); 

        $user = Auth::user();

        if ($user) {
            foreach ($posts as $post) {
                $post->is_liked = $post->isLikedBy($user->id);
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }


    public function getLikeStatus(Post $post)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'is_liked' => false
            ]);
        }
        
        $isLiked = $post->isLikedBy($user->id);
        
        return response()->json([
            'is_liked' => $isLiked,
            'likes_count' => $post->likes()->count()
        ]);
    }

}
