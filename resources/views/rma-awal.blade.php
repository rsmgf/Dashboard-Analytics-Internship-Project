<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form RMA - PLN Icon Plus</title>

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    @vite([
        'resources/css/sidebar.css',
        'resources/css/rma-awal.css'
    ])
</head>

<body>

<div class="app-container">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar" id="sidebar">
        <!-- LOGO -->
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
        </div>

        <!-- DASHBOARD -->
        <div class="sidebar-section">
            <div class="section-title">Dashboard</div>
            <a href="#" class="sidebar-menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- GENERAL -->
        <div class="sidebar-section">
            <div class="section-title">General</div>
            <a href="#" class="sidebar-menu">
                <i class="bi bi-shield-fill"></i>
                <span>POP</span>
            </a>
            <a href="#" class="sidebar-menu active">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Form RMA</span>
            </a>
        </div>

        <!-- KONFIGURASI AKUN -->
        <div class="sidebar-section">
            <div class="sidebar-menu sidebar-dropdown" id="accountConfigToggle">
                <i class="bi bi-gear-fill"></i>
                <span>Konfigurasi Akun</span>
                <i class="bi bi-chevron-down dropdown-arrow" id="accountConfigArrow"></i>
            </div>
            <div class="sidebar-submenu" id="accountConfigMenu">
                <a href="#" class="sidebar-submenu-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Manajemen Menu</span>
                </a>
                <a href="#" class="sidebar-submenu-item">
                    <i class=" bi bi-people-fill"></i>
                    <span>Manajemen User</span>
                </a>
            </div>
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
                <div class="user-info">
                    <span class="user-name">
                        {{ Auth::check() ? Auth::user()->name : 'Nama User' }}
                    </span>
                    <span class="user-email">
                        {{ Auth::check() ? Auth::user()->email : 'user@gmail.com' }}
                    </span>
                </div>
                <div class="user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </header>


        <!-- CONTENT -->
        <div class="rma-page">

            <!-- =================================================
                 PAGE HEADER (Dikembalikan)
            ================================================== -->
            <div class="page-header">
                <div class="page-title">
                    <div class="title-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h1>Form RMA</h1>
                        <p>Return Material Authorization</p>
                    </div>
                </div>

                <!-- BUTTON TAMBAH RMA -->
                <a href="#" class="btn-tambah">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah RMA</span>
                </a>
            </div>

            <!-- ALERT BANNER -->
            <div class="alert-info-custom">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>Hanya form yang Anda isi yang bisa dilihat pada halaman ini</span>
            </div>

            <!-- TABLE CARD CONTAINER -->
            <div class="table-card">
                
                <!-- JUDUL RIWAYAT RMA DI DALAM FORM -->
                <div class="history-header">
                    <h2>Riwayat RMA</h2>
                </div>

                <!-- SEARCH & FILTER CONTROLS -->
                <div class="table-controls">
                    <div class="search-wrapper">
                        <input type="text" placeholder="Cari No. RMA">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="filter-wrapper">
                        <input type="text" placeholder="Filter RMA">
                        <i class="bi bi-calendar"></i>
                    </div>
                </div>

                <!-- TABLE CONTENT -->
                <div class="table-responsive">
                    <table class="rma-table">
                        <thead>
                            <tr>
                                <th>No. RMA</th>
                                <th>Tanggal Pengisian</th>
                                <th>ID POP</th>
                                <th>Merk/Type</th>
                                <th class="text-center">Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- LOOPING DATA DI SINI (Contoh 8 baris sesuai gambar) -->
                            @for ($i = 0; $i < 8; $i++)
                            <tr>
                                <td><strong>RMA-250807-001</strong></td>
                                <td>
                                    <strong>07-Aug 2026</strong>
                                    <span class="text-sub">08:45 WIB</span>
                                </td>
                                <td>
                                    POP_1MBN10004_KASANG PUDAK KUMPEH<br>
                                    ULU 1/PAYO SELINCAH GI SHELTER 2 OLT
                                </td>
                                <td>
                                    <strong>EMERSON Netsure 531</strong>
                                    <span class="text-sub">Rectifier</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-lihat">Lihat</button>
                                    <button type="button" class="btn-cetak">
                                        <i class="bi bi-printer-fill"></i> Cetak
                                    </button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="pagination-footer">
                    <div>Menampilkan 1-8 dari 18 data</div>
                    <div class="pagination-controls">
                        <button type="button">&lt;</button>
                        <button type="button" class="active">1</button>
                        <button type="button">2</button>
                        <button type="button">3</button>
                        <button type="button">&gt;</button>
                    </div>
                </div>

            </div>

        </div>

    </main>
</div>

<!-- =====================================================
     JAVASCRIPT
====================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* SIDEBAR TOGGLE */
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarIcon = sidebarToggle.querySelector('i');

    sidebarToggle.addEventListener('click', function () {
        document.body.classList.toggle('sidebar-collapsed');
        if (document.body.classList.contains('sidebar-collapsed')) {
            sidebarIcon.classList.replace('bi-list', 'bi-chevron-right');
        } else {
            sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
        }
    });

    /* KONFIGURASI AKUN TOGGLE */
    const accountToggle = document.getElementById('accountConfigToggle');
    const accountMenu = document.getElementById('accountConfigMenu');
    const accountArrow = document.getElementById('accountConfigArrow');

    if (accountToggle) {
        accountToggle.addEventListener('click', function () {
            accountMenu.classList.toggle('show');
            if (accountMenu.classList.contains('show')) {
                accountArrow.classList.replace('bi-chevron-down', 'bi-chevron-up');
            } else {
                accountArrow.classList.replace('bi-chevron-up', 'bi-chevron-down');
            }
        });
    }
});
</script>

</body>
</html>