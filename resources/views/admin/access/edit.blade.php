<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Akses {{ $role->name }} - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/access.css'])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <main class="main-content">
            <x-topbar />

            <div class="access-content">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
                    <a href="{{ route('admin.access.index') }}"
                       style="width:34px; height:34px; border-radius:50%; background:#f1f5f9; color:#64748b; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0;"
                       title="Kembali ke Manajemen Akses">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <x-breadcrumb :items="[
                            ['label' => 'Manajemen Akses Role', 'route' => 'admin.access.index'],
                            ['label' => 'Atur Akses: ' . ucfirst(str_replace('_', ' ', $role->name))],
                        ]" />
                        <p style="margin:2px 0 0; font-size:0.8rem; color:#64748b;">Centang toggle untuk memberi izin create/read/update/delete pada tiap menu</p>
                    </div>
                </div>

                <form id="form-akses" action="{{ route('admin.access.update', $role) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="access-toolbar">
                        <div class="field">
                            <label>Salin dari role lain</label>
                            <select id="copyFromRole" class="access-select">
                                <option value="">Pilih role...</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->id }}">{{ ucfirst(str_replace('_', ' ', $r->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="btnTerapkanCopy" class="btn-terapkan">
                            <i class="bi bi-clipboard-check"></i> Terapkan
                        </button>

                        <div class="field grow">
                            <label>Cari menu</label>
                            <input type="text" id="searchMenu" class="access-input" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="table-card">
                        <table class="access-table">
                            <thead>
                                <tr>
                                    <th style="width: 260px;">Menu</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menus as $menu)
                                    @if (!$menu->route)
                                        <tr class="access-group-row">
                                            <td colspan="2">{{ $menu->name }}</td>
                                        </tr>
                                        @foreach ($menu->children as $child)
                                            @include('admin.access.partials.menu-row', [
                                                'menu' => $child,
                                                'isChild' => true,
                                            ])
                                        @endforeach
                                    @else
                                        @include('admin.access.partials.menu-row', [
                                            'menu' => $menu,
                                            'isChild' => false,
                                        ])
                                        @foreach ($menu->children as $child)
                                            @include('admin.access.partials.menu-row', [
                                                'menu' => $child,
                                                'isChild' => true,
                                            ])
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="2" class="access-empty">
                                            Role ini belum punya menu yang di-assign. Atur dulu di halaman "Menu".
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px; display:flex; justify-content:flex-end; gap:10px;">
                        <a href="{{ route('admin.access.index') }}" class="btn-terapkan"
                            style="text-decoration:none;">Batal</a>
                        <button type="submit" class="btn-simpan-akses"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchMenu');
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.access-menu-row').forEach(row => {
                    const name = row.dataset.menuName.toLowerCase();
                    row.style.display = name.includes(q) ? '' : 'none';
                });
            });

            document.querySelectorAll('.select-all-check').forEach(check => {
                check.addEventListener('change', function() {
                    const row = this.closest('.access-menu-row');
                    row.querySelectorAll('.action-toggle').forEach(t => t.checked = this.checked);
                });
            });

            document.querySelectorAll('.action-toggle').forEach(t => {
                t.addEventListener('change', function() {
                    const row = this.closest('.access-menu-row');
                    const all = row.querySelectorAll('.action-toggle');
                    const checkedCount = row.querySelectorAll('.action-toggle:checked').length;
                    row.querySelector('.select-all-check').checked = checkedCount === all.length;
                });
            });

            document.getElementById('btnTerapkanCopy').addEventListener('click', async function() {
                const roleId = document.getElementById('copyFromRole').value;
                if (!roleId) {
                    alert('Pilih role dulu untuk disalin.');
                    return;
                }
                try {
                    const res = await fetch(`/admin/access/${roleId}/permissions`);
                    const permissionIds = await res.json();

                    document.querySelectorAll('.action-toggle').forEach(t => {
                        t.checked = permissionIds.includes(Number(t.value));
                    });
                    document.querySelectorAll('.access-menu-row').forEach(row => {
                        const all = row.querySelectorAll('.action-toggle');
                        const checkedCount = row.querySelectorAll('.action-toggle:checked')
                            .length;
                        row.querySelector('.select-all-check').checked = all.length > 0 &&
                            checkedCount === all.length;
                    });
                } catch (e) {
                    alert('Gagal mengambil data permission role tersebut.');
                }
            });
        });
    </script>
</body>

</html>
