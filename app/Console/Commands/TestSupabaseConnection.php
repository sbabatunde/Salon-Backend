<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestSupabaseConnection extends Command
{
    protected $signature = 'test:supabase';
    protected $description = 'Test Supabase storage connection';

    public function handle()
    {
        $this->info('Testing Supabase connection...');

        $config = config('filesystems.disks.supabase');

        $this->line('Configuration:');
        $this->line('  Key: ' . substr($config['key'], 0, 10) . '...');
        $this->line('  Bucket: ' . $config['bucket']);
        $this->line('  Endpoint: ' . $config['endpoint']);

        try {
            // Try to write a test file
            $testContent = 'test-' . time();
            Storage::disk('supabase')->put('test.txt', $testContent);

            // Read it back
            $content = Storage::disk('supabase')->get('test.txt');

            if ($content === $testContent) {
                $this->info('✅ Connection successful!');
                $this->line('URL: ' . Storage::disk('supabase')->url('test.txt'));
            } else {
                $this->error('❌ Data verification failed');
            }

            // Clean up
            Storage::disk('supabase')->delete('test.txt');
        } catch (\Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
        }
    }
}
