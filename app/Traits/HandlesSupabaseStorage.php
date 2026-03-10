<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

trait HandlesSupabaseStorage
{
    /**
     * Upload image to specific Supabase bucket
     */
    protected function uploadToSupabase($file, $bucket, $name = null, $processImage = true)
    {
        try {
            // Map bucket names to disk names
            $diskMap = [
                'stylists' => 'supabase_stylists',
                'blogs' => 'supabase_blogs',
                'testimonials' => 'supabase_testimonials',
                'hero_videos' => 'supabase_hero_videos',
                'styles' => 'supabase_styles',
                'signatures' => 'supabase_signatures',
            ];
            
            $diskName = $diskMap[$bucket] ?? 'supabase'; // Fallback to default
            
            $filename = $this->generateFileName($file, $name);
            
            if ($processImage && in_array($bucket, ['stylists', 'blogs', 'styles', 'signatures', 'testimonials'])) {
                // Process images with Intervention
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->scaleDown(800, 800);
                
                $tempPath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
                $image->toJpeg(85)->save($tempPath);
                
                $contents = file_get_contents($tempPath);
                Storage::disk($diskName)->put($bucket . '/' . $filename, $contents);
                
                unlink($tempPath);
            } else {
                // Direct upload for videos
                $contents = file_get_contents($file->getRealPath());
                Storage::disk($diskName)->put($bucket . '/' . $filename, $contents);
            }
            
            // Get public URL
            $url = Storage::disk($diskName)->url($bucket . '/' . $filename);
            
            return [
                'success' => true,
                'path' => $bucket . '/' . $filename,
                'url' => $url,
                'filename' => $filename,
                'bucket' => $bucket
            ];
            
        } catch (\Exception $e) {
            \Log::error('Supabase upload error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete image from Supabase
     */
    protected function deleteFromSupabase($path)
    {
        try {
            // Extract bucket from path (e.g., "stylists/filename.jpg" -> "stylists")
            $parts = explode('/', $path);
            $bucket = $parts[0];
            
            $diskMap = [
                'stylists' => 'supabase_stylists',
                'blogs' => 'supabase_blogs',
                'testimonials' => 'supabase_testimonials',
                'hero_videos' => 'supabase_hero_videos',
                'styles' => 'supabase_styles',
                'signatures' => 'supabase_signatures',
            ];
            
            $diskName = $diskMap[$bucket] ?? 'supabase';
            
            if (Storage::disk($diskName)->exists($path)) {
                Storage::disk($diskName)->delete($path);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Supabase delete error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get URL for a file
     */
    protected function getSupabaseUrl($path)
    {
        if (!$path) return null;
        
        $parts = explode('/', $path);
        $bucket = $parts[0];
        
        $diskMap = [
            'stylists' => 'supabase_stylists',
            'blogs' => 'supabase_blogs',
            'testimonials' => 'supabase_testimonials',
            'hero_videos' => 'supabase_hero_videos',
            'styles' => 'supabase_styles',
            'signatures' => 'supabase_signatures',
        ];
        
        $diskName = $diskMap[$bucket] ?? 'supabase';
        
        return Storage::disk($diskName)->url($path);
    }
    
    /**
     * Generate filename
     */
    protected function generateFileName($file, $name = null)
    {
        if ($name) {
            $slug = Str::slug(Str::limit($name, 30));
            return $slug . '-' . time() . '.jpg';
        }
        
        return time() . '_' . $file->getClientOriginalName();
    }
}