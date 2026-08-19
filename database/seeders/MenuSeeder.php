<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'bi bi-house',
            'order' => 1,
        ]);

        $pop = Menu::create([
            'name' => 'POP',
            'route' => 'pop', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-box-seam',
            'order' => 2,
        ]);

        $rma = Menu::create([
            'name' => 'RMA',
            'route' => 'rma', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-arrow-return-left',
            'order' => 3,
        ]);

        $usermanagement = Menu::create([
            'name' => 'Manajemen User',
            'route' => 'admin.users.index', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-people',
            'order' => 4,
        ]);



        $karyawan = Role::where('name', 'karyawan')->first();
        $teknisi = Role::where('name', 'teknisi')->first();
        $superAdmin = Role::where('name', 'super_admin')->first();

        // sesuaikan siapa boleh lihat menu apa
        $dashboard->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]); // semua role
        $pop->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);   // semua role
        $rma->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);   // semua role
        $usermanagement->roles()->sync([$superAdmin->id]);   // hanya admin
    }
}
