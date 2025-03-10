<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YouTubeVideo extends Controller
{
    protected $youtubeService;

    public function __construct(YouTubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function getEducationalVideos(Request $request)
    {
        $query = $request->query('q', 'education'); // Default search: "education"
        $videos = $this->youtubeService->getEducationalVideos($query);

        return response()->json($videos);
    }
}
