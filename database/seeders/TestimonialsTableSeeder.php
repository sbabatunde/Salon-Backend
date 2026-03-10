<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestimonialsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('testimonials')->delete();
        
        \DB::table('testimonials')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Ada O.',
                'review' => 'I absolutely love my new hairstyle! The stylist listened carefully to what I wanted and delivered beyond my expectations. The atmosphere was so welcoming, and I left feeling confident and beautiful. Highly recommend Precious Hairmpire for anyone looking to transform their look!',
                'image_url' => '/storage/testimonials/I2a3K8lCXhpWi4VIjREfP7eXVDnPXIp906IqBOBy.jpg',
                'rating' => 5,
                'token' => 'a8a32584-3b78-4081-b197-cca6fca272c2',
                'submitted' => 1,
                'token_created_at' => '2025-06-11 12:12:55',
                'client_id' => 1,
                'created_at' => '2025-06-11 11:26:22',
                'updated_at' => '2025-06-11 12:23:01',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Chioma Florence',
                'review' => 'Amazing service and outstanding skill. The team is attentive, the chairside manner is warm, and the results exceed my expectations every time.',
                'image_url' => NULL,
                'rating' => 5,
                'token' => 'eb144ef5-cd45-48f2-a06b-ec8fa0b9a650',
                'submitted' => 1,
                'token_created_at' => '2025-08-22 10:26:10',
                'client_id' => 1,
                'created_at' => '2025-08-22 09:55:42',
                'updated_at' => '2025-08-22 11:11:32',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '',
                'review' => '',
                'image_url' => NULL,
                'rating' => 5,
                'token' => 'c5f76fc5-f801-43e9-850d-4c6914e9fcad',
                'submitted' => 0,
                'token_created_at' => '2026-03-06 14:08:53',
                'client_id' => 1,
                'created_at' => '2025-11-28 14:15:41',
                'updated_at' => '2026-03-06 14:08:53',
            ),
        ));
        
        
    }
}