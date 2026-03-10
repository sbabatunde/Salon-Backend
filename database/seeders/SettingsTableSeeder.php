<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'businessName' => 'Precious Hairmpire Salon',
                'email' => 'precioushairmpire@gmail.com',
                'phone' => '+23461514604',
                'address' => 'Lagos Villa, Lagos',
                'googleMapAddress' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.546369871023!2d3.379205214753149!3d6.524379324287253!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8c3e5a1c0b2d%3A0x2d6e0b6b6b6b6b6b!2sLagos!5e0!3m2!1sen!2sng!4v1681234567890!5m2!1sen!2sng',
                'facebook' => '_omololaagosu',
                'instagram' => '@omololaagosu',
                'x' => '#omololaagosu',
                'linkedIn' => 'https://linkedIn-32221111200.omolola-agosu',
                'created_at' => '2025-05-13 07:40:39',
                'updated_at' => '2026-03-06 14:02:14',
            ),
        ));
        
        
    }
}