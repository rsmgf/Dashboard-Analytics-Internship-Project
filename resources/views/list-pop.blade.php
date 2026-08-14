<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List POP - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/pop.css'])
</head>
<body>
  <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
            </div>

        <!-- =================================================
            DASHBOARD
        ================================================== -->
        <div class="sidebar-section">
            <div class="section-title">Dashboard</div>
            <a href="/dashboard" class="sidebar-menu {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- =================================================
            GENERAL
        ================================================== -->
        <div class="sidebar-section">
            <div class="section-title">General</div>
            
            <!-- Menu POP akan aktif jika URL mengandung /pops -->
            <a href="/pops" class="sidebar-menu {{ request()->is('pops*') ? 'active' : '' }}">
                <i class="bi bi-shield-fill"></i>
                <span>POP</span>
            </a>
            
            <!-- Menu Form RMA akan aktif jika URL mengandung /rma -->
            <a href="/rma" class="sidebar-menu {{ request()->is('rma*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Form RMA</span>
            </a>
        </div>

    <!-- =================================================
        KONFIGURASI AKUN
    ================================================== -->
    <div class="sidebar-section">
        <!-- Toggle akun tetap bisa diatur terbuka otomatis jika sedang di halaman users/roles -->
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
        
        <a href="#" class="sidebar-menu">
            <i class="bi bi-box-arrow-right"></i>
            <span>Log Out</span>
        </a>
    </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div class="user-profile">
                    <span>{{ Auth::check() ? Auth::user()->name : 'Super Admin' }}</span>
                    <div style="width: 38px; height: 38px; background-color: #087cf5; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-fill" style="font-size: 20px;"></i>
                    </div>
                </div>
            </header>

            <div class="pop-content">
                <div class="pop-header">
                    <div class="pop-title-wrapper">
                        <div class="pop-title-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h1>List POP</h1>
                            <p>List Point of Presence</p>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                    <div class="pop-action">
                        <a href="/pops/create" class="btn-tambah-pop">
                            <i class="bi bi-plus-lg"></i> Tambah POP
                        </a>
                    </div>
                    @endif
                </div>

                <div class="filter-section">
                    <div class="search-wrapper">
                        <input type="text" id="searchPOP" class="search-input" placeholder="Cari data...">
                    </div>
                    <div class="filter-wrapper">
                        <label class="filter-label">Filter harus dipilih <span>*</span></label>
                        <select id="mainFilter" class="filter-control">
                            <option value="Rectifier">Rectifier</option>
                            <option value="kWh">kWh</option>
                            <option value="Battery">Battery</option>
                            <option value="AC">AC</option>
                        </select>
                    </div>
                </div>

                <div class="table-card">
                    <table class="pop-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Provinsi</th>
                                <th>Kota/Kabupaten</th>
                                <th>ID POP</th>
                                <th>Nama POP</th>
                                <th>Building</th>
                                <th>Type POP</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody id="popTableBody">
                            @for($i = 1; $i <= 14; $i++)
                            <tr>
                                <td>{{ $i }}.</td>
                                <td>Jambi</td>
                                <td>Tanjung Jabung Barat</td>
                                <td>POP_1MBN10004</td>
                                <td>
                                    KASANG PUDAK KUMPEH ULU 1<br>
                                    PAYO SELINCAH GI SHELTER 2 OLT
                                </td>
                                <td>Shelter Permanent</td>
                                <td>POP-SB</td>
                                <td>
                                    <button type="button" class="btn-detail" onclick="lihatPOP('POP_1MBN10004')">Lihat</button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle ? sidebarToggle.querySelector('i') : null;

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                    if (sidebarIcon) {
                        if (isCollapsed) {
                            sidebarIcon.classList.remove('bi-list');
                            sidebarIcon.classList.add('bi-chevron-right');
                        } else {
                            sidebarIcon.classList.remove('bi-chevron-right');
                            sidebarIcon.classList.add('bi-list');
                        }
                    }

                    if (isCollapsed) {
                        const accountMenu = document.getElementById('accountConfigMenu');
                        const accountToggle = document.getElementById('accountConfigToggle');
                        const accountArrow = document.getElementById('accountConfigArrow');

                        if (accountMenu) {
                            accountMenu.classList.remove('show');
                        }

                        if (accountToggle) {
                            accountToggle.classList.remove('active');
                        }

                        if (accountArrow) {
                            accountArrow.classList.remove('bi-chevron-up');
                            accountArrow.classList.add('bi-chevron-down');
                        }
                    }
                });
            }

            const accountConfigToggle = document.getElementById('accountConfigToggle');
            const accountConfigMenu = document.getElementById('accountConfigMenu');
            const accountConfigArrow = document.getElementById('accountConfigArrow');

            if (accountConfigToggle && accountConfigMenu) {
                accountConfigToggle.addEventListener('click', function () {
                    const isOpen = accountConfigMenu.classList.toggle('show');
                    
                    accountConfigToggle.classList.toggle('active', isOpen);

                    if (accountConfigArrow) {
                        if (isOpen) {
                            accountConfigArrow.classList.remove('bi-chevron-down');
                            accountConfigArrow.classList.add('bi-chevron-up');
                        } else {
                            accountConfigArrow.classList.remove('bi-chevron-up');
                            accountConfigArrow.classList.add('bi-chevron-down');
                        }
                    }
                });
            }

            const searchInput = document.getElementById('searchPOP');
            const tableBody = document.getElementById('popTableBody');

            if (searchInput && tableBody) {
                searchInput.addEventListener('keyup', function () {
                    const keyword = this.value.toLowerCase();
                    const rows = tableBody.querySelectorAll('tr');

                    rows.forEach(function (row) {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(keyword)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        function lihatPOP(idPOP) {
            alert('Detail POP\n\nID POP : ' + idPOP);
        }
    </script>
</body>
</html>