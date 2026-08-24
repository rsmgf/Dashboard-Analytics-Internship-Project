<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akses - PLN Icon Plus</title>
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
                <x-breadcrumb :items="[['label' => 'Manajemen Akses Role']]" />
                @if (session('success'))
                    <div class="mb-4 p-3"
                        style="background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="access-header">
                    <div class="access-title-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <div>
                        <h1>Manajemen Akses</h1>
                        <p>Atur hak akses menu dan permission (create/read/update/delete) per role</p>
                    </div>
                </div>

                <div class="table-card">
                    <table class="access-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Role</th>
                                <th>Jumlah Permission</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $i => $role)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</td>
                                    <td><span class="role-badge-count">{{ $role->permissions_count }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.access.edit', $role) }}" class="btn-atur-akses">
                                            <i class="bi bi-sliders"></i> Atur Akses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="access-empty">Belum ada role.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
