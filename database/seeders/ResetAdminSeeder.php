<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResetAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * WARNING: This will DELETE ALL existing users and create a new admin user!
     */
    public function run(): void
    {
        // Delete all existing users
        $deletedCount = User::count();
        User::truncate();
        $this->command->warn("Deleted {$deletedCount} existing user(s) from database.");

        // Create new admin user with specified credentials
        $email = 'geoveza21@gmail.com';
        $password = 'mruknown213';

        $user = User::create([
            'name' => 'Geoveza',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->command->info('=========================================');
        $this->command->info('New admin user created successfully!');
        $this->command->info('=========================================');
        $this->command->info("Email: {$email}");
        $this->command->info("Password: {$password}");
        $this->command->info('=========================================');
        
        // SECURITY: Log the creation
        \Illuminate\Support\Facades\Log::info('Admin user reset via seeder', [
            'email' => $user->email,
            'created_at' => now(),
        ]);
    }
}
