<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
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
        $actions = ['create', 'read', 'update', 'delete'];

        // hanya menu yang punya route asli (bukan parent dropdown)
        $menus = Menu::whereNotNull('route')->get();

        foreach ($menus as $menu) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$menu->route}.{$action}", // contoh: pops.index.create
                    'guard_name' => 'web',
                    'menu_id' => $menu->id,
                ]);
            }
        }

        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->syncPermissions(Permission::all());
    }
}
