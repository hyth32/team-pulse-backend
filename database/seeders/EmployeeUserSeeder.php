<?php

namespace Database\Seeders;

use App\Enums\EntityStatus;
use App\Enums\User\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Employee',
            'lastname' => 'Employee',
            'email' => 'employee@employee.com',
            'email_verified_at' => now(),
            'login' => 'employee',
            'password' => Hash::make('password'),
            'role' => UserRole::Employee->value(),
            'status' => EntityStatus::Active->value(),
        ]);

        $this->command->info('Employee user created.');
    }
}
