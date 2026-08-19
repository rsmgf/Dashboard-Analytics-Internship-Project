<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah POP - PLN Icon Plus</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Pastikan file CSS ini di-load dengan benar melalui Vite -->
    @vite(['resources/css/sidebar.css', 'resources/css/add-pop.css'])

    <style>
        /* Gaya tambahan untuk avatar profile */
        .profile-avatar {
            width: 38px;
            height: 38px;
            background-color: #087cf5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar i {
            font-size: 20px;
        }
    </style>
</head>
<body>

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
        </div>

        <!-- DASHBOARD -->
        <div class="sidebar-section">
            <div class="section-title">Dashboard</div>
            <a href="/dashboard" class="sidebar-menu {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- GENERAL -->
        <div class="sidebar-section">
            <div class="section-title">General</div>
            <a href="/pops" class="sidebar-menu {{ request()->is('pops*') ? 'active' : '' }}">
                <i class="bi bi-shield-fill"></i>
                <span>POP</span>
            </a>
            <a href="/rma" class="sidebar-menu {{ request()->is('rma*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Form RMA</span>
            </a>
        </div>

        <!-- KONFIGURASI AKUN -->
        <div class="sidebar-section">
            <div class="sidebar-menu sidebar-dropdown {{ request()->is('users*') || request()->is('roles*') ? 'active' : '' }}" id="accountConfigToggle">
                <i class="bi bi-gear-fill"></i>
                <span>Konfigurasi Akun</span>
                <i class="bi bi-chevron-{{ request()->is('users*') || request()->is('roles*') ? 'up' : 'down' }} dropdown-arrow" id="accountConfigArrow"></i>
            </div>

            <div class="sidebar-submenu {{ request()->is('users*') || request()->is('roles*') ? 'show' : '' }}" id="accountConfigMenu">
                <a href="/users" class="sidebar-submenu-item {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="/roles" class="sidebar-submenu-item {{ request()->is('roles*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Manajemen Role</span>
                </a>
            </div>

            <!-- LOGOUT -->
            <a href="#" class="sidebar-menu">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->
    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="user-profile">
                <span>{{ Auth::check() ? Auth::user()->name : 'Super Admin' }}</span>
                <div class="profile-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </header>

        <!-- =====================================================
             CONTENT ADD POP
        ====================================================== -->
        <div class="add-pop-content">

            <!-- FORM CARD -->
            < class="add-pop-card">

                <!-- INFO -->
                <div class="add-pop-alert">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Form ini hanya bisa diisi oleh Manajer Unit</span>
                </div>

                <!-- HEADER -->
                <div>
                    <h1> Form RMA </h1>
                    <p> Return Material Authorization </p>
                </div>

                <div class="add-pop-title">
                    <h1>Add Point of Presence</h1>
                    <p>Tambah POP</p>
                </div>

                <!-- FORM -->
                <form id="addPopForm">
                    
                    <!-- PROVINSI -->
                    <div class="add-pop-group">
                        <label for="provinsi">Provinsi <span class="add-pop-required">*</span></label>
                        <input type="text" id="provinsi" name="provinsi" class="add-pop-input" placeholder="Masukkan Provinsi" required>
                    </div>

                    <!-- KOTA / KABUPATEN -->
                    <div class="add-pop-group">
                        <label for="kota">Kota/Kabupaten <span class="add-pop-required">*</span></label>
                        <input type="text" id="kota" name="kota" class="add-pop-input" placeholder="Masukkan Kota/Kabupaten" required>
                    </div>

                    <!-- ID POP -->
                    <div class="add-pop-group">
                        <label for="id_pop">ID POP <span class="add-pop-required">*</span></label>
                        <input type="text" id="id_pop" name="id_pop" class="add-pop-input" placeholder="Masukkan ID POP" required>
                    </div>

                    <!-- NAMA POP -->
                    <div class="add-pop-group">
                        <label for="nama_pop">Nama POP <span class="add-pop-required">*</span></label>
                        <input type="text" id="nama_pop" name="nama_pop" class="add-pop-input" placeholder="Masukkan Nama POP" required>
                    </div>

                    <!-- BUILDING -->
                    <div class="add-pop-group">
                        <label for="building">Building <span class="add-pop-required">*</span></label>
                        <div class="add-pop-select-wrapper">
                            <select id="building" name="building" class="add-pop-select" required>
                                <option value="" selected disabled>Pilih Building</option>
                                <option value="Shelter Permanent">Shelter Permanent</option>
                                <option value="Shelter Temporary">Shelter Temporary</option>
                                <option value="Building">Building</option>
                                <option value="Outdoor">Outdoor</option>
                            </select>
                            <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                        </div>
                    </div>

                    <!-- TYPE POP -->
                    <div class="add-pop-group">
                        <label for="type_pop">Type POP <span class="add-pop-required">*</span></label>
                        <div class="add-pop-select-wrapper">
                            <select id="type_pop" name="type_pop" class="add-pop-select" required>
                                <option value="" selected disabled>Pilih Type POP</option>
                                <option value="POP-SB">POP-SB</option>
                                <option value="POP-DC">POP-DC</option>
                                <option value="POP-ODC">POP-ODC</option>
                                <option value="POP-FO">POP-FO</option>
                            </select>
                            <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="add-pop-actions">
                        <a href="/pops" class="add-pop-btn-cancel">Batal</a>
                        <button type="submit" class="add-pop-btn-save">
                            <i class="bi bi-check-lg"></i> Simpan POP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ==========================================
               SIDEBAR TOGGLE
            ================================================ */
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle ? sidebarToggle.querySelector('i') : null;

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                    if (sidebarIcon) {
                        if (isCollapsed) {
                            sidebarIcon.classList.replace('bi-list', 'bi-chevron-right');
                        } else {
                            sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
                        }
                    }

                    // Tutup submenu saat sidebar ditutup
                    if (isCollapsed) {
                        const accountMenu = document.getElementById('accountConfigMenu');
                        const accountToggle = document.getElementById('accountConfigToggle');
                        const accountArrow = document.getElementById('accountConfigArrow');

                        if (accountMenu) accountMenu.classList.remove('show');
                        if (accountToggle) accountToggle.classList.remove('active');
                        if (accountArrow) accountArrow.classList.replace('bi-chevron-up', 'bi-chevron-down');
                    }
                });
            }

            /* ==========================================
               KONFIGURASI AKUN TOGGLE
            ================================================ */
            const accountConfigToggle = document.getElementById('accountConfigToggle');
            const accountConfigMenu = document.getElementById('accountConfigMenu');
            const accountConfigArrow = document.getElementById('accountConfigArrow');

            if (accountConfigToggle && accountConfigMenu) {
                accountConfigToggle.addEventListener('click', function () {
                    const isOpen = accountConfigMenu.classList.toggle('show');
                    accountConfigToggle.classList.toggle('active', isOpen);

                    if (accountConfigArrow) {
                        if (isOpen) {
                            accountConfigArrow.classList.replace('bi-chevron-down', 'bi-chevron-up');
                        } else {
                            accountConfigArrow.classList.replace('bi-chevron-up', 'bi-chevron-down');
                        }
                    }
                });
            }

            /* ==========================================
               FORM SUBMIT (FRONTEND SAJA)
            ================================================ */
            const form = document.getElementById('addPopForm');

            if (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    alert('Data POP berhasil disiapkan untuk disimpan.');
                    // Logika fetch/axios ke backend bisa ditambahkan di sini
                });
            }
        });
    </script>
</body>
</html>