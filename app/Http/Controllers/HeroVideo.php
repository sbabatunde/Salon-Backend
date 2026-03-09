<?php

namespace App\Http\Controllers;

use App\Models\SiteVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroVideo extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'id' => 'nullable|exists:site_videos,id', // Uncomment if you want to validate 'id'
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:512000', // max 500MB
            'url' => 'nullable|url',
        ]);

        // Check if creating new or updating existing
        $heroVideo = (isset($validated['id']) && $validated['id']) ? SiteVideo::findOrFail($validated['id']) : new SiteVideo();

        $heroVideo->title = $validated['title'];
        $heroVideo->description = $validated['description'] ?? '';
        // $heroVideo->status = $validated['status'];

        // Enforce either video upload or URL
        $hasVideoFile = $request->hasFile('video');
        $hasUrl = isset($validated['url']) && !empty($validated['url']);

        if (!$request->filled('id') && !$hasVideoFile && !$hasUrl) {
            return response()->json(['message' => 'Please upload a video file or provide a video URL.'], 422);
        }

        // Handle uploaded video file if exists
        if ($hasVideoFile) {
            // Delete old file if editing and exists
            if ($heroVideo->video_path && Storage::disk('public')->exists($heroVideo->video_path)) {
                Storage::disk('public')->delete($heroVideo->video_path);
            }

            $path = $request->file('video')->store('hero_videos', 'public');
            $heroVideo->video_path = $path;
            $heroVideo->video_url = null; // clear URL if uploading a file
        } elseif ($hasUrl) {
            // Use the URL provided
            // Delete old file if exists
            if ($heroVideo->video_path && Storage::disk('public')->exists($heroVideo->video_path)) {
                Storage::disk('public')->delete($heroVideo->video_path);
            }

            $heroVideo->video_url = $validated['url'];
            $heroVideo->video_path = null; // clear uploaded file reference
        }

        $heroVideo->save();

        return response()->json([
            'success' => true,
            'message' => $request->filled('id') ? 'Hero video updated successfully' : 'Hero video added successfully',
            'data' => $heroVideo,
        ]);
    }

    public function index()
    {
        $videos = SiteVideo::orderByDesc('created_at')->get();
        return response()->json([
            'success' => true,
            'data' => $videos,
        ]);
    }

    public function destroy($id)
    {
        $video = SiteVideo::findOrFail($id);
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }
        $video->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function toggleStatus($id)
    {
        $video = SiteVideo::findOrFail($id);
        $video->status = $video->status === 'active' ? 'inactive' : 'active';
        $video->save();
        $videos = SiteVideo::orderByDesc('created_at')->get();
        return response()->json([
            'success' => true,
            'data' => $videos,
        ]);
    }

    public function getActiveVideos()
    {
        $videos = SiteVideo::where('status', 'active')->get(); // or where('status', 'active')
        return response()->json($videos);
    }
}
