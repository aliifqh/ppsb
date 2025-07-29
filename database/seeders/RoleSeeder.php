<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Delete existing roles and permissions
        Role::query()->delete();
        Permission::query()->delete();

        // Create roles
        $superAdmin = Role::create(['name' => 'super admin']);
        $panitia = Role::create(['name' => 'panitia']);
        $keuangan = Role::create(['name' => 'keuangan']);
        $ustadz = Role::create(['name' => 'ustadz']);

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view santri',
            'create santri',
            'edit santri',
            'delete santri',
            'view pembayaran',
            'create pembayaran',
            'edit pembayaran',
            'delete pembayaran',
            'view gelombang',
            'create gelombang',
            'edit gelombang',
            'delete gelombang',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to super admin
        $superAdmin->givePermissionTo(Permission::all());

        // Assign specific permissions to panitia
        $panitia->givePermissionTo([
            'view santri',
            'create santri',
            'edit santri',
            'view pembayaran',
        ]);

        // Assign specific permissions to keuangan
        $keuangan->givePermissionTo([
            'view pembayaran',
            'edit pembayaran',
        ]);

        // Assign specific permissions to ustadz
        $ustadz->givePermissionTo([
            'view santri',
        ]);
    }
}
