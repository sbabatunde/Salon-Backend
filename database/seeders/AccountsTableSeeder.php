<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounts')->delete();
        
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'client_id' => 1,
                'amount_paid' => 56000,
                'service_cost' => 45000,
                'material_cost' => 12000,
                'other_cost' => 4000,
                'payment_method' => 'bank_transfer',
                'notes' => 'Good client',
                'created_at' => '2026-01-09 14:46:39',
                'updated_at' => '2026-01-09 14:46:39',
                'total_cost' => 61000,
                'profit' => -5000,
            ),
        ));
        
        
    }
}