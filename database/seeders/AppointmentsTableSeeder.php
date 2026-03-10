<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('appointments')->delete();
        
        \DB::table('appointments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Florence Chioma',
                'service' => 'Haircut',
                'email' => 'floelu@gmail.com',
                'phone' => '08012553879,09033451212',
                'date' => '2025-05-06',
                'time' => '16:30',
                'notes' => 'I would love to have a very beautifule hair extension for my wedding',
                'status' => 'Completed',
                'created_at' => '2025-05-06 14:31:24',
                'updated_at' => '2026-01-09 14:38:10',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Adewunmi Alade',
                'service' => 'Treatments',
                'email' => 'aladewunmi@yahoo.com',
                'phone' => '0703445566532',
                'date' => '2025-05-14',
                'time' => '09:30',
                'notes' => 'I was told you allow installmental payments, I hope that\'s okay though.',
                'status' => 'Completed',
                'created_at' => '2025-05-07 07:31:32',
                'updated_at' => '2026-03-06 14:07:36',
            ),
        ));
        
        
    }
}