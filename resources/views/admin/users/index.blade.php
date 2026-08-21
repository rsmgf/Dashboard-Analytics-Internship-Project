<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/sidebar.css', 'resources/css/manajemen-role.css'])
    <style>
        .role-table th:nth-child(6),
        .role-table th:nth-child(7),
        .role-table td:nth-child(6),
        .role-table td:nth-child(7) {
            text-align: center;
        }

        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: color 0.2s ease;
        }

        .sortable-header:hover {
            color: #007BFF;
        }

        .sortable-header i {
            font-size: 11px;
            margin-left: 4px;
            color: #94A3B8;
            transition: .2s ease;
        }

        .sortable-header.active-asc i,
        .sortable-header.active-desc i {
            color: #007BFF;
        }
    </style>
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <main class="main-content">
            <x-topbar />

            <div class="role-page">
                <div class="role-title">
                    <h1>Manajemen User</h1>
                </div>

                @if (session('success'))
                    <div class="role-alert">
                        <span>{{ session('success') }}</span>
                    </div>
                @else
                    <div class="role-alert">
                        <span id="roleAlert">Status : Sistem Manajemen User berjalan dengan baik</span>
                    </div>
                @endif

                <form method="GET"
                    action="{{ route('admin.access.index') === request()->url() ? '' : request()->url() }}"
                    id="filterForm" class="search-container" style="display: flex; gap: 15px; align-items: center;">
                    <div class="filter-box">
                        <select id="filterStatus" name="status"
                            style="padding: 10px 15px; border-radius: 8px; border: 1px solid #E2E8F0; outline: none; font-family: 'Poppins', sans-serif; color: #64748B; cursor: pointer;">
                            <option value="all" @selected($status === 'all')>Semua Status</option>
                            <option value="active" @selected($status === 'active')>Aktif</option>
                            <option value="inactive" @selected($status === 'inactive')>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchUser" name="search" placeholder="Search User"
                            value="{{ $search }}">
                        <i class="bi bi-search"></i>
                    </div>
                </form>

                <div class="table-container">
                    <table class="role-table" id="roleTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>@include('admin.users.partials.sort-header', [
                                    'column' => 'name',
                                    'label' => 'Nama',
                                ])</th>
                                <th>@include('admin.users.partials.sort-header', [
                                    'column' => 'email',
                                    'label' => 'Email',
                                ])</th>
                                <th>No. HP</th>
                                <th>@include('admin.users.partials.sort-header', [
                                    'column' => 'created_at',
                                    'label' => 'Waktu Registrasi',
                                ])</th>
                                <th>Role Saat Ini</th>
                                <th>Aksi</th>
                                <th>@include('admin.users.partials.sort-header', [
                                    'column' => 'is_active',
                                    'label' => 'Status',
                                ])</th>
                            </tr>
                        </thead>
                        <tbody id="roleTableBody">
                            @forelse ($users as $index => $user)
                                @php
                                    $roleName = $user->roles->first()?->name;
                                    $roleLabel = ($user->is_active && $roleName) ?
                                        ucfirst(str_replace('_', ' ', $roleName)) :
                                        '-';
                                @endphp
                                <tr data-user-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}" data-no-hp="{{ $user->no_hp }}"
                                    data-role="{{ $roleName ?? '' }}">
                                    <td>{{ $users->firstItem() + $index }}.</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->no_hp ?? '-' }}</td>
                                    <td>
                                        <div class="reg-date">
                                            {{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</div>
                                        <div class="reg-time">{{ $user->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="current-role">{{ $roleLabel }}</td>
                                    <td class="action">
                                        <button class="btn-set-role" onclick="openRoleModal(this)">Set Role</button>
                                    </td>
                                    <td class="status">
                                        <button class="btn-status {{ $user->is_active ? 'active' : 'inactive' }}"
                                            onclick="toggleStatus(this)">
                                            <i
                                                class="bi {{ $user->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                            {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center; padding:24px; color:#94a3b8;">
                                        Tidak ada user yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $users->links('vendor.pagination.custom') }}
            </div>
        </main>
    </div>

    {{-- MODAL SET ROLE --}}
    <div class="modal-overlay" id="roleModal">
        <div class="role-modal">
            <div class="modal-header">
                <h2>Edit User</h2>
                <button type="button" class="modal-close" onclick="closeRoleModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="email" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Pegawai</label>
                    <input type="text" id="namaPegawai" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>No. Handphone</label>
                    <input type="text" id="noHpPegawai" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="roleSelect" class="form-control">
                        <option value="" disabled>Pilih</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="teknisi">Teknisi</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-save" onclick="saveRole()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const updateRoleUrlTemplate = "{{ route('admin.users.updateRole', ['user' => '__ID__']) }}";
        const toggleStatusUrlTemplate = "{{ route('admin.users.toggleStatus', ['user' => '__ID__']) }}";

        // ---- FILTER: search & status → reload via query string (server-side) ----
        function debounce(fn, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            params.set('search', document.getElementById('searchUser').value);
            params.set('status', document.getElementById('filterStatus').value);
            params.delete('page'); // reset ke halaman 1 tiap filter berubah
            window.location.search = params.toString();
        }

        document.getElementById('searchUser').addEventListener('keyup', debounce(applyFilters, 500));
        document.getElementById('filterStatus').addEventListener('change', applyFilters);
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });

        // ---- MODAL SET ROLE ----
        let selectedRow = null;

        function openRoleModal(button) {
            selectedRow = button.closest('tr');
            document.getElementById('namaPegawai').value = selectedRow.dataset.name;
            document.getElementById('email').value = selectedRow.dataset.email || '';
            document.getElementById('noHpPegawai').value = selectedRow.dataset.noHp || '-';
            document.getElementById('roleSelect').value = selectedRow.dataset.role || '';
            document.getElementById('roleModal').classList.add('show');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.remove('show');
        }

        function saveRole() {
            if (!selectedRow) return;

            const userId = selectedRow.dataset.userId;
            const selectedRole = document.getElementById('roleSelect').value;
            const inputName = selectedRow.dataset.name;

            if (!selectedRole) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Role Belum Dipilih',
                    text: 'Silakan pilih role terlebih dahulu.',
                    confirmButtonColor: '#0066DC'
                });
                return;
            }

            Swal.fire({
                icon: 'question',
                title: 'Yakin Ingin Mengganti Role?',
                html: `Role user <strong>${inputName}</strong> akan diubah menjadi <strong>${selectedRole}</strong>.`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Ganti Role',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0066DC',
                cancelButtonColor: '#64748B',
                reverseButtons: true
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(updateRoleUrlTemplate.replace('__ID__', userId), {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            role: selectedRole,
                            id_karyawan: email
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message || 'Gagal menyimpan.');

                        // update DOM sesuai response, tanpa perlu reload halaman
                        selectedRow.dataset.role = selectedRole;
                        selectedRow.dataset.email = email;
                        selectedRow.querySelector('.current-role').textContent =
                            selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1).replace('_', ' ');

                        const statusBtn = selectedRow.querySelector('.status .btn-status');
                        statusBtn.classList.remove('inactive');
                        statusBtn.classList.add('active');
                        statusBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Aktif';

                        closeRoleModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Role Berhasil Diubah',
                            text: data.message,
                            confirmButtonColor: '#0066DC',
                            timer: 2500
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message,
                            confirmButtonColor: '#dc3545'
                        });
                    });
            });
        }

        // ---- TOGGLE STATUS AKTIF/NON-AKTIF ----
        function toggleStatus(button) {
            const row = button.closest('tr');
            const userId = row.dataset.userId;
            const name = row.dataset.name;
            const isCurrentlyActive = button.classList.contains('active');

            const config = isCurrentlyActive ? {
                icon: 'warning',
                title: 'Cabut Akses User?',
                html: `Yakin ingin menonaktifkan user <strong>${name}</strong>?`,
                confirmButtonText: 'Ya, Non-Aktifkan',
                confirmButtonColor: '#dc3545'
            } : {
                icon: 'question',
                title: 'Aktifkan / Approve User?',
                html: `Berikan akses login untuk <strong>${name}</strong>?`,
                confirmButtonText: 'Ya, Aktifkan',
                confirmButtonColor: '#0066DC'
            };

            Swal.fire({
                ...config,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                cancelButtonColor: '#64748B',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(toggleStatusUrlTemplate.replace('__ID__', userId), {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message);

                        if (data.is_active) {
                            button.classList.replace('inactive', 'active');
                            button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Aktif';
                        } else {
                            button.classList.replace('active', 'inactive');
                            button.innerHTML = '<i class="bi bi-x-circle-fill"></i> Non-Aktif';
                        }

                        row.querySelector('.current-role').textContent = data.role_label;

                        Swal.fire({
                            icon: 'success',
                            title: data.is_active ? 'User Diaktifkan' : 'Akses Dicabut',
                            confirmButtonColor: '#0066DC',
                            timer: 2000
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message,
                            confirmButtonColor: '#dc3545'
                        });
                    });
            });
        }

        document.getElementById('roleModal').addEventListener('click', function(event) {
            if (event.target === this) closeRoleModal();
        });
    </script>
</body>

</html>
