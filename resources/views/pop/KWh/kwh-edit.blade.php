<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit KWH - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/sidebar.css', 'resources/css/rectifier-form.css', 'resources/css/kwh-create.css'])
</head>

<body>

<div class="app-container">

    {{-- SIDEBAR COMPONENT --}}
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">

        {{-- TOPBAR COMPONENT --}}
        <x-topbar />

        <div class="rform-content">

            {{-- HEADER BAR: Back + Breadcrumb As Title --}}
            <div class="rform-header-bar">
                <a href="{{ route('kwh.detail') }}" class="rform-back" title="Kembali ke Detail KWH">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="rform-header-text">
                    <x-breadcrumb :items="[
                        ['label' => 'POP', 'route' => 'pops.index'],
                        ['label' => 'POP Jambi Kota', 'route' => 'kwh.card'],
                        ['label' => 'KWH Utama - iEM3255', 'route' => 'kwh.detail'],
                        ['label' => 'Edit KWH'],
                    ]" />
                    <p class="rform-page-sub">Kode POP: <strong>POP_1MBN10004</strong> &middot; Jambi Kota, Jambi</p>
                </div>
            </div>

            <form id="kwhEditForm" action="{{ route('kwh.detail') }}" method="GET" enctype="multipart/form-data">

                {{-- ===============================================
                     SECTION 1 — General Information
                ================================================ --}}
                <div class="rform-section">
                    <div class="rform-section-header">
                        <span class="rform-section-step">1</span>
                        General Information
                    </div>
                    <div class="rform-section-body">
                        <div class="rform-row rform-row-3">
                            <div class="rform-group">
                                <label class="rform-label">POP</label>
                                <input type="text" class="rform-input" value="POP_1MBN10004 - POP Jambi Kota" readonly>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Building <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="POP-SB" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">PIC / Petugas <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="40 D AC / 13 A AC" required>
                            </div>
                        </div>

                        <div class="rform-row rform-row-3">
                            <div class="rform-group">
                                <label class="rform-label">Type POP <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="POP-SB" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">ID Customer (PLN) <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="1431011655492" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Tanggal Pemeriksaan <span class="rform-required">*</span></label>
                                <input type="date" class="rform-input" value="2026-08-17" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===============================================
                     SECTION 2 — Spesifikasi & Parameter KWH
                ================================================ --}}
                <div class="rform-section">
                    <div class="rform-section-header">
                        <span class="rform-section-step">2</span>
                        Spesifikasi Panel KWH Meter
                    </div>
                    <div class="rform-section-body">
                        <div class="rform-row rform-row-4">
                            <div class="rform-group">
                                <label class="rform-label">Daya (PS GI) <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="33.000 VA" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">MCB Utama <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="50A x 3 (150 A) / ABB" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Jumlah Phasa <span class="rform-required">*</span></label>
                                <select class="rform-select" required>
                                    <option value="3 Phasa" selected>3 Phasa</option>
                                    <option value="1 Phasa">1 Phasa</option>
                                </select>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Keberadaan Arrester <span class="rform-required">*</span></label>
                                <select class="rform-select" required>
                                    <option value="ADA" selected>ADA (Terpasang)</option>
                                    <option value="TIDAK ADA">TIDAK ADA</option>
                                </select>
                            </div>
                        </div>

                        <div class="rform-row rform-row-2">
                            <div class="rform-group">
                                <label class="rform-label">Merk / Type Arrester <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="OBO" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Status Kelistrikan <span class="rform-required">*</span></label>
                                <select class="rform-select" required>
                                    <option value="OK / Memadai" selected>OK / Memadai</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                    <option value="Kritis">Kritis / Tidak Standar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===============================================
                     SECTION 3 — Pengukuran Tegangan & Arus Phasa
                ================================================ --}}
                <div class="rform-section">
                    <div class="rform-section-header">
                        <span class="rform-section-step">3</span>
                        Pengukuran Tegangan & Arus Phasa
                    </div>
                    <div class="rform-section-body">
                        <div class="kwh-form-table-wrapper">
                            <table class="kwh-form-table">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Tegangan (Vac)</th>
                                        <th style="width: 25%;">Nilai Pengukuran (Vac)</th>
                                        <th style="width: 25%;">Arus Beban (A)</th>
                                        <th style="width: 25%;">Nilai Pengukuran (A)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>R - N</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="211.5" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td><strong>R</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="20.1" required>
                                                <span class="unit-text">A</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>S - N</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="213.5" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td><strong>S</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="14.8" required>
                                                <span class="unit-text">A</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>T - N</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="204.6" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td><strong>T</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="22.8" required>
                                                <span class="unit-text">A</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>R - S</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="371.5" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                    </tr>
                                    <tr>
                                        <td><strong>S - T</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="360.8" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                    </tr>
                                    <tr>
                                        <td><strong>R - T</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="358.2" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                    </tr>
                                    <tr>
                                        <td><strong>N - G</strong></td>
                                        <td>
                                            <div class="input-with-unit">
                                                <input type="text" class="rform-input" value="0.4" required>
                                                <span class="unit-text">Vac</span>
                                            </div>
                                        </td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                        <td class="kwh-empty-cell">&mdash;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="rform-row rform-row-2 kwh-totals-row">
                            <div class="rform-group">
                                <label class="rform-label">Total Daya Terpakai (VA) <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="4930.5" required>
                            </div>

                            <div class="rform-group">
                                <label class="rform-label">Total Beban (A) <span class="rform-required">*</span></label>
                                <input type="text" class="rform-input" value="57.7" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===============================================
                     SECTION 4 — Spesifikasi Kabel Output KWH
                ================================================ --}}
                <div class="rform-section">
                    <div class="rform-section-header">
                        <span class="rform-section-step">4</span>
                        Spesifikasi Kabel Output KWH
                    </div>
                    <div class="rform-section-body">
                        <div class="cable-form-grid">
                            {{-- Block Warna Kabel --}}
                            <div class="cable-form-block">
                                <div class="cable-block-heading">
                                    <i class="bi bi-palette-fill"></i> Warna Kabel Output
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">R</span>
                                    <input type="text" class="rform-input" value="Merah" placeholder="Warna Fasa R" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">S</span>
                                    <input type="text" class="rform-input" value="Kuning Hijau" placeholder="Warna Fasa S" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">T</span>
                                    <input type="text" class="rform-input" value="Hitam" placeholder="Warna Fasa T" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">N</span>
                                    <input type="text" class="rform-input" value="Biru" placeholder="Warna Netral" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">G</span>
                                    <input type="text" class="rform-input" value="Kuning Hijau" placeholder="Warna Grounding" required>
                                </div>
                            </div>

                            {{-- Block Ukuran Kabel --}}
                            <div class="cable-form-block">
                                <div class="cable-block-heading">
                                    <i class="bi bi-rulers"></i> Ukuran Kabel (mm&sup2;)
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">R</span>
                                    <input type="text" class="rform-input" value="16mm" placeholder="Ukuran mm²" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">S</span>
                                    <input type="text" class="rform-input" value="16mm" placeholder="Ukuran mm²" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">T</span>
                                    <input type="text" class="rform-input" value="16mm" placeholder="Ukuran mm²" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">N</span>
                                    <input type="text" class="rform-input" value="16mm" placeholder="Ukuran mm²" required>
                                </div>
                                <div class="cable-input-row">
                                    <span class="cable-badge">G</span>
                                    <input type="text" class="rform-input" value="16mm" placeholder="Ukuran mm²" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===============================================
                     SECTION 5 — Dokumentasi Foto KWH (Multi-Foto Dinamis)
                ================================================ --}}
                <div class="rform-section">
                    <div class="rform-section-header" style="justify-content: space-between;">
                        <div>
                            <span class="rform-section-step">5</span>
                            Dokumentasi Foto KWH
                        </div>
                        <span id="kwhEditPhotoCountBadge" class="photo-count-badge">
                            <i class="bi bi-images"></i> Total: 5 Foto
                        </span>
                    </div>
                    <div class="rform-section-body">
                        
                        {{-- Dynamic Photo Cards Grid --}}
                        <div id="kwhEditPhotoCardsGrid" class="kwh-photo-cards-grid">
                            
                            {{-- Foto 1 --}}
                            <div class="kwh-photo-card" id="photoCardEdit-0">
                                <div class="kwh-photo-card-header">
                                    <span class="kwh-photo-card-title">Foto 1</span>
                                    <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(0)" title="Hapus foto ini">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="kwh-photo-card-body">
                                    <div class="kwh-photo-preview-box">
                                        <img id="photoPreviewEdit-0" src="{{ asset('images/kwh/tampak-kwh.jpg') }}" class="kwh-photo-img" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=Tampak+KWh'" alt="Preview Foto 1" onclick="openKwhLightbox(this.src, 'Tampak KWh')">
                                    </div>
                                    <div class="kwh-photo-upload-action">
                                        <label for="photoInputEdit-0" class="btn-kwh-browse">
                                            <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="photoInputEdit-0" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, 0)" hidden>
                                    </div>
                                    <div class="rform-group" style="margin-top: 6px;">
                                        <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                        <input type="text" class="rform-input" value="Tampak KWh" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Foto 2 --}}
                            <div class="kwh-photo-card" id="photoCardEdit-1">
                                <div class="kwh-photo-card-header">
                                    <span class="kwh-photo-card-title">Foto 2</span>
                                    <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(1)" title="Hapus foto ini">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="kwh-photo-card-body">
                                    <div class="kwh-photo-preview-box">
                                        <img id="photoPreviewEdit-1" src="{{ asset('images/kwh/kwh-meter.jpg') }}" class="kwh-photo-img" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=KWh+Meter'" alt="Preview Foto 2" onclick="openKwhLightbox(this.src, 'KWh Meter')">
                                    </div>
                                    <div class="kwh-photo-upload-action">
                                        <label for="photoInputEdit-1" class="btn-kwh-browse">
                                            <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="photoInputEdit-1" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, 1)" hidden>
                                    </div>
                                    <div class="rform-group" style="margin-top: 6px;">
                                        <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                        <input type="text" class="rform-input" value="KWh Meter" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Foto 3 --}}
                            <div class="kwh-photo-card" id="photoCardEdit-2">
                                <div class="kwh-photo-card-header">
                                    <span class="kwh-photo-card-title">Foto 3</span>
                                    <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(2)" title="Hapus foto ini">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="kwh-photo-card-body">
                                    <div class="kwh-photo-preview-box">
                                        <img id="photoPreviewEdit-2" src="{{ asset('images/kwh/mcb-kwh.jpg') }}" class="kwh-photo-img" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=MCB+KWh'" alt="Preview Foto 3" onclick="openKwhLightbox(this.src, 'Tampak MCB KWh')">
                                    </div>
                                    <div class="kwh-photo-upload-action">
                                        <label for="photoInputEdit-2" class="btn-kwh-browse">
                                            <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="photoInputEdit-2" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, 2)" hidden>
                                    </div>
                                    <div class="rform-group" style="margin-top: 6px;">
                                        <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                        <input type="text" class="rform-input" value="Tampak MCB KWh" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Foto 4 --}}
                            <div class="kwh-photo-card" id="photoCardEdit-3">
                                <div class="kwh-photo-card-header">
                                    <span class="kwh-photo-card-title">Foto 4</span>
                                    <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(3)" title="Hapus foto ini">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="kwh-photo-card-body">
                                    <div class="kwh-photo-preview-box">
                                        <img id="photoPreviewEdit-3" src="{{ asset('images/kwh/mcb-kwh-2.jpg') }}" class="kwh-photo-img" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=MCB+KWh+2'" alt="Preview Foto 4" onclick="openKwhLightbox(this.src, 'MCB KWh (Tampak Dalam)')">
                                    </div>
                                    <div class="kwh-photo-upload-action">
                                        <label for="photoInputEdit-3" class="btn-kwh-browse">
                                            <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="photoInputEdit-3" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, 3)" hidden>
                                    </div>
                                    <div class="rform-group" style="margin-top: 6px;">
                                        <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                        <input type="text" class="rform-input" value="MCB KWh (Tampak Dalam)" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Foto 5 --}}
                            <div class="kwh-photo-card" id="photoCardEdit-4">
                                <div class="kwh-photo-card-header">
                                    <span class="kwh-photo-card-title">Foto 5</span>
                                    <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(4)" title="Hapus foto ini">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                                <div class="kwh-photo-card-body">
                                    <div class="kwh-photo-preview-box">
                                        <img id="photoPreviewEdit-4" src="{{ asset('images/kwh/pengukuran.jpg') }}" class="kwh-photo-img" onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=Pengukuran+KWh'" alt="Preview Foto 5" onclick="openKwhLightbox(this.src, 'Pengukuran KWh')">
                                    </div>
                                    <div class="kwh-photo-upload-action">
                                        <label for="photoInputEdit-4" class="btn-kwh-browse">
                                            <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                        </label>
                                        <input type="file" id="photoInputEdit-4" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, 4)" hidden>
                                    </div>
                                    <div class="rform-group" style="margin-top: 6px;">
                                        <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                        <input type="text" class="rform-input" value="Pengukuran KWh" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Tombol Tambah Foto --}}
                        <div style="margin-top: 18px;">
                            <button type="button" class="rform-btn-add-module" onclick="addPhotoCardEdit()">
                                <i class="bi bi-plus-lg"></i> Tambah Foto
                            </button>
                        </div>

                    </div>
                </div>

                {{-- ===============================================
                     SECTION 6 — SOP & Catatan Lapangan (Note Box)
                ================================================ --}}
                <div class="kwh-sop-note">
                    <div class="sop-note-title">
                        <i class="bi bi-info-circle-fill"></i> Standar Operasional Prosedur (SOP) & Catatan Pemeriksaan
                    </div>
                    <ol class="sop-note-list">
                        <li>Pastikan tegangan setiap fasa berkisar antara <strong>210 &ndash; 225 Vac</strong>. Jika di luar rentang tersebut, segera laporkan ke tim NOC / Internal ICON Plus.</li>
                        <li>Pastikan semua terminasi kabel terpasang dengan kuat dan kencang pada baut MCB.</li>
                        <li>Periksa indikator surge arrester: harus dalam kondisi <strong>Hijau (Baik)</strong>. Jika berwarna <strong>Merah</strong>, segera lakukan penggantian.</li>
                        <li>Pastikan boks panel KWH bebas karat. Lakukan pengecatan anti-karat bila ditemukan korosi.</li>
                    </ol>
                </div>

                {{-- ===============================================
                     FORM ACTIONS: Reset & Perbarui
                ================================================ --}}
                <div class="rform-actions">
                    <button type="button" class="rform-btn-reset" onclick="resetKwhEditForm()">Reset</button>
                    <button type="submit" class="rform-btn-simpan">
                        <i class="bi bi-check-lg"></i> Perbarui
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
    let photoEditCounter = 5;

    function previewDynamicPhotoEdit(input, idx) {
        const file = input.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Ukuran foto maksimal adalah 2MB.',
                confirmButtonColor: '#2563eb'
            });
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('photoPreviewEdit-' + idx);
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }

    function addPhotoCardEdit() {
        const grid = document.getElementById('kwhEditPhotoCardsGrid');
        const idx = photoEditCounter++;
        const currentCount = grid.querySelectorAll('.kwh-photo-card').length + 1;

        const card = document.createElement('div');
        card.className = 'kwh-photo-card';
        card.id = 'photoCardEdit-' + idx;
        card.innerHTML = `
            <div class="kwh-photo-card-header">
                <span class="kwh-photo-card-title">Foto ${currentCount}</span>
                <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(${idx})" title="Hapus foto ini">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
            <div class="kwh-photo-card-body">
                <div class="kwh-photo-preview-box">
                    <div class="kwh-photo-empty" id="photoEmptyEdit-${idx}">
                        <i class="bi bi-image"></i>
                        <small>Belum ada foto</small>
                    </div>
                    <img id="photoPreviewEdit-${idx}" class="kwh-photo-img" style="display: none;" alt="Preview Foto" onclick="openKwhLightbox(this.src, document.querySelector('#photoCardEdit-${idx} input[type=text]')?.value || 'Foto ${currentCount}')">
                </div>
                <div class="kwh-photo-upload-action">
                    <label for="photoInputEdit-${idx}" class="btn-kwh-browse">
                        <i class="bi bi-camera-fill"></i> Pilih Foto
                    </label>
                    <input type="file" id="photoInputEdit-${idx}" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, ${idx})" hidden>
                </div>
                <div class="rform-group" style="margin-top: 6px;">
                    <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                    <input type="text" class="rform-input" placeholder="Keterangan foto baru..." required>
                </div>
            </div>
        `;
        grid.appendChild(card);
        reindexPhotoCardsEdit();
    }

    function removePhotoCardEdit(idx) {
        const grid = document.getElementById('kwhEditPhotoCardsGrid');
        const cards = grid.querySelectorAll('.kwh-photo-card');
        if (cards.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Minimal 1 Foto',
                text: 'Dokumentasi KWH wajib memiliki minimal 1 foto.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        const el = document.getElementById('photoCardEdit-' + idx);
        if (el) {
            el.remove();
            reindexPhotoCardsEdit();
        }
    }

    function reindexPhotoCardsEdit() {
        const cards = document.querySelectorAll('#kwhEditPhotoCardsGrid .kwh-photo-card');
        cards.forEach((card, i) => {
            const title = card.querySelector('.kwh-photo-card-title');
            if (title) title.innerText = 'Foto ' + (i + 1);
        });

        const badge = document.getElementById('kwhEditPhotoCountBadge');
        if (badge) {
            badge.innerHTML = `<i class="bi bi-images"></i> Total: ${cards.length} Foto`;
        }
    }

    function resetKwhEditForm() {
        Swal.fire({
            title: 'Kembalikan Data Semula?',
            text: 'Perubahan yang belum tersimpan akan dikembalikan ke data awal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kembalikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kwhEditForm').reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Data Direset',
                    showConfirmButton: false,
                    timer: 1200
                });
            }
        });
    }

    // Lightbox functions
    function openKwhLightbox(src, caption) {
        if (!src) return;
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

</body>
</html>
