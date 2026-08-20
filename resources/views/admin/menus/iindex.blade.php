    <x-app-layout>
        <div class="py-8 max-w-6xl mx-auto px-4">
            <h1 class="text-2xl font-bold mb-6">Manajemen Menu</h1>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ============================
                FORM TAMBAH MENU
            ============================= --}}
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold mb-4">Tambah Menu Baru</h2>

                <form action="{{ route('admin.menus.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @csrf

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                            placeholder="Dashboard" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Route</label>
                        <input type="text" name="route" value="{{ old('route') }}"
                            class="w-full border rounded px-3 py-2 @error('route') border-red-500 @enderror"
                            placeholder="dashboard">
                        @error('route')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (class)</label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                            class="w-full border rounded px-3 py-2 @error('icon') border-red-500 @enderror"
                            placeholder="bi bi-house">
                        @error('icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Induk Menu</label>
                        <select name="parent_id" class="w-full border rounded px-3 py-2">
                            <option value="">— Tidak ada (menu utama) —</option>
                            @foreach ($menus as $parentMenu)
                                <option value="{{ $parentMenu->id }}"
                                    {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                                    {{ $parentMenu->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}"
                            class="w-full border rounded px-3 py-2" min="0">
                    </div>

                    <div class="md:col-span-5">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Tambah Menu
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============================
                DAFTAR MENU + ASSIGN ROLE
            ============================= --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left p-3 w-1/4">Menu</th>
                            <th class="text-left p-3 w-1/6">Route</th>
                            <th class="text-left p-3">Akses Role</th>
                            <th class="text-left p-3 w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            {{-- Baris menu utama --}}
                            <tr class="border-t">
                                <td class="p-3 font-medium">
                                    @if ($menu->icon)
                                        <i class="{{ $menu->icon }} mr-1"></i>
                                    @endif
                                    {{ $menu->name }}
                                </td>
                                <td class="p-3 text-sm text-gray-500">{{ $menu->route ?? '-' }}</td>
                                <td class="p-3">
                                    <form action="{{ route('admin.menus.updateRoles', $menu) }}" method="POST"
                                        class="flex flex-wrap items-center gap-4">
                                        @csrf
                                        @method('PATCH')

                                        @foreach ($roles as $role)
                                            <label class="flex items-center gap-1 text-sm">
                                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                    {{ $menu->roles->contains($role->id) ? 'checked' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </label>
                                        @endforeach

                                        <button type="submit"
                                            class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        onsubmit="return confirm('Hapus menu ini beserta submenu-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-sm hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Baris submenu (kalau ada) --}}
                            @foreach ($menu->children as $child)
                                <tr class="border-t bg-gray-50">
                                    <td class="p-3 pl-10 text-sm">
                                        <span class="text-gray-400">↳</span>
                                        @if ($child->icon)
                                            <i class="{{ $child->icon }} mr-1"></i>
                                        @endif
                                        {{ $child->name }}
                                    </td>
                                    <td class="p-3 text-sm text-gray-500">{{ $child->route ?? '-' }}</td>
                                    <td class="p-3">
                                        <form action="{{ route('admin.menus.updateRoles', $child) }}" method="POST"
                                            class="flex flex-wrap items-center gap-4">
                                            @csrf
                                            @method('PATCH')

                                            @foreach ($roles as $role)
                                                <label class="flex items-center gap-1 text-sm">
                                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                        {{ $child->roles->contains($role->id) ? 'checked' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </label>
                                            @endforeach

                                            <button type="submit"
                                                class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                    <td class="p-3">
                                        <form action="{{ route('admin.menus.destroy', $child) }}" method="POST"
                                            onsubmit="return confirm('Hapus submenu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 text-sm hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-500">
                                    Belum ada menu. Tambahkan menu pertama lewat form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-app-layout>
