<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing test users
        DB::table('users')->whereIn('email', [
            'employee@test.com',
            'superior@test.com',
            'it@test.com',
            'hod@test.com',
            'hodiv@test.com',
            'presdir@test.com',
        ])->delete();

        // Create test users for each role
        $users = [
            // Employee
            [
                'name' => 'Test Employee',
                'email' => 'employee@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'EMP001',
                'role' => 'employee',
                'department' => 'IT Department',
                'division' => 'General',
            ],
            // IT
            [
                'name' => 'Test IT',
                'email' => 'it@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'IT001',
                'role' => 'it',
                'department' => 'IT',
                'division' => 'General',
            ],
            // Head of Department
            [
                'name' => 'Test Head of Dept',
                'email' => 'hod@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'HOD001',
                'role' => 'Head of Department',
                'department' => 'Production',
                'division' => 'PCBA',
            ],
            // Head of Division
            [
                'name' => 'Test Head of Division',
                'email' => 'hodiv@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'HODIV001',
                'role' => 'Head of Division',
                'department' => 'Management',
                'division' => 'General',
            ],
            // President Director
            [
                'name' => 'Test President Director',
                'email' => 'presdir@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'PRES001',
                'role' => 'President Director',
                'department' => '-',
                'division' => '-',
            ],
        ];

        DB::table('users')->insert($users);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Test users created successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Login credentials (password: admin123):');
        $this->command->info('');
        $this->command->info('  Employee:           employee@test.com');
        $this->command->info('  IT:                 it@test.com');
        $this->command->info('  Head of Dept:       hod@test.com');
        $this->command->info('  Head of Division:   hodiv@test.com');
        $this->command->info('  President Director: presdir@test.com');
        $this->command->info('');
    }
}
