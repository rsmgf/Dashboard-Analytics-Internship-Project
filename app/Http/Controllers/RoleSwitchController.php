<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $role = $request->input('role');
        $user = auth()->user();

        // Hanya manajer yang boleh switch role (ke mode admin atau kembali ke manajer)
        if (!$user->hasRole('manajer')) {
            abort(403, 'Hanya role Manajer yang dapat beralih mode tampilan.');
        }

        // Validasi target mode: hanya 'manajer' atau 'super_admin'
        if (!in_array($role, ['manajer', 'super_admin'])) {
            abort(422, 'Mode tidak valid.');
        }

        // Simpan ke session sebagai mode tampilan (bukan perubahan role nyata)
        session(['active_role' => $role]);

        $label = $role === 'super_admin' ? 'Admin' : 'Manajer';
        return redirect()->back()->with('info', "Tampilan beralih ke mode {$label}.");
    }
}
