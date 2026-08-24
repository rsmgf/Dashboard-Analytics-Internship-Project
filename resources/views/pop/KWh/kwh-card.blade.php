<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card KWH - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/card.css'
    ])
</head>
<body>
<div class="app-container">
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <main class="main-content">
        <x-topbar />
        <div class="content">
            <div class="page-header">
                <div class="page-info">
                    <a href="#" class="back-button" title="Kembali ke List POP">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <span class="pop-name">POP Jambi Kota</span>
                        <span class="pop-sub">Jambi Kota, Jambi &mdash; 3 KWH</span>
                    </div>
                </div>
                <a href="#" class="btn-tambah-rectifier">
                    <i class="bi bi-plus-lg"></i> Tambah
                </a>
            </div>

            <div class="grid">
                <div class="card">
                    <div class="card-header">
                        <div class="checklist-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="checklist-title">
                            <h3>Checklist KWH</h3>
                            <p>KWH Utama</p>
                        </div>
                        <span class="number">KWH #1</span>
                    </div>
                    <div class="information">
                        <div class="equipment-info">
                            <strong>Type :</strong> iEM3255
                        </div>
                        <div class="equipment-info">
                            <strong>Phasa :</strong> 3 Phasa
                        </div>
                        <div class="equipment-info">
                            <strong>Daya :</strong> 197 kVA
                        </div>
                    </div>
                    <div class="meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar-fill"></i>
                            <span>24 Agu 2026</span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jambi Kota</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn-hapus" onclick="alert('Tombol Hapus diklik')">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                        <a href="#" class="detail-button">
                            <span>Detail</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="checklist-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="checklist-title">
                            <h3>Checklist KWH</h3>
                            <p>KWH Backup</p>
                        </div>
                        <span class="number">KWH #2</span>
                    </div>
                    <div class="information">
                        <div class="equipment-info">
                            <strong>Type :</strong> SL7000
                        </div>
                        <div class="equipment-info">
                            <strong>Phasa :</strong> 3 Phasa
                        </div>
                        <div class="equipment-info">
                            <strong>Daya :</strong> 131 kVA
                        </div>
                    </div>
                    <div class="meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar-fill"></i>
                            <span>23 Agu 2026</span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jambi Kota</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn-hapus" onclick="alert('Tombol Hapus diklik')">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                        <a href="#" class="detail-button">
                            <span>Detail</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="checklist-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="checklist-title">
                            <h3>Checklist KWH</h3>
                            <p>KWH Distribusi</p>
                        </div>
                        <span class="number">KWH #3</span>
                    </div>
                    <div class="information">
                        <div class="equipment-info">
                            <strong>Type :</strong> ZMD405
                        </div>
                        <div class="equipment-info">
                            <strong>Phasa :</strong> 3 Phasa
                        </div>
                        <div class="equipment-info">
                            <strong>Daya :</strong> 197 kVA
                        </div>
                    </div>
                    <div class="meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar-fill"></i>
                            <span>22 Agu 2026</span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jambi Kota</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn-hapus" onclick="alert('Tombol Hapus diklik')">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                        <a href="#" class="detail-button">
                            <span>Detail</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>