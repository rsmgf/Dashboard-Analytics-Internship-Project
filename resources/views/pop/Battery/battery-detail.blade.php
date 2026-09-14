<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Baterai - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/battery-create.css',
        'resources/css/battery-detail.css'
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
                    <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <a href="#" class="btn-edit-form">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                </div>

                <div class="detail-container">
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">POP_1MBN10004</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Building</span>
                                <span class="detail-value">17/08/2026</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">PIC</span>
                                <span class="detail-value">POP-SB</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Type POP</span>
                                <span class="detail-value">POP-SB</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Recti</span>
                                <span class="detail-value">40 A DC / 13 A AC</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti</div>
                                <div class="checklist-field">POP_1SRG012</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank</div>
                                <div class="checklist-field">POP_1SRG012_BANK01</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery</div>
                                <div class="checklist-field">SACRED SUN</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Battery</div>
                                <div class="checklist-field">LITHIUM</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery</div>
                                <div class="checklist-field">SSIFP48100B</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V)</div>
                                <div class="checklist-field">48 V</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH)</div>
                                <div class="checklist-field">100 AH</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery</div>
                                <div class="checklist-field">
                                    <!-- Jika Lithium (1 Nilai) -->
                                    <div class="uji-val-single">100.00 AH</div>
                                    
                                    <!-- Jika VRLA (4 Nilai dalam Grid, aktifkan jika VRLA) -->
                                    <!-- 
                                    <div class="uji-grid-4-detail">
                                        <div class="uji-sub-display"><span class="sub-lbl">Battery 1:</span> <strong>25.00 AH</strong></div>
                                        <div class="uji-sub-display"><span class="sub-lbl">Battery 3:</span> <strong>25.00 AH</strong></div>
                                        <div class="uji-sub-display"><span class="sub-lbl">Battery 2:</span> <strong>25.00 AH</strong></div>
                                        <div class="uji-sub-display"><span class="sub-lbl">Battery 4:</span> <strong>25.00 AH</strong></div>
                                    </div> 
                                    -->
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery %</div>
                                <div class="checklist-field">
                                    <span class="capacity-value">100.00%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>

                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Tanggal Uji Terakhir</div>
                                <div class="checklist-field">10/31/2025</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tanggal Penggantian</div>
                                <div class="checklist-field">05/05/2020</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Status Uji Baterai</div>
                                <div class="checklist-field">
                                    <span class="status-badge status-good">GOOD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tambahan Photo Battery pada Detail -->
                    <div class="form-card">
                        <h3 class="form-section-title">Photo Battery</h3>
                        <div class="detail-grid-3" style="align-items: center;">
                            <div class="detail-item" style="grid-column: span 2;">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">Kondisi baterai aman dan aktif di lokasi POP</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Dokumentasi Foto</span>
                                <div class="preview-box" style="height: 100px; width: 150px; margin-top: 5px;">
                                    <img src="" alt="Foto Baterai" style="width: 100%; height: 100%; object-fit: cover; display: none;" id="detailPhoto">
                                    <div class="no-preview" id="noPhotoDetail">
                                        <i class="bi bi-image" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                                        <span style="font-size: 0.65rem;">Tidak ada foto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <button type="button" class="btn-reset" onclick="history.back()" style="height: 42px; padding: 0 24px; border: none; border-radius: 8px; background: #64748b; color: #ffffff; font-weight: 600; cursor: pointer;">Kembali</button>
                        <a href="#" class="btn-submit btn-edit-detail" style="height: 42px; padding: 0 28px; border-radius: 8px; background: #0070d8; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                            <i class="bi bi-pencil-fill"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>