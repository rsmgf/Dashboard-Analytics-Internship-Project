<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-detail.css'
    ])
</head>
<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rectifier-content">
                <div class="detail-page-header">
                    <div class="rectifier-page-info">
                        <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                    <div class="page-action-buttons">
                        <a href="#" class="btn-edit-form">
                            <i class="bi bi-pencil-fill"></i> Edit Data
                        </a>
                    </div>
                </div>

                <div class="detail-container">
                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">POP_1MBN10004</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Zona</span>
                                <span class="detail-value">Zona 1</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">PIC</span>
                                <span class="detail-value">Ahmad Fauzi</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Type POP</span>
                                <span class="detail-value">Main Hub</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Bentuk Fisik</span>
                                <span class="detail-value">Indoor Cabinet</span>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Genset</h3>
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Merk Genset</div>
                                <div class="checklist-field">Perkins</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Model</div>
                                <div class="checklist-field">Perkins 1104A</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Model</div>
                                <div class="checklist-field">Model A</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">SN Genset</div>
                                <div class="checklist-field">SN-PRK-998231</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas (KVA)</div>
                                <div class="checklist-field">50 KVA</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Engine</div>
                                <div class="checklist-field">Perkins</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">SN Engine</div>
                                <div class="checklist-field">ENG-PRK-44512</div>
                            </div>
                        </div>
                    </div>

                    <!-- Uji Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Genset</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">Tahun Pasang</span>
                                <span class="detail-value">2021</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal PM (Pemeliharaan Rutin)</span>
                                <span class="detail-value">12 Juni 2026</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status Genset</span>
                                <div class="detail-value">
                                    <span class="status-badge status-excellent">EXCELLENT</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Section (Genset & Engine) -->
                    <div class="photo-grid-2">
                        <!-- Photo Genset -->
                        <div class="form-card photo-card">
                            <h3 class="form-section-title">Photo Genset</h3>
                            <div class="preview-box-large">
                                <img src="https://via.placeholder.com/600x400?text=Foto+Genset" alt="Foto Genset">
                            </div>
                            <div class="detail-item mt-3">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">Kondisi unit genset terpasang rapi di shelter utama POP.</span>
                            </div>
                        </div>

                        <!-- Photo Engine -->
                        <div class="form-card photo-card">
                            <h3 class="form-section-title">Photo Engine</h3>
                            <div class="preview-box-large">
                                <img src="https://via.placeholder.com/600x400?text=Foto+Engine" alt="Foto Engine">
                            </div>
                            <div class="detail-item mt-3">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">Kondisi mesin engine bersih tanpa indikasi kebocoran oli.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>