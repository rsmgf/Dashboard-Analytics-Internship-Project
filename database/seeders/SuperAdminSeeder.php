<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
        ['email' => 'admin@plniconplus.co.id'],
        [
            'name' => 'Manajer Unit ICONPLUS KP Jambi - Sandria Abhiseka',
            'password' => Hash::make('iconplusjaya'),
        ]
    );
    $admin->assignRole('super_admin');
    }
}
