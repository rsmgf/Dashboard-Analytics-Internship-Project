<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail KWH - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite(['resources/css/sidebar.css', 'resources/css/kwh-detail.css'])
</head>

<body>

<div class="app-container">

    {{-- SIDEBAR COMPONENT --}}
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">

        {{-- TOPBAR COMPONENT --}}
        <x-topbar />

        <div class="kwh-detail-content">

            {{-- HEADER ATAS: Back + Breadcrumb As Title + Badge + Tombol Edit --}}
            <div class="detail-header-bar">
                <div class="header-left-group">
                    <a href="{{ route('kwh.card') }}" class="detail-back" title="Kembali ke Daftar KWH">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="header-title-wrapper">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => 'POP Jambi Kota', 'route' => 'kwh.card'],
                                ['label' => 'KWH Utama - iEM3255'],
                            ]" />
                            <span class="device-badge">3 Phasa &bull; 197 kVA</span>
                        </div>
                        <span class="pop-sub-info">Kode POP: <strong>POP_1MBN10004</strong> &middot; Jambi Kota, Jambi</span>
                    </div>
                </div>

                <a href="{{ route('kwh.edit') }}" class="btn-edit">
                    <i class="bi bi-pencil-fill"></i>
                    Edit Form
                </a>
            </div>

            {{-- ALERT BANNER LAST UPDATE --}}
            <div class="alert-info-custom">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>
                    Terakhir diperbarui: <strong>Ahmad Teknisi &middot; 24 Agustus 2026, 14:30 WIB</strong>
                </span>
            </div>

            {{-- 1. CARD GENERAL INFORMATION --}}
            <section class="detail-card information-card">
                <div class="detail-card-title">
                    <i class="bi bi-info-circle-fill"></i> General Information
                </div>

                <div class="general-grid">
                    <div class="general-item">
                        <span class="general-label">POP</span>
                        <span class="general-value">POP Jambi Kota (POP_1MBN10004)</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">Tanggal Pemeriksaan</span>
                        <span class="general-value">17 Agustus 2026</span>
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
                        <span class="general-label">PIC / Petugas</span>
                        <span class="general-value">40 D AC / 13 A AC</span>
                    </div>

                    <div class="general-item">
                        <span class="general-label">ID Customer (PLN)</span>
                        <span class="general-value">1431011655492</span>
                    </div>
                </div>
            </section>

            {{-- 2. CARD CHECKLIST KWH & PENGUKURAN PHASA --}}
            <section class="detail-card checklist-card">
                <div class="section-heading">
                    <span><i class="bi bi-speedometer2"></i> Checklist & Pengukuran Phasa</span>
                    <small>Main AC Power Information (Panel KWH Meter)</small>
                </div>

                <div class="table-wrapper">
                    <table class="kwh-table">
                        <tbody>
                            <tr>
                                <th class="label-column">Daya (PS GI)</th>
                                <td class="value-column" colspan="2"><strong>33.000 VA</strong></td>
                            </tr>
                            <tr>
                                <th class="label-column">MCB Utama</th>
                                <td class="value-column" colspan="2">50A &times; 3 (150 A) / ABB</td>
                            </tr>
                            <tr>
                                <th class="label-column">Jumlah Phasa</th>
                                <td class="value-column" colspan="2"><span class="badge-phasa">3 Phasa</span></td>
                            </tr>
                            <tr>
                                <th class="label-column">Pengukuran Phasa</th>
                                <td class="value-column nested-cell" colspan="2">
                                    <table class="sub-measurement-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Tegangan (Vac)</th>
                                                <th style="width: 25%;">Nilai</th>
                                                <th style="width: 25%;">Arus Beban (A)</th>
                                                <th style="width: 25%;">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>R - N</strong></td>
                                                <td><code>211,5 Vac</code></td>
                                                <td><strong>R</strong></td>
                                                <td><code>20,1 A</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>S - N</strong></td>
                                                <td><code>213,5 Vac</code></td>
                                                <td><strong>S</strong></td>
                                                <td><code>14,8 A</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>T - N</strong></td>
                                                <td><code>204,6 Vac</code></td>
                                                <td><strong>T</strong></td>
                                                <td><code>22,8 A</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>R - S</strong></td>
                                                <td><code>371,5 Vac</code></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>S - T</strong></td>
                                                <td><code>360,8 Vac</code></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>R - T</strong></td>
                                                <td><code>358,2 Vac</code></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>N - G</strong></td>
                                                <td><code>0,4 Vac</code></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                <td><span style="color: #94a3b8;">&mdash;</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th class="label-column">Arrester</th>
                                <td class="value-column" colspan="2">
                                    <div class="arrester-row">
                                        <div>Status: <span class="badge-status-ok">ADA</span></div>
                                        <div>Merk / Type: <strong>OBO</strong></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="label-column">Kabel Output KWH</th>
                                <td class="value-column nested-cell" colspan="2">
                                    <div class="cable-grid-container">
                                        <div class="cable-block">
                                            <div class="cable-block-title">Warna Kabel</div>
                                            <div class="cable-row"><span class="cable-code">R</span> Merah</div>
                                            <div class="cable-row"><span class="cable-code">S</span> Kuning Hijau</div>
                                            <div class="cable-row"><span class="cable-code">T</span> Hitam</div>
                                            <div class="cable-row"><span class="cable-code">N</span> Biru</div>
                                            <div class="cable-row"><span class="cable-code">G</span> Kuning Hijau</div>
                                        </div>
                                        <div class="cable-block">
                                            <div class="cable-block-title">Ukuran Kabel</div>
                                            <div class="cable-row"><span class="cable-code">R</span> 16 mm&sup2;</div>
                                            <div class="cable-row"><span class="cable-code">S</span> 16 mm&sup2;</div>
                                            <div class="cable-row"><span class="cable-code">T</span> 16 mm&sup2;</div>
                                            <div class="cable-row"><span class="cable-code">N</span> 16 mm&sup2;</div>
                                            <div class="cable-row"><span class="cable-code">G</span> 16 mm&sup2;</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="label-column">Total Daya Terpakai</th>
                                <td class="value-column" colspan="2"><strong>4.930,5 VA</strong></td>
                            </tr>
                            <tr>
                                <th class="label-column">Total Beban</th>
                                <td class="value-column" colspan="2"><strong>57,7 A</strong></td>
                            </tr>
                            <tr>
                                <th class="label-column">Status Kelistrikan</th>
                                <td class="value-column" colspan="2"><span class="badge-status-ok"><i class="bi bi-check-circle-fill"></i> OK / Memadai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- 3. CARD PHOTO DOKUMENTASI KWH --}}
            <section class="detail-card photo-card">
                <div class="section-heading">
                    <span><i class="bi bi-camera-fill"></i> Photo Dokumentasi KWH</span>
                    <small>5 Foto Terlampir</small>
                </div>

                <div class="photo-grid">
                    <div class="photo-item" onclick="openKwhLightbox(this.querySelector('img').src, 'Tampak KWh')">
                        <img src="{{ asset('images/kwh/tampak-kwh.jpg') }}" alt="Tampak KWh" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=Tampak+KWh'">
                        <span>Tampak KWh</span>
                    </div>
                    <div class="photo-item" onclick="openKwhLightbox(this.querySelector('img').src, 'KWh Meter')">
                        <img src="{{ asset('images/kwh/kwh-meter.jpg') }}" alt="KWh Meter" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=KWh+Meter'">
                        <span>KWh Meter</span>
                    </div>
                    <div class="photo-item" onclick="openKwhLightbox(this.querySelector('img').src, 'Tampak MCB KWh')">
                        <img src="{{ asset('images/kwh/mcb-kwh.jpg') }}" alt="Tampak MCB KWh" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=MCB+KWh'">
                        <span>Tampak MCB KWh</span>
                    </div>
                    <div class="photo-item" onclick="openKwhLightbox(this.querySelector('img').src, 'MCB KWh (Tampak Dalam)')">
                        <img src="{{ asset('images/kwh/mcb-kwh-2.jpg') }}" alt="Tampak MCB KWh 2" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=MCB+KWh+2'">
                        <span>MCB KWh (Tampak Dalam)</span>
                    </div>
                    <div class="photo-item" onclick="openKwhLightbox(this.querySelector('img').src, 'Pengukuran KWh')">
                        <img src="{{ asset('images/kwh/pengukuran.jpg') }}" alt="Pengukuran KWh" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=Pengukuran+KWh'">
                        <span>Pengukuran KWh</span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

{{-- LIGHTBOX MODAL --}}
<div id="imageLightboxModal" class="kwh-lightbox-modal" onclick="closeKwhLightbox(event)">
    <div class="kwh-lightbox-wrapper">
        <button type="button" class="kwh-lightbox-close" onclick="closeKwhLightboxDirect()" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
        <img id="lightboxImg" class="kwh-lightbox-img" src="" alt="Preview Foto HD">
        <div id="lightboxCaption" class="kwh-lightbox-caption"></div>
    </div>
</div>

<script>
    function openKwhLightbox(src, caption) {
        const modal = document.getElementById('imageLightboxModal');
        const img = document.getElementById('lightboxImg');
        const cap = document.getElementById('lightboxCaption');
        if (modal && img) {
            img.src = src;
            if (cap) cap.innerText = caption || 'Bukti Foto KWH';
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeKwhLightbox(e) {
        if (e.target.id === 'imageLightboxModal') {
            closeKwhLightboxDirect();
        }
    }

    function closeKwhLightboxDirect() {
        const modal = document.getElementById('imageLightboxModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeKwhLightboxDirect();
        }
    });
</script>

</body>
</html>