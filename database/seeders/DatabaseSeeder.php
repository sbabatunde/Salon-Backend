<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call(UsersTableSeeder::class);
        $this->call(AppointmentsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(InventoriesTableSeeder::class);
        $this->call(StylesTableSeeder::class);
        $this->call(BlogsTableSeeder::class);
        $this->call(SiteVideosTableSeeder::class);
        $this->call(SignatureLooksTableSeeder::class);
        $this->call(TestimonialsTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(StylistsTableSeeder::class);
    }
}
