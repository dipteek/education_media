<?php
// http://172.17.11.156:8000/api/profile/2
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'age' => 'required|integer',
            'education_type' => 'required|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->age = $request->age;
        $user->education_type = $request->education_type;
        $user->username = $request->username;

        if ($request->hasFile('profile_image')) {
            $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->save();

        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }


    public function login(Request $request)
{
    /*$credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        //$token = $request->user()->createToken($request->token_name);
        $token = $request->user()->createToken('auth_token')->plainTextToken;
//dd($token);
//dd($token);
        //$token = $user->createToken('auth_token')->plainTextToken;

        //$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user_id' => $user->id,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials',
    ], 401);*/
    // Validate incoming request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to authenticate the user
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Get the authenticated user
    $user = Auth::user();

    // Generate a token for the user
    $token = $request->user()->createToken('auth_token')->plainTextToken;

    // Return the token and user details
    return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'data' => [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],
    ]);
}



public function getUserProfileById($userId)
{
    $user = User::findOrFail($userId);
    
    $isFollowing = false;
    $authUser = Auth::user();
    
    if ($authUser) {
        // Check if the authenticated user is following this user
        $isFollowing = DB::table('followers')
            ->where('follower_id', $authUser->id)
            ->where('user_id', $user->id)
            ->exists();
    }
    
    $followersCount = DB::table('followers')
        ->where('user_id', $user->id)
        ->count();
    
    $followingCount = DB::table('followers')
        ->where('follower_id', $user->id)
        ->count();
    
    return response()->json([
        'success' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'gender' => $user->gender,
            'age' => $user->age,
            'education' => $user->education,
            'image' => $user->image,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'is_following' => $isFollowing
        ]
    ]);
}




public function followUser($userId)
{
    $authUser = Auth::user();
    
    if ($authUser->id == $userId) {
        return response()->json([
            'success' => false,
            'message' => 'You cannot follow yourself'
        ], 400);
    }
    
    $userToFollow = User::findOrFail($userId);
    
    if (!$authUser->following->contains($userId)) {
        $authUser->following()->attach($userId);
        return response()->json([
            'success' => true,
            'message' => 'You are now following ' . $userToFollow->username
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'You are already following this user'
    ], 400);
}

public function unfollowUser($userId)
{
    $authUser = Auth::user();
    $userToUnfollow = User::findOrFail($userId);
    
    if ($authUser->following->contains($userId)) {
        $authUser->following()->detach($userId);
        return response()->json([
            'success' => true,
            'message' => 'You have unfollowed ' . $userToUnfollow->username
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'You are not following this user'
    ], 400);
}



}
