<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BlogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blogs')->delete();
        
        \DB::table('blogs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '5 Tips for Healthy, Shiny Hairs',
                'slug' => '5-tips-for-healthy-shiny-hairs',
            'content' => '<h3><span style=\\"color: rgb(255, 255, 102);\\">Our stylists share their secrets to maintaining gorgeous hair between salon visits. Healthy hair starts with proper care and nourishment.</span></h3><p><span style=\\"color: rgb(255, 255, 102);\\">&nbsp;&nbsp;&nbsp;</span></p><ul><li><span style=\\"color: rgb(255, 255, 102);\\">Use sulfate-free shampoos</span></li><li><span style=\\"color: rgb(255, 255, 102);\\">Regular deep conditioning</span></li><li><span style=\\"color: rgb(255, 255, 102);\\">Protect hair from heat damage</span></li><li><span style=\\"color: rgb(255, 255, 102);\\">Trim split ends regularly</span></li><li><span style=\\"color: rgb(255, 255, 102);\\">Eat a balanced diet rich in vitamins</span></li></ul><p><br></p><p><span style=\\"color: rgb(255, 255, 102);\\">Follow these tips to keep your hair shiny and vibrant every day.</span><a href=\\"http://lnkedIn.com\\" rel=\\"noopener noreferrer\\" target=\\"_blank\\" style=\\"color: rgb(255, 255, 102);\\">http://lnkedIn.com</a></p>',
                'tag' => 'Trendys',
                'image' => '/assets/blogs/5-tips-for-healthy-shiny-hair-1749630074.png',
                'status' => 'Active',
                'created_at' => '2025-05-16 11:27:29',
                'updated_at' => '2025-09-22 07:52:51',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '3 Ways To Stay Elegant',
                'slug' => '3-ways-to-stay-elegant',
                'content' => '<h2><strong><u>These are three ways for you to stay elegant</u></strong></h2><p>Below are some of the three ways by which you can stay as elegant as you like.</p><ol><li>Stay Eleganze</li><li>Stay correct</li><li>Stay attractive</li></ol><p>I know it doesn\'t make a lot of sense to you but try to make sense out of it.</p>',
                'tag' => 'Tutorial',
                'image' => '/assets/blogs/3-ways-to-stay-elegant-1749630092.png',
                'status' => 'Active',
                'created_at' => '2025-05-16 13:46:32',
                'updated_at' => '2025-06-11 07:21:33',
            ),
            2 => 
            array (
                'id' => 11,
                'title' => 'Revamp Your Style: Must-Know Hair Trends & Tips for 2025',
                'slug' => 'revamp-your-style-must-know-hair-trends-tips-for-2025',
                'content' => '<p><br></p><p>Looking to update your hairstyle this year? Whether you\'re craving a bold new cut or just want to maintain healthy, shiny hair, 2025 is all about embracing individuality and experimenting with fresh trends!</p><p>This season, we’re seeing a resurgence of natural textures, layered cuts, and vibrant color techniques like balayage and pastel shades. As your hairstylist, I love helping clients find styles that boost confidence and suit their lifestyle.</p><p>Here are a few tips to keep your hair looking gorgeous:</p><ul><li>Regular trims to prevent split ends</li><li>Use heat protectants before styling tools</li><li>Incorporate nourishing masks weekly</li><li>Try a new color for a pop of fun</li></ul><p>Ready to make a change? Book your appointment today, and let’s craft a look that’s uniquely you!</p>',
                'tag' => 'Tips,Trendy',
                'image' => '/assets/blogs/revamp-your-style-must-know-h-1749630104.png',
                'status' => 'Active',
                'created_at' => '2025-05-19 08:51:11',
                'updated_at' => '2025-06-11 07:21:44',
            ),
        ));
        
        
    }
}