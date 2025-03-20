<?php

namespace Database\Seeders;

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
            'login' => 'employee',
            'password' => Hash::make('password'),
            'role' => UserRole::Employee->value(),
        ]);

        $this->command->info('Employee user created.');
    }
}
