<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('YOUTUBE_API_KEY');
    }

/*public function getEducationalVideos($query = '', $maxResults = 1)
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'videoCategoryId' => 27, // Education category
            'maxResults' => $maxResults,
            'order' => 'relevance',
            'safeSearch' => 'strict',
            'key' => $this->apiKey,
        ]);

        //return $response->json();

        $videos = $response->json();

    // Debugging: Print the full API response
    Log::info('YouTube API Response: ' . json_encode($videos));

    if (!isset($videos['items'])) {
        return []; // If no items found, return empty array
    }

    return $videos;
    }
}*/

public function getEducationalVideos($query = '', $maxResults = 10)
{
    $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
        'part' => 'snippet',
        'q' => $query . ' education', // Append 'education' to the query
        'type' => 'video',
        'videoCategoryId' => 27, // Education category
        'maxResults' => $maxResults,
        'order' => 'relevance',
        'safeSearch' => 'strict',
        'key' => $this->apiKey,
    ]);

    $videos = $response->json();

    // Debugging: Print the full API response
    Log::info('YouTube API Response: ' . json_encode($videos));

    if (!isset($videos['items'])) {
        return []; // If no items found, return empty array
    }

    return $videos;
}
}

    // public function getEducationalVideos($query = '', $maxResults = 1)
    // {
    //     // Step 1: Search for videos using query
    //     $searchResponse = Http::get('https://www.googleapis.com/youtube/v3/search', [
    //         'part' => 'snippet',
    //         'q' => $query,
    //         'type' => 'video',
    //         'maxResults' => $maxResults * 2, // Fetch more results to filter later
    //         'order' => 'relevance',
    //         'safeSearch' => 'strict',
    //         'key' => $this->apiKey,
    //     ]);

    //     $searchResults = $searchResponse->json();
    //     Log::info('YouTube API Search Response: ' . json_encode($searchResults));

    //     if (!isset($searchResults['items'])) {
    //         return []; // No items found
    //     }

    //     // Extract video IDs
    //     $videoIds = collect($searchResults['items'])->pluck('id.videoId')->filter()->implode(',');

    //     // Step 2: Get video details and filter by category
    //     if (!$videoIds) {
    //         return [];
    //     }

    //     $videosResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', [
    //         'part' => 'snippet',
    //         'id' => $videoIds,
    //         'key' => $this->apiKey,
    //     ]);

    //     $videos = $videosResponse->json();
    //     Log::info('YouTube API Videos Response: ' . json_encode($videos));

    //     if (!isset($videos['items'])) {
    //         return [];
    //     }

    //     // Filter only videos with category 27 (Education)
    //     $educationalVideos = collect($videos['items'])->filter(function ($video) {
    //         return $video['snippet']['categoryId'] == '27';
    //     })->values()->all();

    //     return $educationalVideos;
    // }

