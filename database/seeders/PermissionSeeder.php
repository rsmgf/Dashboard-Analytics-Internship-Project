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
     *
     * Extra actions per menu route (di luar create/read/update/delete standar).
     * Key = route menu, Value = array of extra action names.
     */
    private array $extraActions = [
        'dashboard' => ['filter', 'ekspor'],
    ];

    public function run(): void
    {
        $baseActions = ['create', 'read', 'update', 'delete'];

        // hanya menu yang punya route asli — tolak NULL *dan* string kosong
        $menus = Menu::whereNotNull('route')
            ->where('route', '<>', '')
            ->get();

        foreach ($menus as $menu) {
            $actions = array_merge(
                $baseActions,
                $this->extraActions[$menu->route] ?? []
            );

            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    [
                        'name'       => "{$menu->route}.{$action}",
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

        // Manajer = sama seperti super_admin (semua permission)
        // Perbedaan hanya pada UI (sidebar muncul/hilang berdasarkan session active_role)
        $manajer = Role::where('name', 'manajer')->first();
        if ($manajer) {
            $manajer->syncPermissions(Permission::all());
        }
    }
}