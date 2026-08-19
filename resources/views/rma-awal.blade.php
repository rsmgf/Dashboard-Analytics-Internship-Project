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


        <!-- =================================================
             DASHBOARD
        ================================================== -->
        <div class="sidebar-section">

            <div class="section-title">Dashboard</div>

            <a href="/dashboard"
               class="sidebar-menu {{ request()->is('dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid-fill"></i>

                <span>Dashboard</span>

            </a>

        </div>


        <!-- =================================================
             GENERAL
        ================================================== -->
        <div class="sidebar-section">

            <div class="section-title">General</div>

            <!-- POP -->
            <a href="/pops"
               class="sidebar-menu {{ request()->is('pops*') ? 'active' : '' }}">

                <i class="bi bi-shield-fill"></i>

                <span>POP</span>

            </a>


            <!-- FORM RMA -->
            <a href="{{ route('rma.index') }}"
               class="sidebar-menu {{ request()->is('rma*') ? 'active' : '' }}">

                <i class="bi bi-file-earmark-text-fill"></i>

                <span>Form RMA</span>

            </a>

        </div>


        <!-- =================================================
             KONFIGURASI AKUN
        ================================================== -->
        <div class="sidebar-section">

            <div class="sidebar-menu sidebar-dropdown
                {{ request()->is('users*') || request()->is('roles*') ? 'active' : '' }}"
                id="accountConfigToggle">

                <i class="bi bi-gear-fill"></i>

                <span>Konfigurasi Akun</span>

                <i class="bi bi-chevron-down dropdown-arrow"
                   id="accountConfigArrow"></i>

            </div>


            <div class="sidebar-submenu
                {{ request()->is('users*') || request()->is('roles*') ? 'show' : '' }}"
                id="accountConfigMenu">

                <a href="/users"
                   class="sidebar-submenu-item
                   {{ request()->is('users*') ? 'active' : '' }}">

                    <i class="bi bi-people-fill"></i>

                    <span>Manajemen User</span>

                </a>


                <a href="/roles"
                   class="sidebar-submenu-item
                   {{ request()->is('roles*') ? 'active' : '' }}">

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


        <!-- =================================================
             TOPBAR
        ================================================== -->
        <header class="topbar">

            <!-- TOGGLE SIDEBAR -->
            <button type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    title="Buka / Tutup Sidebar">

                <i class="bi bi-list"></i>

            </button>


            <!-- USER PROFILE -->
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


        <!-- =================================================
             CONTENT
        ================================================== -->
        <div class="rma-page">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->
            <div class="page-header">

                <div class="page-title">

                    <div class="title-icon">

                        <i class="bi bi-file-earmark-text-fill"></i>

                    </div>


                    <div>

                        <h1>Form RMA</h1>

                        <p>
                            Return Material Authorization
                        </p>

                    </div>

                </div>


                <!-- BUTTON TAMBAH RMA -->
                <a href="{{ route('rma.create') }}"
                   class="btn-tambah">

                    <i class="bi bi-plus-lg"></i>

                    <span>Tambah RMA</span>

                </a>

            </div>


            <!-- =================================================
                 WELCOME CARD
            ================================================== -->
            <div class="welcome-card">

                <div class="welcome-icon">

                    <i class="bi bi-clipboard2-check-fill"></i>

                </div>


                <div class="welcome-content">

                    <h2>
                        Pengisian Return Material Authorization
                    </h2>


                    <p>
                        Gunakan Form RMA untuk melakukan pengajuan
                        pengembalian material atau perangkat yang
                        mengalami kerusakan maupun membutuhkan proses
                        pengembalian.
                    </p>


                    <a href="{{ route('rma.create') }}"
                       class="btn-mulai">

                        Mulai Pengisian RMA

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>


            <!-- =================================================
                 INFORMASI RMA
            ================================================== -->
            <div class="section-heading">

                <h2>Informasi Pengisian RMA</h2>

                <p>
                    Pastikan data dan dokumen yang diperlukan
                    telah disiapkan sebelum melakukan pengisian.
                </p>

            </div>


            <!-- INFORMATION GRID -->
            <div class="info-grid">


                <!-- DATA MATERIAL -->
                <div class="info-card">

                    <div class="info-card-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>


                    <div>

                        <h3>Data Material</h3>

                        <p>
                            Siapkan nomor dokumen, merk, tipe,
                            serial number, dan material number
                            perangkat.
                        </p>

                    </div>

                </div>


                <!-- KONDISI MATERIAL -->
                <div class="info-card">

                    <div class="info-card-icon">

                        <i class="bi bi-exclamation-triangle"></i>

                    </div>


                    <div>

                        <h3>Kondisi Material</h3>

                        <p>
                            Tentukan jenis kerusakan material
                            sesuai dengan kondisi perangkat.
                        </p>

                    </div>

                </div>


                <!-- BUKTI PENGEMBALIAN -->
                <div class="info-card">

                    <div class="info-card-icon">

                        <i class="bi bi-cloud-arrow-up"></i>

                    </div>


                    <div>

                        <h3>Bukti Pengembalian</h3>

                        <p>
                            Siapkan foto material dan tanda tangan
                            Engineer sebagai bukti pengembalian.
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 RIWAYAT
            ================================================== -->
            <div class="history-section">

                <div class="history-header">

                    <div>

                        <h2>Riwayat Pengisian RMA</h2>

                        <p>
                            Lihat data RMA yang telah dibuat sebelumnya.
                        </p>

                    </div>


                    <a href="{{ route('rma.history') }}"
                       class="btn-history">

                        Lihat Riwayat

                        <i class="bi bi-arrow-right"></i>

                    </a>

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


    /* =================================================
       SIDEBAR TOGGLE
    ================================================== */

    const sidebarToggle =
        document.getElementById('sidebarToggle');

    const sidebarIcon =
        sidebarToggle.querySelector('i');


    sidebarToggle.addEventListener('click', function () {

        document.body.classList.toggle(
            'sidebar-collapsed'
        );


        if (
            document.body.classList.contains(
                'sidebar-collapsed'
            )
        ) {

            sidebarIcon.classList.replace(
                'bi-list',
                'bi-chevron-right'
            );

        } else {

            sidebarIcon.classList.replace(
                'bi-chevron-right',
                'bi-list'
            );

        }

    });


    /* =================================================
       KONFIGURASI AKUN
    ================================================== */

    const accountToggle =
        document.getElementById(
            'accountConfigToggle'
        );

    const accountMenu =
        document.getElementById(
            'accountConfigMenu'
        );

    const accountArrow =
        document.getElementById(
            'accountConfigArrow'
        );


    if (accountToggle) {

        accountToggle.addEventListener(
            'click',
            function () {

                accountMenu.classList.toggle(
                    'show'
                );


                if (
                    accountMenu.classList.contains(
                        'show'
                    )
                ) {

                    accountArrow.classList.replace(
                        'bi-chevron-down',
                        'bi-chevron-up'
                    );

                } else {

                    accountArrow.classList.replace(
                        'bi-chevron-up',
                        'bi-chevron-down'
                    );

                }

            }
        );

    }

});

</script>

</body>

</html>