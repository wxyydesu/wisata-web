<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Admin No. 1',
            'password' => Hash::make('admin_ganteng'),
            'level' => 'admin',
        ]);

        $this->call([
            KategoriWisataSeeder::class,
            // Tambahkan seeder lainnya di sini
        ]);
    }
}
 
