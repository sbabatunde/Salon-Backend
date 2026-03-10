<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Styles;
use App\Models\Stylist;
use App\Models\SiteVideo;
use App\Models\Testimonial;
use App\Models\SignatureLook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToSupabase extends Command
{
    protected $signature = 'migrate:images-to-supabase';
    protected $description = 'Migrate all existing images from local storage to Supabase Storage';

    public function handle()
    {
        $this->info('Starting image migration to Supabase Storage...');

        // Test Supabase connection first
        if (!$this->testSupabaseConnection()) {
            return 1;
        }

        // Migrate stylist images
        $this->migrateStylistImages();

        // Migrate blog images
        $this->migrateBlogImages();

        // Migrate testimonial images
        $this->migrateTestimonialImages();

        // Migrate style images
        $this->migrateStyleImages();

        // Migrate signature look images
        $this->migrateSignatureImages();

        // Migrate hero videos
        $this->migrateHeroVideos();

        $this->info('✅ Image migration completed!');
        return 0;
    }

    protected function testSupabaseConnection()
    {
        $this->info('Testing Supabase connection...');

        try {
            // Try to list files in the bucket (will fail if connection is bad)
            Storage::disk('supabase')->files('/');
            $this->info('✅ Supabase connection successful');
            return true;
        } catch (\Exception $e) {
            $this->error('❌ Supabase connection failed: ' . $e->getMessage());
            $this->warn('Please check your SUPABASE_STORAGE_KEY, SUPABASE_STORAGE_BUCKET, and SUPABASE_STORAGE_ENDPOINT in .env');
            return false;
        }
    }

    protected function migrateStylistImages()
    {
        $this->info('Migrating stylist images...');

        $stylists = Stylist::whereNotNull('image')->get();

        if ($stylists->isEmpty()) {
            $this->warn('  No stylist images found');
            return;
        }

        foreach ($stylists as $stylist) {
            $localPath = storage_path('app/public/' . $stylist->image);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($stylist->image);

                Storage::disk('supabase')->put('stylists/' . $filename, $contents);

                $this->line("  ✓ Migrated stylist image: {$filename}");
            } else {
                $this->warn("  ⚠ Image not found: {$localPath}");
            }
        }
    }

    protected function migrateBlogImages()
    {
        $this->info('Migrating blog images...');

        $blogs = Blog::whereNotNull('image')->get();

        if ($blogs->isEmpty()) {
            $this->warn('  No blog images found');
            return;
        }

        foreach ($blogs as $blog) {
            // Handle both storage paths
            $localPath = str_starts_with($blog->image, '/assets/')
                ? public_path($blog->image)
                : storage_path('app/public/' . $blog->image);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($blog->image);

                Storage::disk('supabase')->put('blogs/' . $filename, $contents);

                $this->line("  ✓ Migrated blog image: {$filename}");
            } else {
                $this->warn("  ⚠ Image not found: {$localPath}");
            }
        }
    }

    protected function migrateTestimonialImages()
    {
        $this->info('Migrating testimonial images...');

        $testimonials = Testimonial::whereNotNull('image_url')->get();

        if ($testimonials->isEmpty()) {
            $this->warn('  No testimonial images found');
            return;
        }

        foreach ($testimonials as $testimonial) {
            // Extract path from URL
            $path = str_replace('/storage/', '', parse_url($testimonial->image_url, PHP_URL_PATH));
            $localPath = storage_path('app/public/' . $path);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($path);

                Storage::disk('supabase')->put('testimonials/' . $filename, $contents);

                $this->line("  ✓ Migrated testimonial image: {$filename}");
            } else {
                $this->warn("  ⚠ Image not found: {$localPath}");
            }
        }
    }

    protected function migrateStyleImages()
    {
        $this->info('Migrating style images...');

        $styles = Styles::whereNotNull('image')->get();

        if ($styles->isEmpty()) {
            $this->warn('  No style images found');
            return;
        }

        foreach ($styles as $style) {
            $localPath = public_path($style->image);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($style->image);

                Storage::disk('supabase')->put('styles/' . $filename, $contents);

                $this->line("  ✓ Migrated style image: {$filename}");
            } else {
                $this->warn("  ⚠ Image not found: {$localPath}");
            }
        }
    }

    protected function migrateSignatureImages()
    {
        $this->info('Migrating signature look images...');

        $signatures = SignatureLook::whereNotNull('image')->get();

        if ($signatures->isEmpty()) {
            $this->warn('  No signature look images found');
            return;
        }

        foreach ($signatures as $signature) {
            $localPath = storage_path('app/public/' . $signature->image);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($signature->image);

                Storage::disk('supabase')->put('signatures/' . $filename, $contents);

                $this->line("  ✓ Migrated signature image: {$filename}");
            } else {
                $this->warn("  ⚠ Image not found: {$localPath}");
            }
        }
    }

    protected function migrateHeroVideos()
    {
        $this->info('Migrating hero videos...');

        $videos = SiteVideo::whereNotNull('video_path')->get();

        if ($videos->isEmpty()) {
            $this->warn('  No hero videos found');
            return;
        }

        foreach ($videos as $video) {
            $localPath = storage_path('app/public/' . $video->video_path);

            if (file_exists($localPath)) {
                $contents = file_get_contents($localPath);
                $filename = basename($video->video_path);

                Storage::disk('supabase')->put('hero_videos/' . $filename, $contents);

                $this->line("  ✓ Migrated hero video: {$filename}");
            } else {
                $this->warn("  ⚠ Video not found: {$localPath}");
            }
        }
    }
}
