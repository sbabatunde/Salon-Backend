<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Styles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Style extends Controller
{
    public function create(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:styles,name',
                'category' => 'required|string|max:255',
                'description' => 'required|string',
                'tag' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validatedData = $validator->validated();

            // Upload and process image
            $imagePath = $this->processAndStoreImage($request->file('image'), $validatedData['name']);

            if (!$imagePath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process image',
                ], 500);
            }

            // Create style
            $style = Styles::create([
                'name' => $validatedData['name'],
                'category' => $validatedData['category'],
                'description' => $validatedData['description'],
                'tag' => $validatedData['tag'],
                'image' => $imagePath,
            ]);

            Log::info('Style created successfully', ['style_id' => $style->id]);

            return response()->json([
                'success' => true,
                'message' => 'Style created successfully.',
                'data' => $style,
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error creating style: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
            ], 500);
        } catch (Exception $e) {
            Log::error('Error creating style: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the style.',
            ], 500);
        }
    }

    public function show()
    {
        try {
            $styles = Styles::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $styles,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching styles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch styles.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $style = Styles::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:styles,name,' . $id,
                'category' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'tag' => 'sometimes|required|string|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'sometimes|required|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validatedData = $validator->validated();

            // Handle image update if provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $newImagePath = $this->processAndStoreImage($request->file('image'), $validatedData['name'] ?? $style->name);

                if ($newImagePath) {
                    // Delete old image
                    $this->deleteImageFile($style->image);
                    $validatedData['image'] = $newImagePath;
                }
            }

            $style->update($validatedData);

            Log::info('Style updated successfully', ['style_id' => $style->id]);

            return response()->json([
                'success' => true,
                'message' => 'Style updated successfully!',
                'data' => $style,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Style not found.',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error updating style: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $style = Styles::findOrFail($id);

            // Delete the image file
            $this->deleteImageFile($style->image);

            // Delete the database record
            $style->delete();

            Log::info('Style deleted successfully', ['style_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Style and associated image deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Style not found.',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error deleting style: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting.',
            ], 500);
        }
    }

    /**
     * Process and store uploaded image with Intervention v3.x
     */
    private function processAndStoreImage($imageFile, $name)
    {
        try {
            // Create slug from name for filename
            $slug = Str::slug(Str::limit($name, 30));
            $filename = $slug . '-' . time() . '.jpg';

            $savePath = public_path('assets/styles');

            // Create directory if it doesn't exist
            if (!File::exists($savePath)) {
                File::makeDirectory($savePath, 0755, true, true);
            }

            // Process image using Intervention v3.x - CORRECT SYNTAX
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($imageFile);

            // Resize image maintaining aspect ratio with maximum dimensions
            $image->scaleDown(800, 800);

            // Encode and save as JPEG with good quality
            $image->toJpeg(85)->save($savePath . '/' . $filename);

            Log::info('Image processed successfully', [
                'filename' => $filename,
                'path' => $savePath . '/' . $filename,
                'file_exists' => file_exists($savePath . '/' . $filename)
            ]);

            return '/assets/styles/' . $filename;
        } catch (Exception $e) {
            Log::error('Image processing error: ' . $e->getMessage());
            Log::error('Image processing trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Simple image storage without processing (fallback)
     */
    private function storeImageSimple($imageFile, $name)
    {
        try {
            // Create slug from name for filename
            $slug = Str::slug(Str::limit($name, 30));
            $extension = $imageFile->getClientOriginalExtension();
            $filename = $slug . '-' . time() . '.' . $extension;

            $savePath = public_path('assets/styles');

            // Create directory if it doesn't exist
            if (!File::exists($savePath)) {
                File::makeDirectory($savePath, 0755, true, true);
            }

            // Simply move the file without processing
            $imageFile->move($savePath, $filename);

            Log::info('Image stored simply', [
                'filename' => $filename,
                'path' => $savePath . '/' . $filename
            ]);

            return '/assets/styles/' . $filename;
        } catch (Exception $e) {
            Log::error('Simple image storage error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image file from storage
     */
    private function deleteImageFile($imagePath)
    {
        try {
            if ($imagePath && File::exists(public_path($imagePath))) {
                File::delete(public_path($imagePath));
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::error('Error deleting image file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get style by ID
     */
    public function showById($id)
    {
        try {
            $style = Styles::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $style,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Style not found.',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error fetching style: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch style.',
            ], 500);
        }
    }

    /**
     * Test image upload with both methods
     */
    public function testImageUpload(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image provided'
                ], 422);
            }

            $imageFile = $request->file('image');

            // Test Intervention Image processing
            $interventionPath = $this->processAndStoreImage($imageFile, 'test-image');

            // Test simple storage
            $simplePath = $this->storeImageSimple($request->file('image'), 'test-image-simple');

            return response()->json([
                'success' => true,
                'intervention_result' => $interventionPath ? 'Success' : 'Failed',
                'intervention_path' => $interventionPath,
                'simple_result' => $simplePath ? 'Success' : 'Failed',
                'simple_path' => $simplePath,
                'file_info' => [
                    'original_name' => $imageFile->getClientOriginalName(),
                    'size' => $imageFile->getSize(),
                    'mime_type' => $imageFile->getMimeType(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
