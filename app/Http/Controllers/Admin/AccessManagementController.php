<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class AccessManagementController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('admin.access.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $roles = Role::where('id', '!=', $role->id)->get(); // buat dropdown "copy dari role lain"

        // ambil parent + child, tapi hanya yang menu-nya di-assign ke role ini
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function ($q) use ($role) {
                $q->whereHas('roles', fn($r) => $r->where('roles.id', $role->id))
                    ->with('permissions')
                    ->orderBy('order');
            }, 'permissions'])
            ->whereHas('roles', fn($r) => $r->where('roles.id', $role->id))
            ->orWhereHas('children.roles', fn($r) => $r->where('roles.id', $role->id))
            ->orderBy('order')
            ->get()
            // buang parent yang ternyata tidak punya route sendiri & tidak ada child yang lolos filter
            ->filter(fn($menu) => $menu->route || $menu->children->isNotEmpty());

        // permission yang SUDAH dimiliki role ini saat ini (buat pre-check toggle)
        $currentPermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.access.edit', compact('role', 'roles', 'menus', 'currentPermissionIds'));
    }

    public function getRolePermissions(Role $role)
    {
        return response()->json($role->permissions->pluck('id'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions'   => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // safety: batasi hanya permission dari menu yang memang sudah di-assign ke role ini,
        // supaya konsisten dengan filter kombinasi menu-role + permission
        $allowedMenuIds = $role->menus()->pluck('menus.id') ?? collect();
        // NB: kalau Role tidak punya relasi menus(), pakai query manual di bawah ini sbg gantinya:
        // $allowedMenuIds = \App\Models\Menu::whereHas('roles', fn($r) => $r->where('roles.id', $role->id))->pluck('id');

        $validPermissionIds = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions ?? [])
            ->whereIn('menu_id', $allowedMenuIds)
            ->pluck('id');

        $role->syncPermissions($validPermissionIds);

        return redirect()->route('admin.access.edit', $role)
            ->with('success', "Akses untuk role {$role->name} berhasil diperbarui");
    }
}
