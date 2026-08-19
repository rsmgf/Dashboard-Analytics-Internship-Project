<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List POP - PLN Icon Plus</title>

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- GOOGLE FONT - POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/pop.css'])

</head>

<body>

    <div class="app-container">

        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR (Disesuaikan dengan rma agar memiliki fungsi toggle) -->
            <header class="topbar" style="display: flex; justify-content: flex-start; align-items: center; gap: 12px; width: 100%; padding-right: 20px;">
                <!-- Tombol Toggle Sidebar (Kiri) -->
                <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Info Profil User (Kanan) -->
                <!-- margin-left: auto; berfungsi untuk mendorong elemen ini mentok ke kanan -->
                <div class="user-profile" style="margin-left: auto; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    
                    <!-- Nama User -->
                    <span style="font-weight: 600; font-size: 14px; color: #333;">
                        {{ Auth::check() ? Auth::user()->name : 'Nama User' }}
                    </span>

                    <!-- Foto/Ikon Profil -->
                    <!-- Gunakan tag <img> jika ada foto, atau pakai ikon Bootstrap jika belum ada -->
                    <div style="width: 38px; height: 38px; background-color: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-fill" style="font-size: 20px;"></i>
                    </div>
                    
                </div>
            </header>

            <!-- CONTENT LAYOUT -->
            <div class="pop-content">

               <!-- PAGE TITLE -->
                <div class="pop-header" style="display: flex; justify-content: space-between; align-items: center;">
                    
                    <!-- Bagian Kiri: Icon dan Judul -->
                    <div class="pop-title-wrapper" style="display: flex; align-items: center; gap: 15px;">
                        <div class="pop-title-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h1>List POP</h1>
                            <p>List Point of Presence</p>
                        </div>
                    </div>

                    <!-- Bagian Kanan: Tombol Tambah (Hanya untuk Super Admin) -->
                    <!-- Asumsi variabel role disimpan di auth()->user()->role -->
                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                    <div class="pop-action">
                        <a href="/pops/create" class="btn-tambah-pop">
                            <i class="bi bi-plus-lg"></i> Tambah POP
                        </a>
                    </div>
                    @endif

                </div>

                <!-- SEARCH & FILTER -->
                <div class="filter-section">
                    <!-- SEARCH -->
                    <div class="search-wrapper">
                        <input type="text" id="searchPOP" class="search-input" placeholder="Cari data...">
                    </div>

                    <!-- FILTER -->
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

                <!-- TABLE -->
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
                            <script>
                                for(let i = 1; i <= 14; i++) {
                                    document.write(`
                                    <tr>
                                        <td>${i}.</td>
                                        <td>Jambi</td>
                                        <td>Tanjung Jabung Barat</td>
                                        <td>POP_1MBN10004</td>
                                        <td>KASANG PUDAK KUMPEH ULU 1/<br>PAYO SELINCAH GI SHELTER 2 OLT</td>
                                        <td>Shelter Permanent</td>
                                        <td>POP-SB</td>
                                        <td>
                                            <button type="button" class="btn-detail" onclick="lihatPOP('POP_1MBN10004')">Lihat</button>
                                        </td>
                                    </tr>
                                    `);
                                }
                            </script>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FUNGSI TOGGLE SIDEBAR (Identik dengan RMA)
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

        // FUNGSI DETAIL POP
        function lihatPOP(idPOP) {
            alert('Detail POP\n\nID POP : ' + idPOP);
        }
    </script>

</body>
</html>
