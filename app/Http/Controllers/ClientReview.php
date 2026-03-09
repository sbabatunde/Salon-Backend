<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientReview extends Controller
{

    public function index()
    {
        return Testimonial::where('submitted', true)
            ->latest()
            ->get();
    }

    public function createInvite(Request $request)
    {
        $request->validate(['client_id' => 'required|integer']);

        $testimonial = Testimonial::firstOrNew([
            'client_id' => $request->client_id,
            'submitted' => false,
        ]);

        if (!$testimonial->exists || $testimonial->token_created_at->diffInMinutes(now()) > 30) {
            $testimonial->token = Str::uuid();
            $testimonial->token_created_at = now();
            $testimonial->name = '';
            $testimonial->review = '';
            $testimonial->rating = 5;
            $testimonial->save();
        }
        $frontendUrl = config('app.frontend_url');
        return response()->json([
            'link' => $frontendUrl . "/testimonial/form/{$testimonial->token}",
            'expires_in_minutes' => 30 - $testimonial->token_created_at->diffInMinutes(now()),
        ]);
    }


    public function showForm($token)
    {
        $testimonial = Testimonial::where('token', $token)->firstOrFail();

        // Check token expiry (e.g., 30 minutes)
        if ($testimonial->token_created_at->diffInMinutes(now()) > 30) {
            return response()->json(['message' => 'This link has expired.'], 410);
        }

        return response()->json([
            'testimonial' => $testimonial,
        ]);
    }


    public function submit(Request $request, $token)
    {
        try {
            $testimonial = Testimonial::where('token', $token)->firstOrFail();

            if ($testimonial->submitted) {
                return response()->json([
                    'message' => 'This testimonial has already been submitted.'
                ], 422);
            }

            // More flexible validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'review' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5',
                // 'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('image')) {
                // Additional check to ensure it's actually an image
                $file = $request->file('image');
                $mime = $file->getMimeType();

                if (str_starts_with($mime, 'image/')) {
                    $path = $file->store('testimonials', 'public');
                    $data['image_url'] = Storage::url($path);
                } else {
                    return response()->json([
                        'message' => 'The uploaded file is not a valid image',
                        'errors' => ['image' => ['The file must be a valid image (jpeg, png, jpg, gif)']]
                    ], 422);
                }
            }

            $testimonial->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'review' => $data['review'],
                'rating' => $data['rating'],
                'image_url' => $data['image_url'] ?? null,
                'submitted' => true,
                'submitted_at' => now()
            ]);

            return response()->json([
                'message' => 'Testimonial submitted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Server error:', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
