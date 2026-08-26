<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail KWH - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/kwh-detail.css'
    ])
</head>
<body>

<div class="app-container">
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">
        <x-topbar />

        <div class="content">
            <div class="detail-top">
                <a href="#" class="back-button" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="#" class="edit-button">
                    <i class="bi bi-pencil-fill"></i>
                    Edit Form
                </a>
            </div>

            <section class="detail-card">
                <div class="detail-card-title">
                    General Information
                </div>

                <div class="general-grid">
                    <div class="general-item">
                        <span class="general-label">POP</span>
                        <span class="general-value">POP_1MBN10004</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">Tanggal Pemeriksaan</span>
                        <span class="general-value">17/08/2026</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">Building</span>
                        <span class="general-value">POP-SB</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">Type POP</span>
                        <span class="general-value">POP-SB</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">PIC</span>
                        <span class="general-value">40 D AC / 13 A AC</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">ID Customer</span>
                        <span class="general-value">1431011655492</span>
                    </div>
                </div>
            </section>

            <section class="detail-card checklist-card">
                <div class="detail-card-title">
                    Checklist KWh
                </div>

                <div class="subtitle">
                    Main AC Power Information (Panel KWh Meter)
                </div>

                <div class="table-wrapper">
                    <table class="kwh-table">
                        <tbody>
                            <tr>
                                <th class="label-column">Daya</th>
                                <td colspan="2">33.000 VA (PS GI)</td>
                            </tr>
                            <tr>
                                <th class="label-column">MCB</th>
                                <td colspan="2">50A X 3 (150 A) / ABB</td>
                            </tr>
                            <tr>
                                <th class="label-column">Jumlah Phasa</th>
                                <td colspan="2">3 Phasa</td>
                            </tr>
                            <tr>
                                <th rowspan="7" class="label-column">Pengukuran Phasa</th>
                                <td>R-N : 211,5 Vac</td>
                                <td>R : 20,1 Ampera</td>
                            </tr>
                            <tr>
                                <td>S-N : 213,5 Vac</td>
                                <td>S : 14,8 Ampera</td>
                            </tr>
                            <tr>
                                <td>T-N : 204,6 Vac</td>
                                <td>T : 22,8 Ampera</td>
                            </tr>
                            <tr>
                                <td>R-S : 371,5 Vac</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>S-T : 360,8 Vac</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>R-T : 358,2 Vac</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>N-G : 0,4 Vac</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="label-column">Arrester</th>
                                <td>ADA</td>
                                <td>OBO</td>
                            </tr>
                            <tr>
                                <th class="label-column">Kabel output KWh</th>
                                <td>
                                    <div class="cable-info">
                                        <strong>WARNA :</strong>
                                        <span>R : Merah</span>
                                        <span>S : Kuning Hijau</span>
                                        <span>T : Hitam</span>
                                        <span>N : Biru</span>
                                        <span>Grounding : Kuning Hijau</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cable-info">
                                        <strong>UKURAN KABEL :</strong>
                                        <span>R (mm) : 16mm</span>
                                        <span>S (mm) : 16mm</span>
                                        <span>T (mm) : 16mm</span>
                                        <span>N (mm) : 16mm</span>
                                        <span>G (mm) : 16mm</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="label-column">Total Daya Terpakai (VA)</th>
                                <td colspan="2">4930.5</td>
                            </tr>
                            <tr>
                                <th class="label-column">Total Beban (A)</th>
                                <td colspan="2">57,7 A</td>
                            </tr>
                            <tr>
                                <th class="label-column">Status</th>
                                <td colspan="2">OK / Memadai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="detail-card photo-card">
                <div class="photo-title">
                    <i class="bi bi-camera-fill"></i>
                    <span>Photo KWH</span>
                </div>

                <div class="photo-grid">
                    <div class="photo-item">
                        <img src="{{ asset('images/kwh/tampak-kwh.jpg') }}" alt="Tampak KWh">
                        <span>Tampak KWh</span>
                    </div>
                    <div class="photo-item">
                        <img src="{{ asset('images/kwh/kwh-meter.jpg') }}" alt="KWh Meter">
                        <span>KWh Meter</span>
                    </div>
                    <div class="photo-item">
                        <img src="{{ asset('images/kwh/mcb-kwh.jpg') }}" alt="Tampak MCB KWh">
                        <span>Tampak MCB KWh</span>
                    </div>
                    <div class="photo-item">
                        <img src="{{ asset('images/kwh/mcb-kwh-2.jpg') }}" alt="Tampak MCB KWh">
                        <span>Tampak MCB KWh</span>
                    </div>
                    <div class="photo-item">
                        <img src="{{ asset('images/kwh/pengukuran.jpg') }}" alt="Pengukuran KWh">
                        <span>Pengukuran KWh</span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

</body>
</html>