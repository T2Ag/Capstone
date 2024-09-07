<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['create', 'edit', 'delete', 'view'];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        Role::create(['name' => 'admin'])->givePermissionTo($permissions);

        Role::create(['name' => 'trainor'])->givePermissionTo(['edit']);

        Role::create(['name' => 'user'])->givePermissionTo(['view']);
    }
}
