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
            'icon' => 'bi bi-grid-fill',
            'order' => 1,
        ]);

        $pop = Menu::create([
            'name' => 'POP',
            'route' => 'pops.index', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-geo-alt-fill',
            'order' => 2,
        ]);

        $rma = Menu::create([
            'name' => 'RMA',
            'route' => 'rma', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-file-earmark-text-fill',
            'order' => 3,
        ]);

        $accountConfig = Menu::create([
            'name' => 'Konfigurasi Akun',
            'route' => null, // parent tidak punya route sendiri, cuma dropdown toggle
            'icon' => 'bi bi-gear-fill',
            'order' => 4,
        ]);

        $usermanagement = Menu::create([
            'name' => 'Manajemen User',
            'route' => 'admin.users.index', // sesuaikan dengan nama route asli kamu
            'icon' => 'bi bi-people',
            'order' => 5,
            'parent_id' => $accountConfig->id,
        ]);

        $AccessManagement = Menu::create([
            'name' => 'Manajemen Akses Role',
            'route' => 'admin.access.index',
            'icon' => 'bi bi-shield-fill',
            'order' => 6,
            'parent_id' => $accountConfig->id,
        ]);

        $rectifier = Menu::create([
            'name' => 'Rectifier',
            'route' => 'rectifiers.index',
            'icon' => 'bi bi-cpu-fill',
            'order' => 1,
            'parent_id' => $pop->id,
            'is_sidebar' => false,
        ]);

        $kwh = Menu::create([
            'name' => 'kWh',
            'route' => null,
            'icon' => 'bi bi-lightning-charge-fill',
            'order' => 2,
            'parent_id' => $pop->id,
            'is_sidebar' => false,
        ]);

        $battery = Menu::create([
            'name' => 'Battery',
            'route' => null,
            'icon' => 'bi bi-battery-full',
            'order' => 3,
            'parent_id' => $pop->id,
            'is_sidebar' => false,
        ]);

        $ac = Menu::create([
            'name' => 'AC',
            'route' => null,
            'icon' => 'bi bi-fan',
            'order' => 4,
            'parent_id' => $pop->id,
            'is_sidebar' => false,
        ]);



        $karyawan = Role::where('name', 'karyawan')->first();
        $teknisi = Role::where('name', 'teknisi')->first();
        $superAdmin = Role::where('name', 'super_admin')->first();

        // sesuaikan siapa boleh lihat menu apa
        $dashboard->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]); // semua role
        $pop->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);   // semua role
        $rectifier->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);
        $kwh->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);
        $battery->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);
        $ac->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);
        $rma->roles()->sync([$karyawan->id, $teknisi->id, $superAdmin->id]);   // semua role
        $usermanagement->roles()->sync([$superAdmin->id]);   // hanya admin
        $accountConfig->roles()->sync([$superAdmin->id]);
        $AccessManagement->roles()->sync([$superAdmin->id]); // hanya admin
    }
}
