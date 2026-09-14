<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Air Conditioner - PLN Icon Plus</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-detail.css'
    ])
</head>

<body>

    <div class="app-container">

        <x-sidebar />

        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            <x-topbar />

            <div class="ac-content">

                <div class="detail-page-header">
                    <div class="ac-page-info">
                        <a href="{{ url()->previous() }}"
                            class="back-button"
                            title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                    <div>
                        <a href="#" class="btn-edit-form">
                            <i class="bi bi-pencil-fill"></i> Edit Form
                        </a>
                    </div>
                </div>

                <div class="detail-container">

                    <div class="form-card">
                        <h3 class="form-section-title">
                            General Information
                        </h3>

                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">POP_1MBN10004</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Building</span>
                                <span class="detail-value">Shelter Permanen</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Type POP</span>
                                <span class="detail-value">POP-SB</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-bottom-grid">

                        <div class="form-card mb-0">
                            <h3 class="form-section-title">
                                Checklist Air Conditioner
                            </h3>

                            <div class="checklist-table-container">
                                <div class="checklist-row">
                                    <div class="checklist-label">Nomor AC</div>
                                    <div class="checklist-field">AC-01</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Merk AC</div>
                                    <div class="checklist-field">Daikin</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Type AC</div>
                                    <div class="checklist-field">Split</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">PK</div>
                                    <div class="checklist-field">1 PK</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Jenis Freon</div>
                                    <div class="checklist-field">R32</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tahun Manufaktur</div>
                                    <div class="checklist-field">2023</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Instalasi</div>
                                    <div class="checklist-field">12 Januari 2024</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Terakhir PM</div>
                                    <div class="checklist-field">
                                        <span class="status-badge status-excellent">ADA OK</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-card mb-0 photo-card-wrapper">
                            <h3 class="form-section-title">
                                Photo Air Conditioner
                            </h3>

                            <div class="detail-photo-box">
                                <img src="" alt="Foto Kondisi AC" id="detailPhoto" style="display: none;">
                                
                                <div id="noDetailPhoto" class="no-preview">
                                    <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                    <span style="font-size: 0.8rem; color: #64748b;">Tidak ada foto tersedia</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>

</html>