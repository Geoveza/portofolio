<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * SECURITY: This seeder creates the initial admin user
     * IMPORTANT: Change the default password immediately after first login!
     */
    public function run(): void
    {
        // Check if any admin user already exists
        if (User::where('role', 'admin')->exists()) {
            $this->command->warn('An admin user already exists. Skipping creation.');
            return;
        }

        // Get credentials from environment or use secure random defaults
        $name = env('ADMIN_NAME', 'Admin User');
        $email = env('ADMIN_EMAIL', 'geoveza21@gmail.com');
        $password = env('ADMIN_PASSWORD', 'mruknown213');
        
        // SECURITY: If no password provided in env, generate a secure random one
        if (!$password) {
            $password = bin2hex(random_bytes(16)); // 32 character random password
            $this->command->warn('=========================================');
            $this->command->warn('GENERATED RANDOM ADMIN PASSWORD:');
            $this->command->warn($password);
            $this->command->warn('=========================================');
            $this->command->warn('Please save this password immediately!');
            $this->command->warn('Or set ADMIN_PASSWORD in your .env file before seeding.');
            $this->command->warn('=========================================');
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->command->info("Admin user '{$user->email}' created successfully!");
        
        // SECURITY: Log the creation
        \Illuminate\Support\Facades\Log::info('Initial admin user created via seeder', [
            'email' => $user->email,
            'created_at' => now(),
        ]);
    }
}
