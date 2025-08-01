<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun admin atau user default
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Jangan lupa hashing!
            'email_verified_at' => now(),
        ]);

        // Panggil TopicSeeder juga agar data topic terisi
        $this->call(TopicSeeder::class);

        // Panggil QuestionSeeder jika memang dibutuhkan
        $this->call(QuestionSeeder::class);
    }
}
