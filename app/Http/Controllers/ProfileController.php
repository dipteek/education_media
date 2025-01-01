<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getProfile($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', $id)->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'profile_picture' => $user->profile_image,
                'bio' => $user->bio,
                'posts_count' => $posts->count(),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
            ],
            'posts' => $posts,
        ]);
    }

    public function getProfileforEdit($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    /*public function updateProfile(Request $request, $id)
    {
        //Log::info('Request Headers: ', $request->headers->all());
        //dd( $request->headers->all());
        $user = User::findOrFail($id);
        

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        //dd($request);

        // Update profile data
        $user->name = $request->name;
        $user->username = $request->username;
        $user->bio = $request->bio;
        $user->gender = $request->gender;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();
        

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ]);

        return response()->json($user, 302);
    }*/

    public function updateProfile(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validate incoming data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'bio' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female,other',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Update user details
    $user->name = $request->input('name');
    $user->username = $request->input('username');
    $user->bio = $request->input('bio', '');
    $user->gender = $request->input('gender');

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        $image = $request->file('profile_image');
        $imagePath = $image->store('profile_images', 'public');
        $user->profile_image = $imagePath;
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'user' => $user,
    ]);
}


    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
