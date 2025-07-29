<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Buat user admin default
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'aliifqh@almukmin.sch.id',
            'password' => Hash::make('mamalemon'),
            'google_avatar' => null,
        ]);

        // Assign role super admin
        $admin->assignRole('super admin');

        // Buat user ustadz default
        $ustadz = User::create([
            'name' => 'Ustadz Default',
            'email' => 'ustadz@almukmin.sch.id',
            'password' => Hash::make('password123'),
            'google_avatar' => null,
        ]);

        // Assign role ustadz
        $ustadz->assignRole('ustadz');

        $this->command->info('Users seeded successfully!');
        $this->command->info('Aliifqh: aliifqh@almukmin.sch.id / password123');
        $this->command->info('Ustadz: ustadz@almukmin.sch.id / password123');
    }
}
