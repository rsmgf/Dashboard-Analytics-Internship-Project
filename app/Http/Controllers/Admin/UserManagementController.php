<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $sort   = $request->input('sort', 'created_at');
        $dir    = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        // whitelist kolom yang boleh di-sort, cegah SQL injection lewat query string
        $allowedSorts = ['name', 'email', 'created_at', 'is_active'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $users = User::with('roles')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('is_active', $status === 'active');
            })
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'status', 'sort', 'dir'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role'        => ['required', 'in:karyawan,teknisi,super_admin'],
        ], [
            'role.required' => 'Role wajib dipilih.',
            'role.in'       => 'Role tidak valid.',
        ]);

        $user->is_active = true; // assign role = otomatis approve/aktifkan user
        $user->save();

        $user->syncRoles([$request->role]);

        return response()->json([
            'success'   => true,
            'message'   => "Role {$user->name} berhasil diubah ke {$request->role}.",
            'role'      => $request->role,
            'is_active' => true,
        ]);
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $roleName = $user->roles->first()?->name;
        $roleLabel = ($user->is_active && $roleName)
            ? ucfirst(str_replace('_', ' ', $roleName))
            : '-';

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active,
            'message'   => $user->is_active
                ? "{$user->name} berhasil diaktifkan."
                : "{$user->name} berhasil dinonaktifkan.",
        ]);
    }
}
