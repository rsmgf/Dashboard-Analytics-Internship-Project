<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
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

        // hanya menu yang punya route asli — tolak NULL *dan* string kosong
        $menus = Menu::whereNotNull('route')
            ->where('route', '<>', '')
            ->get();

        foreach ($menus as $menu) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    [
                        'name' => "{$menu->route}.{$action}",
                        'guard_name' => 'web',
                    ],
                    [
                        'menu_id' => $menu->id,
                    ]
                );
            }
        }

        // Bootstrap: super_admin otomatis dapat SEMUA permission
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->syncPermissions(Permission::all());
    }
}