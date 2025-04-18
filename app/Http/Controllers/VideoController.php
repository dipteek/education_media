<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller {
    
    public function store(Request $request) {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:1002400', 
        ]);

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');

            $video = Video::create([
                'course_id' => $request->course_id,
                'user_id' => $request->user_id, 
                'title' => $request->title,
                'video_path' => $videoPath,
            ]);

            return response()->json(['message' => 'Video uploaded successfully', 'video' => $video], 201);
        }

        return response()->json(['error' => 'Video upload failed'], 400);
    }

    public function index($course_id) {
        // dd("ff");
        $videos = Video::where('course_id', $course_id)->get();
        return response()->json(['videos' => $videos]);

        //->with('user')
    }

  /*  public function index() {
        dd("ff");
        

        //->with('user')
    }*/

}
