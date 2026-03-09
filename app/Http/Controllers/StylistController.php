<?php

namespace App\Http\Controllers;

use App\Models\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StylistController extends Controller
{
    // Get all active stylists for frontend
    // In StylistController.php, update the index method:

    public function index()
    {
        try {
            $stylists = Stylist::active()->get()->map(function ($stylist) {
                // Ensure awards and specializations are arrays
                $awards = $stylist->awards;
                if (is_string($awards)) {
                    $awards = json_decode($awards, true) ?? [];
                }

                $specializations = $stylist->specializations;
                if (is_string($specializations)) {
                    $specializations = json_decode($specializations, true) ?? [];
                }

                // Fix image URL - use asset() helper
                $imageUrl = null;
                if ($stylist->image) {
                    // If image is stored in public/storage/stylists/
                    $imageUrl = asset('storage/' . $stylist->image);

                    // Alternative: if image is stored in public/uploads/stylists/
                    // $imageUrl = asset('uploads/stylists/' . $stylist->image);
                }

                $imageUrl = $stylist->image
                    ? asset('storage/' . $stylist->image)
                    : null;

                return [
                    'id' => $stylist->id,
                    'name' => $stylist->name,
                    'role' => $stylist->role,
                    'image' => $imageUrl, // Use the fixed URL
                    'bio' => $stylist->bio,
                    'image' => $imageUrl, // This will be full URL like http://localhost:8000/storage/stylists/xxx.jpg
                    'awards' => $awards ?? [],
                    'social' => [
                        'instagram' => $stylist->instagram,
                        'email' => $stylist->email
                    ],
                    'specializations' => $specializations ?? []
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stylists
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching stylists: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stylists'
            ], 500);
        }
    }

    // Admin: Get all stylists (for management)
    public function adminIndex()
    {
        try {
            $stylists = Stylist::orderBy('display_order')->get();
            return response()->json([
                'success' => true,
                'data' => $stylists
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stylists'
            ], 500);
        }
    }

    // Admin: Store new stylist
    // In StylistController.php, update the store and update methods:

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'bio' => 'required|string',
            'awards' => 'nullable|json', // Changed from array to json
            'instagram' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'specializations' => 'nullable|json', // Changed from array to json
            'display_order' => 'nullable|integer'
        ]);

        try {
            $data = $request->except('image');

            // Decode JSON strings to arrays for storage
            if ($request->has('awards') && is_string($request->awards)) {
                $data['awards'] = json_decode($request->awards, true);
            }

            if ($request->has('specializations') && is_string($request->specializations)) {
                $data['specializations'] = json_decode($request->specializations, true);
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('stylists', 'public');
            }

            $stylist = Stylist::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Stylist created successfully',
                'data' => $stylist
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create stylist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin: Update stylist
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'role' => 'sometimes|string|max:255',
                'image' => 'nullable|image|max:5120', // 5MB max
                'bio' => 'sometimes|string',
                'instagram' => 'nullable|string|max:255',
                'email' => 'sometimes|email|max:255',
                'is_active' => 'sometimes|boolean',
                'display_order' => 'nullable|integer'
            ]);

            $stylist = Stylist::findOrFail($id);
            $data = $request->except('image', '_method');

            // Handle awards array - Laravel will parse awards[0], awards[1] etc.
            if ($request->has('awards')) {
                $awards = $request->input('awards');
                // If it's already an array, use it; if it's a string, try to decode
                if (is_array($awards)) {
                    $data['awards'] = $awards;
                } elseif (is_string($awards)) {
                    $data['awards'] = json_decode($awards, true) ?? [];
                } else {
                    $data['awards'] = [];
                }
            }

            // Handle specializations array
            if ($request->has('specializations')) {
                $specializations = $request->input('specializations');
                if (is_array($specializations)) {
                    $data['specializations'] = $specializations;
                } elseif (is_string($specializations)) {
                    $data['specializations'] = json_decode($specializations, true) ?? [];
                } else {
                    $data['specializations'] = [];
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($stylist->image) {
                    Storage::disk('public')->delete($stylist->image);
                }
                $data['image'] = $request->file('image')->store('stylists', 'public');
            }

            $stylist->update($data);

            // Return the updated stylist with proper image URL
            $stylistData = $stylist->toArray();
            $stylistData['image'] = $stylist->image ? asset('storage/' . $stylist->image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Stylist updated successfully',
                'data' => $stylistData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Stylist update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stylist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin: Delete stylist
    public function destroy($id)
    {
        try {
            $stylist = Stylist::findOrFail($id);

            // Delete image
            if ($stylist->image) {
                Storage::disk('public')->delete($stylist->image);
            }

            $stylist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Stylist deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stylist'
            ], 500);
        }
    }

    // Admin: Update display order
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:stylists,id',
            'orders.*.display_order' => 'required|integer'
        ]);

        try {
            foreach ($request->orders as $order) {
                Stylist::where('id', $order['id'])->update(['display_order' => $order['display_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order'
            ], 500);
        }
    }
}
