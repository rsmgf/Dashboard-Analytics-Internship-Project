<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Manajemen User & Role</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Nama</th>
                    <th class="text-left p-3">Username</th>
                    <th class="text-left p-3">Email</th>
                    <th class="text-left p-3">Role Saat Ini</th>
                    <th class="text-left p-3">Ubah Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->username }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="p-3">
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="border rounded px-2 py-1">
                                    <option value="karyawan" @selected($user->hasRole('karyawan'))>Karyawan</option>
                                    <option value="teknisi" @selected($user->hasRole('teknisi'))>Teknisi</option>
                                    <option value="super_admin" @selected($user->hasRole('super_admin'))>Super Admin</option>
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    Simpan
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>