<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Baterai - PLN Icon Plus</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Vite CSS --}}
    @vite([
        'resources/css/sidebar.css',
        'resources/css/battery-card.css'
    ])
</head>

<body>

<div class="app-container">

    {{-- ================= SIDEBAR ================= --}}
    <x-sidebar />

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    {{-- ================= MAIN ================= --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <x-topbar />

        {{-- CONTENT --}}
        <div class="rectifier-content">

            {{-- HEADER HALAMAN (TANPA KOTAK TABEL) --}}
            <div class="rectifier-page-header">

                <div class="rectifier-page-info">

                    <a href="#"
                       class="back-button"
                       title="Kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <div class="rectifier-header-text">

                        <div class="breadcrumb">
                            <a href="#"><i class="bi bi-house-door-fill"></i></a>
                            <i class="bi bi-chevron-right"></i>
                            <a href="#">POP</a>
                            <i class="bi bi-chevron-right"></i>
                            <span style="color: #0785dc; font-weight: 600;">QUAE DOLORES 5</span>
                        </div>

                        <span class="rectifier-pop-sub">
                            Kode: <strong>POP_FDPD40172</strong> &middot; Kota Sungai Penuh, Jambi &mdash; 2 Baterai
                        </span>

                    </div>

                </div>

                <a href="#"
                   class="btn-tambah-rectifier">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Baterai
                </a>

            </div>


            {{-- ==================================================
                RECTIFIER 1 SECTION
            ================================================== --}}
            <div class="rectifier-section">

                <div class="rectifier-header">
                    <div class="rectifier-title">
                        Rectifier 1 : POP_1SRG012_RECT01
                    </div>
                    <div class="backup-time status-excellent">
                        Performance Backup Time : 6.50
                    </div>
                </div>

                <div class="rectifier-grid" id="batteryGrid1">

                    {{-- CARD 1 (EXCELLENT - Hijau) --}}
                    <div class="rectifier-card battery-card" id="battery-card-1">

                        {{-- Card Header Sesuai Gambar --}}
                        <div class="rectifier-card-header">
                            <div class="card-icon">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <div class="card-title-text">
                                <h3>Daftar pemeriksaan Baterai</h3>
                                <span>Data Baterai</span>
                            </div>
                        </div>

                        <div class="rectifier-information">
                            <div class="equipment-info">
                                <strong>Merk</strong>
                                <span class="data-value">: SACRED SUN</span>
                            </div>
                            <div class="equipment-info">
                                <strong>Tipe</strong>
                                <span class="data-value">: SSIFP48100B</span>
                            </div>
                            <div class="equipment-info">
                                <strong>Performa Baterai</strong>
                                <span class="data-value">
                                    : <span class="status-badge status-excellent" style="padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px;">
                                        100% - EXCELLENT
                                        <span class="status-dot dot-excellent"></span>
                                    </span>
                                </span>
                            </div>
                            <div class="equipment-info">
                                <strong>Status Uji Baterai</strong>
                                <span class="data-value">: Belum di Uji</span>
                            </div>
                        </div>

                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar-event-fill"></i>
                                <span>07 Ags 2026</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Jambi</span>
                            </div>
                        </div>

                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>Manager Unit ICONPLUS KP JAMBI - Sandria Abhiseka . 04 Sep 2026, 10.31</span>
                        </div>

                        <div class="rectifier-card-footer">
                            <button type="button" class="btn-hapus" onclick="hapusBaterai('battery-card-1', 'Battery #1')">
                                <i class="bi bi-trash3-fill"></i>
                                Hapus
                            </button>
                            <a href="#" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>

                    </div>

                    {{-- CARD 2 (GOOD ENOUGH - Kuning) --}}
                    <div class="rectifier-card battery-card" id="battery-card-2">

                        {{-- Card Header Sesuai Gambar --}}
                        <div class="rectifier-card-header">
                            <div class="card-icon">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <div class="card-title-text">
                                <h3>Daftar pemeriksaan Baterai</h3>
                                <span>Data Baterai</span>
                            </div>
                        </div>

                        <div class="rectifier-information">
                            <div class="equipment-info">
                                <strong>Merk</strong>
                                <span class="data-value">: SACRED SUN</span>
                            </div>
                            <div class="equipment-info">
                                <strong>Tipe</strong>
                                <span class="data-value">: SSIFP48100B</span>
                            </div>
                            <div class="equipment-info">
                                <strong>Performa Baterai</strong>
                                <span class="data-value">
                                    : <span class="status-badge status-good-enough" style="padding: 3px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px;">
                                        80% - GOOD ENOUGH
                                        <span class="status-dot dot-good-enough"></span>
                                    </span>
                                </span>
                            </div>
                            <div class="equipment-info">
                                <strong>Status Uji Baterai</strong>
                                <span class="data-value">: Sudah di Uji</span>
                            </div>
                        </div>

                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar-event-fill"></i>
                                <span>07 Ags 2026</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Jambi</span>
                            </div>
                        </div>

                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>Manager Unit ICONPLUS KP JAMBI - Sandria Abhiseka . 04 Sep 2026, 10.31</span>
                        </div>

                        <div class="rectifier-card-footer">
                            <button type="button" class="btn-hapus" onclick="hapusBaterai('battery-card-2', 'Battery #2')">
                                <i class="bi bi-trash3-fill"></i>
                                Hapus
                            </button>
                            <a href="#" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusBaterai(cardId, batteryName) {
        Swal.fire({
            title: 'Hapus Baterai?',
            html: `Apakah Anda yakin ingin menghapus data <strong>"${batteryName}"</strong>?<br><small style="color: #64748b;">Data baterai dan riwayat pemeriksaannya akan dihapus.</small>`,
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
                const cardElement = document.getElementById(cardId);
                if (cardElement) {
                    cardElement.style.transition = 'all 0.3s ease';
                    cardElement.style.opacity = '0';
                    cardElement.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        const grid = cardElement.closest('.rectifier-grid');
                        cardElement.remove();

                        if (grid && grid.querySelectorAll('.battery-card').length === 0) {
                            grid.innerHTML = `
                                <div class="empty-battery" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                                    <i class="bi bi-battery" style="font-size: 3rem; color: #cbd5e1;"></i>
                                    <h3 style="margin-top: 15px; color: #475569;">Belum Ada Data Baterai</h3>
                                    <p style="color: #64748b;">Data baterai belum tersedia untuk rectifier ini.</p>
                                </div>
                            `;
                        }
                    }, 300);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Data "${batteryName}" berhasil dihapus.`,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
</script>

</body>
</html>