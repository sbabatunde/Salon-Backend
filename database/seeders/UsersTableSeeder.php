<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Omolola Agosu',
                'email' => 'agosuomolola@gmail.com',
                'email_verified_at' => NULL,
                'role' => 'admin',
                'password' => '$2y$12$8JUZH9Ru26OFrSYwvOKq0OKhMug1/Nh0CGdLrvnNx4ASesDmXUeO2',
                'remember_token' => NULL,
                'created_at' => '2025-06-11 14:10:29',
                'updated_at' => '2025-06-11 14:10:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Tunde Salawu',
                'email' => 'salawubabatunde69@gmail.com',
                'email_verified_at' => '2025-06-12 14:58:02',
                'role' => 'staff',
                'password' => '$2y$12$6F.F1MwQHqUGAQj1.E1JNOCxDyqJGLz0ed2OmYRf/ar9wYyQeb2cO',
                'remember_token' => NULL,
                'created_at' => '2025-06-11 14:10:30',
                'updated_at' => '2025-06-11 14:10:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'email_verified_at' => NULL,
                'role' => 'user',
                'password' => '$2y$12$XoHj2A7olauI4FvhZ8l8TOwKz0jSG2CuOTFh9TwPGiMPOrE4WoU.G',
                'remember_token' => NULL,
                'created_at' => '2025-06-11 14:10:31',
                'updated_at' => '2025-06-11 14:10:31',
            ),
        ));
        
        
    }
}