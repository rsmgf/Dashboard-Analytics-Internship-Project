<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index() {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:karyawan,teknisi,super_admin'], // di sini semua role boleh, karena hanya admin yang akses
        ]);

        $user->syncRoles([$request->role]); // syncRoles = ganti role lama dengan yang baru

        return back()->with('success', "Role {$user->name} berhasil diubah ke {$request->role}");
    }
}
