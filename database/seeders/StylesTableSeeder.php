<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StylesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('styles')->delete();
        
        \DB::table('styles')->insert(array (
            0 => 
            array (
                'id' => 7,
                'name' => 'Balayage Highlight',
                'description' => 'This is an elegant style for you alone and your friends',
                'image' => '/assets/styles/-1748871948.png',
                'category' => 'Colouring',
                'tag' => 'Trendy',
                'status' => 'Active',
                'created_at' => '2025-05-16 06:50:03',
                'updated_at' => '2025-06-02 12:45:49',
            ),
            1 => 
            array (
                'id' => 8,
                'name' => 'Box Braids with Beads',
                'description' => 'Protective style with medium-length box braids, finished with decorative beads for a trendy, bold look.',
                'image' => '/assets/styles/-1748873694.png',
                'category' => 'Braiding',
                'tag' => 'braids, protective, beads',
                'status' => 'Active',
                'created_at' => '2025-05-16 06:57:51',
                'updated_at' => '2025-06-02 13:14:55',
            ),
            2 => 
            array (
                'id' => 9,
                'name' => 'Silk Press',
                'description' => 'A heat styling method that transforms natural curls into a smooth, straight finish without chemicals. Ideal for a sleek temporary change.',
                'image' => '/assets/styles/-1748872497.png',
                'category' => 'Natural Hair Styling',
                'tag' => 'natural, silk press, straightening',
                'status' => 'Active',
                'created_at' => '2025-05-16 06:58:53',
                'updated_at' => '2025-06-02 12:54:59',
            ),
            3 => 
            array (
                'id' => 10,
                'name' => 'Loose Curls Blowout',
                'description' => 'Bouncy, soft curls created with a round brush and blow dryer, great for volume and movement.\\nTag: curls, blowout, volume',
                'image' => '/assets/styles/Loose Curls Blowout-1747382383.png',
                'category' => 'Blow Dry Styles',
                'tag' => 'curls, blowout, volume',
                'status' => 'Active',
                'created_at' => '2025-05-16 06:59:43',
                'updated_at' => '2025-09-22 08:02:30',
            ),
            4 => 
            array (
                'id' => 11,
                'name' => 'Bridal Updo',
                'description' => 'Elegant updo tailored for brides, featuring pinned curls and decorative accessories for a graceful look on the big day.',
                'image' => '/assets/styles/Bridal Updo-1747382427.png',
                'category' => 'Special Occasion',
                'tag' => 'bridal, updo, weddings',
                'status' => 'Active',
                'created_at' => '2025-05-16 07:00:27',
                'updated_at' => '2026-03-02 15:20:45',
            ),
        ));
        
        
    }
}