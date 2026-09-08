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
                        <p class="form-section-subtitle">Informasi Baterai</p>

                        <div class="table-detail-container">
                            <table class="table-detail">
                                <tbody>
                                    <tr>
                                        <td class="td-label">Nomor Bank</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">POP_1SRG012_BANK01</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Merk Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">SACRED SUN</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Jenis Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">LITHIUM</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Tipe Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">SSIFP48100B</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Baterai (AH)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">100 AH</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Uji (AH)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">100.00 AH</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Baterai (%)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">
                                            <span class="capacity-value">100.00%</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Backup Timer (Hour)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">6.54 Hour</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>

                        <div class="table-detail-container">
                            <table class="table-detail">
                                <tbody>
                                    <tr>
                                        <td class="td-label">Tanggal Uji Terakhir</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">10/31/2025</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Tanggal Penggantian</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">05/05/2020</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Status Uji Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">
                                            <span class="status-badge status-good">GOOD</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Area STI</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">Baten 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-reset" onclick="history.back()">Kembali</button>
                        <a href="#" class="btn-submit btn-edit-detail">
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