<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::whereIn('name', ['karyawan', 'teknisi'])->get();
        return view('auth.register', [
            'roles' => $roles,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'no_hp' => ['required', 'string', 'min:7', 'max:13', 'regex:/^[0-9+\-\s]+$/'],
                // 'role' => ['required', 'in:karyawan,teknisi'],
            ],
            [
                // --- PESAN CUSTOM PER JENIS KESALAHAN ---
                'name.required'  => 'Nama wajib diisi.',

                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email ini sudah terdaftar, silakan gunakan email lain.',

                'no_hp.required' => 'Nomor handphone wajib diisi.',
                'no_hp.min'      => 'Nomor handphone minimal 7 karakter.',
                'no_hp.max'      => 'Nomor handphone maksimal 13 karakter.',
                'no_hp.regex'    => 'Nomor handphone hanya boleh berisi angka.',

                'password.required'  => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak sama.',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('karyawan');

        event(new Registered($user));

        // Auth::login($user);

        return redirect(route('login', absolute: false))
            ->with('unverified', 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi dari admin.');
    }
}
