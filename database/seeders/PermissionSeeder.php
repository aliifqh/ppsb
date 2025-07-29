<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar semua permission yang ada di aplikasi
        $permissions = [
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Santri
            'view santri',
            'edit santri',
            'delete santri',

            // Pembayaran
            'view pembayaran',
            'edit pembayaran',

            // Gelombang
            'view gelombang',
            'create gelombang',
            'edit gelombang',
            'delete gelombang',

            // Pengaturan
            'view pengaturan',
            'edit pengaturan',

            // Role & Permission
            'view roles',
            'edit roles',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Pastikan role super admin memiliki semua permission
        $role = Role::firstOrCreate(['name' => 'super admin']);
        $role->syncPermissions($permissions);

        // Role default untuk user baru (ustadz)
        $defaultRole = Role::firstOrCreate(['name' => 'ustadz']);
        $defaultRole->syncPermissions([
            'view santri',
            'view pembayaran',
            'view gelombang',
        ]);
    }
}
