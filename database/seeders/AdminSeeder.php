<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Admin User
        $admin = User::create([
            'name' => 'admin', 
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin1234')
        ]);
        $admin->assignRole('Admin');

        // Creating Manager User
        $manager = User::create([
            'name' => 'manager', 
            'email' => 'manager@gmail.com',
            'password' => Hash::make('manager1234')
        ]);
        $manager->assignRole('Manager');


        // Creating User
        $user = User::create([
            'name' => 'user', 
            'email' => 'user@gmail.com',
            'password' => Hash::make('user1234')
        ]);
        $user->assignRole('User');
    }
}
