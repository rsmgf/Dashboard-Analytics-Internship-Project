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

    @vite(['resources/css/sidebar.css', 'resources/css/pop.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        svg.w-5.h-5 {
            width: 16px;
            height: 16px;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>

<body>

    <div class="app-container">

        <!-- SIDEBAR COMPONENT -->
        <x-sidebar />

        <main class="main-content">

            <!-- TOPBAR COMPONENT -->
            <x-topbar />

            <div class="pop-content">

                <!-- PAGE TITLE -->
                <div class="pop-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="pop-title-wrapper" style="display: flex; align-items: center; gap: 15px;">
                        <div class="pop-title-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h1>List POP</h1>
                            <p>List Point of Presence</p>
                        </div>
                    </div>

                    @can('pops.index.create')
                        <a href="{{ route('pops.create') }}" class="btn-tambah-pop">
                            <i class="bi bi-plus-lg"></i> Tambah POP
                        </a>
                    @endcan
                </div>

                <div class="filter-section">
                    <div class="search-wrapper">
                        <!-- Jika ke depannya ingin search berfungsi, bungkus input ini dengan <form method="GET" action="/pops"> -->
                        <input type="text" id="searchPOP" name="search" class="search-input"
                            placeholder="Cari data..." value="{{ request('search') }}">
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
                                <th>Tipe POP</th>
                                <th style="text-align: center;">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="popTableBody">
                            @forelse($pops as $index => $pop)
                                <tr>
                                    <td>{{ $pops->firstItem() + $index }}.</td>
                                    <td>{{ $pop->provinsi ?? 'Jambi' }}</td>
                                    <td>{{ $pop->kota_kabupaten }}</td>
                                    <td>{{ $pop->kode_pop }}</td>
                                    <td>{{ $pop->nama_pop }}</td>
                                    <td>{{ $pop->jenis_bangunan }}</td>
                                    <td>{{ $pop->tipe_pop ?? 'POP-SB' }}</td>
                                    <td style="text-align: center;">
                                        <div
                                            style="display: flex; gap: 6px; justify-content: center; align-items: center;">

                                            <!-- Tombol Lihat — semua role bisa lihat detail -->
                                            <button type="button" class="btn-detail"
                                                onclick="lihatPOP('{{ $pop->id }}')">Lihat</button>

                                            @can('pops.index.update')
                                                <a href="{{ route('pops.edit', $pop->id) }}" class="btn-edit"><i
                                                        class="bi bi-pencil-fill"></i></a>
                                            @endcan

                                            @can('pops.index.delete')
                                                <button type="button" title="Hapus POP"
                                                        onclick="openDeleteModal('{{ route('pops.destroy', $pop->id) }}', '{{ $pop->nama_pop }}')" class="btn-hapus"><i
                                                        class="bi bi-trash3-fill"></i></button>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">Belum
                                        ada data POP.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- WRAPPER PAGINATION -->
                    <div class="pagination-container">
                        <div>
                            Menampilkan {{ $pops->firstItem() ?? 0 }} - {{ $pops->lastItem() ?? 0 }} dari
                            {{ $pops->total() }} data
                        </div>

                        <div>
                            <!-- Panggil paginasi dengan format Bootstrap-4 agar tag HTML-nya sesuai dengan CSS kita -->
                            {{ $pops->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <<<<<<< HEAD <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle ? sidebarToggle.querySelector('i') : null;

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
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
                accountConfigToggle.addEventListener('click', function() {
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
                searchInput.addEventListener('keyup', function() {
                    const keyword = this.value.toLowerCase();
                    const rows = tableBody.querySelectorAll('tr');

                    rows.forEach(function(row) {
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

        ===
        ===
        =
        {{-- Hidden form untuk submit DELETE — action diisi oleh SweetAlert2 --}}
            <
            form id = "deleteForm"
        method = "POST"
        style = "display:none;" >
            @csrf
        @method('DELETE')
            <
            /form>

            <
            !--JAVASCRIPT-- >
            <
            script >
            // FUNGSI DETAIL POP DINAMIS BERDASARKAN FILTER
            >>>
            >>>
            >
            0 dd85777157429ea593723e2939a41ab5010977f

        function lihatPOP(idPOP) {
            const filterValue = document.getElementById('mainFilter').value;
            let targetUrl = '';

            switch (filterValue) {
                case 'Rectifier':
                    targetUrl = `/pops/${idPOP}/rectifiers`;
                    break;
                case 'AC':
                    targetUrl = `/pops/${idPOP}/ac`;
                    break;
                case 'Battery':
                    targetUrl = `/pops/${idPOP}/battery`;
                    break;
                case 'kWh':
                    targetUrl = `/pops/${idPOP}/kwh`;
                    break;
                default:
                    targetUrl = `/pops/${idPOP}/rectifiers`;
                    break;
            }

            window.location.href = targetUrl;
        }

        // ── KONFIRMASI HAPUS — SweetAlert2 ──
        function openDeleteModal(url, namaPOP) {
            Swal.fire({
                title: 'Hapus Data POP?',
                html: `Anda akan menghapus: <strong>${namaPOP}</strong><br>
                       Tindakan ini <span style="color:#DC2626;font-weight:600;">tidak bisa dibatalkan</span>.`,
                icon: 'warning',
                iconColor: '#DC2626',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                buttonsStyling: true,
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'swal-popup-custom',
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-custom',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').action = url;
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
    <<<<<<< HEAD=======<style>
        /* Kustomisasi SweetAlert2 agar sesuai font & tema web */
        .swal-popup-custom { font-family: 'Poppins', sans-serif; border-radius: 16px !important; }
        .swal-title-custom { font-size: 1.15rem !important; font-weight: 700 !important; color: #111827 !important; }
        .swal-html-custom { font-size: 0.875rem !important; color: #6b7280 !important; line-height: 1.6 !important; }
        .swal-btn-confirm,
        .swal-btn-cancel { font-family: 'Poppins', sans-serif !important; font-weight: 600 !important; border-radius:
        8px !important; padding: 10px 20px !important; }
        </style>

        >>>>>>> 0dd85777157429ea593723e2939a41ab5010977f
</body>

</html>
