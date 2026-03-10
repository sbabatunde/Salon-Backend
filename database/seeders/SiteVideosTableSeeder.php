<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteVideosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('site_videos')->delete();
        
        \DB::table('site_videos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Shola\'s Wedding',
                'description' => 'A short wedding shoot',
                'status' => 'active',
                'video_path' => 'hero_videos/ZsA2XstcFAxNIteK9IUnyM7ce3wBiSMwEoKg4XTK.mp4',
                'video_url' => NULL,
                'created_at' => '2025-06-05 11:38:28',
                'updated_at' => '2025-06-05 11:38:28',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'An online very cute wedding entrance',
                'description' => 'A cute wedding entrance that captured my heart.',
                'status' => 'active',
                'video_path' => NULL,
                'video_url' => 'https://www.youtube.com/watch?v=VSsvg7AjlfU',
                'created_at' => '2025-06-05 11:39:48',
                'updated_at' => '2025-09-22 08:05:34',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Mr Joshua\'s Wedding',
                'description' => 'A short clip of Mr Josh and his wife at their wedding hotel room.',
                'status' => 'active',
                'video_path' => 'hero_videos/t2MhQ26MrXMhJ8WUw3wJ0zgu1jTTx97QYcZgFbqq.mp4',
                'video_url' => NULL,
                'created_at' => '2025-06-05 12:33:43',
                'updated_at' => '2025-10-04 06:52:56',
            ),
        ));
        
        
    }
}