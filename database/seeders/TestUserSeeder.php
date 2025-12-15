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
        // Clear ALL existing data
        $this->command->info('Clearing existing data...');
        
        // Delete in order of foreign key dependencies
        DB::table('approval')->delete();
        DB::table('pr_detail')->delete();
        DB::table('pr')->delete();
        DB::table('users')->delete();
        
        $this->command->info('All data cleared.');

        // Create 6 structured users
        $users = [
            // Employee 1 - Maintenance Engineering, PCBA
            [
                'name' => 'Abyan',
                'email' => 'abyan@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'EMP001',
                'role' => 'employee',
                'position' => 'staff',
                'department' => 'Maintenance Engineering',
                'division' => 'PCBA',
            ],
            // Employee 2 - Production Testing, PCBA
            [
                'name' => 'Rey',
                'email' => 'rey@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'EMP002',
                'role' => 'employee',
                'position' => 'staff',
                'department' => 'Production Testing',
                'division' => 'PCBA',
            ],
            // IT - IT Department, General
            [
                'name' => 'El',
                'email' => 'el@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'IT001',
                'role' => 'it',
                'position' => 'staff',
                'department' => 'IT',
                'division' => 'General',
            ],
            // Head of Department - Maintenance Engineering, PCBA
            [
                'name' => 'Raki',
                'email' => 'raki@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'HOD001',
                'role' => 'Head of Department',
                'position' => 'head_of_department',
                'department' => 'Maintenance Engineering',
                'division' => 'PCBA',
            ],
            // Head of Division - PCBA
            [
                'name' => 'Kira',
                'email' => 'kira@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'HODIV001',
                'role' => 'Head of Division',
                'position' => 'head_of_division',
                'department' => '-',
                'division' => 'PCBA',
            ],
            // President Director
            [
                'name' => 'Mai',
                'email' => 'mai@test.com',
                'password' => Hash::make('admin123'),
                'badge' => 'PRES001',
                'role' => 'President Director',
                'position' => 'president_director',
                'department' => '-',
                'division' => '-',
            ],
        ];

        DB::table('users')->insert($users);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('6 Structured users created successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Login credentials (password: admin123):');
        $this->command->info('');
        $this->command->info('  Employee (Maintenance Eng, PCBA):     abyan@test.com');
        $this->command->info('  Employee (Production Testing, PCBA):  rey@test.com');
        $this->command->info('  IT (IT, General):                     el@test.com');
        $this->command->info('  Head of Dept (Maintenance Eng, PCBA): raki@test.com');
        $this->command->info('  Head of Division (PCBA):              kira@test.com');
        $this->command->info('  President Director:                   mai@test.com');
        $this->command->info('');
    }
}
