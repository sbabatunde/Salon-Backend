<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('inventories')->delete();
        
        \DB::table('inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'product' => 'Shampoo',
                'stock' => 2,
                'acquiredOn' => '2025-05-13',
                'price' => 70000,
                'remark' => 'This is used for cleansing customers hairs and i got it from Mummy Adeola.',
                'unit' => 'Litres',
                'status' => 0,
                'created_at' => '2025-05-13 14:43:21',
                'updated_at' => '2025-05-14 12:35:55',
            ),
            1 => 
            array (
                'id' => 2,
                'product' => 'Conditioner',
                'stock' => 45,
                'acquiredOn' => '2025-05-08',
                'price' => 70000,
                'remark' => 'Got it from Awoyaya store.',
                'unit' => 'Litres',
                'status' => 1,
                'created_at' => '2025-05-14 07:27:28',
                'updated_at' => '2025-05-16 09:04:09',
            ),
            2 => 
            array (
                'id' => 3,
                'product' => 'Hair Straightner',
                'stock' => 2,
                'acquiredOn' => '2025-05-07',
                'price' => 15000,
                'remark' => 'None',
                'unit' => 'pieces',
                'status' => 0,
                'created_at' => '2025-05-14 08:11:28',
                'updated_at' => '2025-05-14 08:11:28',
            ),
        ));
        
        
    }
}