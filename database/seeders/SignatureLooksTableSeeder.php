<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SignatureLooksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('signature_looks')->delete();
        
        \DB::table('signature_looks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Elegant bridal updo',
                'tag' => 'Classic',
                'image' => 'signatures/yYFcGJTUBBvp4N4wiZ5Dnh67Usto7YaaXsak44rj.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:22:49',
                'updated_at' => '2025-06-11 09:01:05',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Rich balayage color',
                'tag' => 'Trendy',
                'image' => 'signatures/52R9hbPV6Modt2xuuRgmSCbMAUUIAO5px2cbOdHI.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:23:56',
                'updated_at' => '2025-06-11 09:01:04',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Seamless hair extensions',
                'tag' => 'Trendy',
                'image' => 'signatures/PswBZ2U0fK0U2XcRigonhFzEWNtisBO0fLNfEYYW.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:25:59',
                'updated_at' => '2025-06-11 09:01:03',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Glamorous event styling',
                'tag' => 'Classic',
                'image' => 'signatures/2rPQgPlXvFCv3VdAYo1174yk7Aqq6aAigYvC7Ily.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:33:42',
                'updated_at' => '2025-06-11 09:01:02',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Classic bridal waves',
                'tag' => 'Classic',
                'image' => 'signatures/vE21nIivhD21rcI1gmrQoEFJbJpb9vQFMKfRRtkO.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:34:06',
                'updated_at' => '2025-06-11 09:01:02',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Vibrant hair coloring',
                'tag' => 'Trendy',
                'image' => 'signatures/Tpxd31eNl08Nm24tOGoJH4msEqZCggM7t8UQo6Ut.jpg',
                'status' => 'active',
                'created_at' => '2025-06-11 08:34:33',
                'updated_at' => '2025-06-11 08:43:58',
            ),
        ));
        
        
    }
}