<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - PLN Icon Plus</title>

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- GOOGLE FONT - POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/users.css'])

</head>

<body>

    <div class="app-container">

        <!-- SIDEBAR COMPONENT -->
        <x-sidebar />

        <main class="main-content">

            <!-- TOPBAR COMPONENT -->
            <x-topbar />

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- CONTENT LAYOUT -->
            <div class="users-content" style="padding: 24px;">

                @if (session('success'))
                    <div class="mb-4 p-3"
                        style="background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 16px;">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- PAGE TITLE -->
                <div class="users-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 24px;">
                    <div class="users-title-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h1>Manajemen User &amp; Role</h1>
                        <p>Kelola akun pengguna dan hak akses</p>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-card">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role Saat Ini</th>
                                <th>Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username ?? '-' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="role-badge">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST"
                                            style="display: flex; gap: 8px;">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="role-select">
                                                <option value="karyawan" @selected($user->hasRole('karyawan'))>Karyawan</option>
                                                <option value="teknisi" @selected($user->hasRole('teknisi'))>Teknisi</option>
                                                <option value="super_admin" @selected($user->hasRole('super_admin'))>Super Admin
                                                </option>
                                            </select>
                                            <button type="submit" class="btn-simpan">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding: 16px;">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle.querySelector('i');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-collapsed');
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        sidebarIcon.classList.replace('bi-list', 'bi-chevron-right');
                    } else {
                        sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
                    }
                });
            }
        });
    </script>

</body>

</html>
