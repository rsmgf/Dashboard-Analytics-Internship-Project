<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card KWH - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite(['resources/css/sidebar.css', 'resources/css/rectifier-card.css'])
</head>

<body>
    <div class="app-container">

        {{-- SIDEBAR COMPONENT --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            {{-- TOPBAR COMPONENT --}}
            <x-topbar />

            <div class="rectifier-content">
                {{-- Header: Back Button + Breadcrumb As Title + Tombol Tambah --}}
                <div class="rectifier-page-header">
                    <div class="rectifier-page-info">
                        <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="rectifier-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => 'POP Jambi Kota']
                            ]" />
                            <span class="rectifier-pop-sub">Kode: <strong>POP_1MBN10004</strong> &middot; Jambi Kota, Jambi &mdash; 3 KWH</span>
                        </div>
                    </div>

                    <a href="{{ route('kwh.create') }}" class="btn-tambah-rectifier">
                        <i class="bi bi-plus-lg"></i> Tambah KWH
                    </a>
                </div>

                {{-- KWH Grid --}}
                <div class="rectifier-grid">
                    
                    {{-- KWH Card 1 --}}
                    <div class="rectifier-card">
                        <div class="rectifier-card-header">
                            <div class="checklist-icon">
                                <i class="bi bi-speedometer2"></i>
                            </div>

                            <div class="checklist-title">
                                <h3>Checklist KWH</h3>
                                <p>KWH Utama</p>
                            </div>

                            <span class="rectifier-number">
                                KWH #1
                            </span>
                        </div>

                        <div class="rectifier-information">
                            <div class="equipment-info"><strong>Type :</strong> iEM3255</div>
                            <div class="equipment-info"><strong>Phasa :</strong> 3 Phasa</div>
                            <div class="equipment-info serial"><strong>Daya :</strong> 197 kVA</div>
                        </div>

                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-person-fill"></i>
                                <span>Ahmad Teknisi</span>
                            </div>

                            <div class="meta-item">
                                <i class="bi bi-calendar-fill"></i>
                                <span>24 Agu 2026</span>
                            </div>
                        </div>

                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>Admin &middot; 24 Agu 2026, 14:30</span>
                        </div>

                        <div class="rectifier-card-footer">
                            <button type="button" class="btn-hapus" onclick="hapusKwh(1, 'KWH Utama')">
                                <i class="bi bi-trash3-fill"></i> Hapus
                            </button>
                            <a href="{{ route('kwh.detail') }}" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- KWH Card 2 --}}
                    <div class="rectifier-card">
                        <div class="rectifier-card-header">
                            <div class="checklist-icon">
                                <i class="bi bi-speedometer2"></i>
                            </div>

                            <div class="checklist-title">
                                <h3>Checklist KWH</h3>
                                <p>KWH Backup</p>
                            </div>

                            <span class="rectifier-number">
                                KWH #2
                            </span>
                        </div>

                        <div class="rectifier-information">
                            <div class="equipment-info"><strong>Type :</strong> SL7000</div>
                            <div class="equipment-info"><strong>Phasa :</strong> 3 Phasa</div>
                            <div class="equipment-info serial"><strong>Daya :</strong> 131 kVA</div>
                        </div>

                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-person-fill"></i>
                                <span>Budi Santoso</span>
                            </div>

                            <div class="meta-item">
                                <i class="bi bi-calendar-fill"></i>
                                <span>23 Agu 2026</span>
                            </div>
                        </div>

                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>Budi &middot; 23 Agu 2026, 11:15</span>
                        </div>

                        <div class="rectifier-card-footer">
                            <button type="button" class="btn-hapus" onclick="hapusKwh(2, 'KWH Backup')">
                                <i class="bi bi-trash3-fill"></i> Hapus
                            </button>
                            <a href="{{ route('kwh.detail') }}" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- KWH Card 3 --}}
                    <div class="rectifier-card">
                        <div class="rectifier-card-header">
                            <div class="checklist-icon">
                                <i class="bi bi-speedometer2"></i>
                            </div>

                            <div class="checklist-title">
                                <h3>Checklist KWH</h3>
                                <p>KWH Distribusi</p>
                            </div>

                            <span class="rectifier-number">
                                KWH #3
                            </span>
                        </div>

                        <div class="rectifier-information">
                            <div class="equipment-info"><strong>Type :</strong> ZMD405</div>
                            <div class="equipment-info"><strong>Phasa :</strong> 3 Phasa</div>
                            <div class="equipment-info serial"><strong>Daya :</strong> 197 kVA</div>
                        </div>

                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-person-fill"></i>
                                <span>Cahyo Pratama</span>
                            </div>

                            <div class="meta-item">
                                <i class="bi bi-calendar-fill"></i>
                                <span>22 Agu 2026</span>
                            </div>
                        </div>

                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>Cahyo &middot; 22 Agu 2026, 09:45</span>
                        </div>

                        <div class="rectifier-card-footer">
                            <button type="button" class="btn-hapus" onclick="hapusKwh(3, 'KWH Distribusi')">
                                <i class="bi bi-trash3-fill"></i> Hapus
                            </button>
                            <a href="{{ route('kwh.detail') }}" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function hapusKwh(id, kwhName) {
            Swal.fire({
                title: 'Hapus KWH?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${kwhName}"</strong>?<br><small style="color: #64748b;">Data KWh dan riwayat pengukurannya akan dihapus.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `Data "${kwhName}" berhasil dihapus.`,
                        showConfirmButton: false,
                        timer: 1800
                    });
                }
            });
        }
    </script>
</body>

</html>