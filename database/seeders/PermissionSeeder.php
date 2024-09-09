<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
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
            'status-task',
            'trashed-task',
            'restore-task',
            'forceDelete-task',

        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
