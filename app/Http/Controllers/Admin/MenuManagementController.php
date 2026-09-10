<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuManagementController extends Controller
{
    public function index()
    {
        $menus = Menu::with('roles')->whereNull('parent_id')->orderBy('order')->get();
        $roles = Role::all();
        return view('admin.menus.index', compact('menus', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'route' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
        ]);

        $menu = Menu::create($request->only('name', 'route', 'icon', 'order'));

        Cache::flush();

        return back()->with('success', 'Menu berhasil ditambahkan');
    }

    public function updateRoles(Request $request, Menu $menu)
    {
        $request->validate([
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $menu->roles()->sync($request->roles ?? []);

        Cache::flush();

        return back()->with('success', "Akses menu {$menu->name} berhasil diperbarui");
    }

    public function destroy(Menu $menu)
    {
        $menu->delete(); // otomatis hapus submenu juga karena onDelete('cascade') di migration

        Cache::flush();

        return back()->with('success', "Menu {$menu->name} berhasil dihapus");
    }
}
