<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StylistsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stylists')->delete();
        
        \DB::table('stylists')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'Agosu-Vyon Omolola',
                'role' => 'Bridal Stylist Specialist',
                'image' => 'stylists/XQzPfwvzTIBzm9md2j3cYItn3OdS4GzQNMCWaUaW.jpg',
                'bio' => 'A test Bio for myself',
                'awards' => '["Test","Bridal Shower"]',
                'instagram' => '@omololaagosu',
                'email' => 'olola@gmail.com',
                'specializations' => '["Bridal","Color","Extension","Men\'s"]',
                'is_active' => 'true',
                'display_order' => 0,
                'created_at' => '2026-03-06 16:05:39',
                'updated_at' => '2026-03-06 16:20:34',
            ),
        ));
        
        
    }
}