<?php

namespace Database\Seeders;

use App\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminExists = User::where(['role' => UserRole::Admin->value()])->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'lastname' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'login' => 'admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin->value(),
            ]);

            $this->command->info('Admin user created');
        } else {
            $this->command->info('Admin user already exists');
        }
    }
}
