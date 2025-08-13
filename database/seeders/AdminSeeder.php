<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

    class AdminSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Create admin role if it doesn't exist
            $adminRole = Role::firstOrCreate(['name' => 'ADMIN']);

            // Create the admin user
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@dmw.com',
                    'name' => 'Admin User',
                    'password' => Hash::make('password'), // change to secure password
                    "email_verified_at" => now(),
                ]
            );

            // Assign the admin role
            $adminUser->assignRole($adminRole);
        }
    }
