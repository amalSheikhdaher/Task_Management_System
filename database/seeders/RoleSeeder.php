<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $user = Role::create(['name' => 'User']);

        

        // Assign full permissions to Admin
        $admin->givePermissionTo([
            'register',
            'index-user',
            'show-user',
            'create-user',
            'update-user',
            'delete-user',
            'trashed-user',
            'restore-user',
            'forceDelete-user',
            'create-task',
            'update-task',
            'delete-task',
            'assign-task',
            'trashed-task',
            'restore-task',
            'forceDelete-task'
        ]);

        // Assign specific permissions to Manager
        $manager->givePermissionTo([
            'create-task',
            'update-task',
            'delete-task',
            'assign-task',
            'index-user',
            'show-user',
        ]);

        // Assign permissions to User
        $user->givePermissionTo(['status-task']);
    }
}