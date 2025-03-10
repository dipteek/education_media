<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Course::select('id', 'title', 'description', 'image')->paginate(10));
    }

    public function getByUsers($user_id) {
        $courses = Course::where('user_id',$user_id)->select('id', 'title', 'description', 'image')->get();
        return response()->json($courses);
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

     public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);

        //return response()->json(['message' => 'Course created failed', 'course' => $course], 500);
    }

    
    
     /*public function store(Request $request)
     {
         $validated = $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'required|string',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
         ]);
 
         if ($request->hasFile('image')) {
             $path = $request->file('image')->store('courses', 'public');
             $validated['image'] = $path;
         }
 
         $course = Course::create($validated);
 
         return response()->json($course, 201);
     }*/

    

    public function show($id)
    {
        $course = Course::select('id', 'title', 'description', 'image')->findOrFail($id);
        return response()->json($course);
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
