<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('services')->delete();
        
        \DB::table('services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Bridal Hair',
                'icon' => 'Flower2',
                'description' => 'Elegant, long-lasting bridal styles for your special day.',
                'price' => 20000,
                'duration' => '1 hr',
                'status' => 'Active',
                'created_at' => '2025-05-13 07:02:37',
                'updated_at' => '2025-12-12 09:07:18',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Hair Coloring',
                'icon' => 'Brush',
                'description' => 'Vibrant colors, balayage, highlights, and more.',
                'price' => 30000,
                'duration' => '1 hr',
                'status' => 'Active',
                'created_at' => '2025-05-13 07:15:17',
                'updated_at' => '2025-08-22 08:50:58',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Hair Extensions',
                'icon' => 'Sparkles',
                'description' => 'Natural-looking length and volume with premium extensions.',
                'price' => 54250,
                'duration' => '4 hrs',
                'status' => 'Active',
                'created_at' => '2025-05-13 07:30:29',
                'updated_at' => '2025-10-04 07:10:11',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Cuts & Styling',
                'icon' => 'Scissors',
                'description' => 'Precision cuts and styling for every hair type.',
                'price' => 10500,
                'duration' => '1 hr',
                'status' => 'Inactive',
                'created_at' => '2025-05-13 07:32:08',
                'updated_at' => '2026-03-06 13:37:10',
            ),
            4 => 
            array (
                'id' => 8,
                'title' => 'Big Braids',
                'icon' => 'Flower',
                'description' => 'Wonderful big braids that makes you look dashing in the presence of friends and well-wishers',
                'price' => 14000,
                'duration' => '45 Mins',
                'status' => 'Active',
                'created_at' => '2025-05-14 08:32:29',
                'updated_at' => '2025-08-22 08:59:49',
            ),
        ));
        
        
    }
}