<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        User::updateOrCreate([
            'email' => 'leeadrian994@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => 'Hello2026!!!',
            'is_admin' => true,
        ]);

        // Seed products
        $this->call(ProductSeeder::class);
    }
}
