<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Detail Baterai {{ $battery->nomor_bank }} - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/css/sidebar.css',
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
                {{-- Header Bar --}}
                <div class="detail-header-bar">
                    <div class="header-left-group">
                        <a href="{{ route('batteries.index', $pop->id) }}" class="detail-back" title="Kembali ke Daftar Baterai">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-title-wrapper">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <x-breadcrumb :items="[
                                    ['label' => 'POP', 'route' => 'pops.index'],
                                    ['label' => $pop->nama_pop_display . ': Battery', 'route' => 'batteries.index', 'params' => ['pop' => $pop->id]],
                                    ['label' => $battery->nomor_bank],
                                ]" />
                                <span class="device-badge">{{ $battery->merk_battery }} &bull; {{ $battery->kapasitas_battery }} AH</span>
                            </div>
                        </div>
                    </div>

                    @can('batteries.index.update')
                    <a href="{{ route('batteries.edit', [$pop->id, $battery->id]) }}" class="btn-edit">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                    @endcan
                </div>

                {{-- Alert Banner Last Update --}}
                <div class="alert-info-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>
                        Terakhir diperbarui:
                        <strong>
                            @if($battery->diupdateOleh)
                                {{ $battery->diupdateOleh->name }} &middot; {{ $battery->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                {{ $battery->updated_at ? $battery->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                            @endif
                        </strong>
                    </span>
                </div>

                <div class="detail-container">
                    {{-- SECTION 1: GENERAL INFORMATION --}}
                    <div class="form-card">
                        <h3 class="form-section-title">
                            <i class="bi bi-info-circle-fill"></i> General Information
                        </h3>
                        <div class="general-info-grid">
                            <div class="general-info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <span class="info-card-label">POP</span>
                                    <span class="info-card-value">{{ $pop->nama_pop_display }}</span>
                                </div>
                            </div>

                            <div class="general-info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="info-card-content">
                                    <span class="info-card-label">Building</span>
                                    <span class="info-card-value">{{ $pop->jenis_bangunan ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="general-info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <span class="info-card-label">PIC</span>
                                    <span class="info-card-value">{{ $battery->pic ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="general-info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-tag-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <span class="info-card-label">Type POP</span>
                                    <span class="info-card-value">{{ $pop->tipe_pop ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="general-info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-hdd-rack-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <span class="info-card-label">Nomor Recti</span>
                                    <span class="info-card-value">{{ $battery->rectifier->nama_alias ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">
                            <i class="bi bi-battery-charging"></i> Checklist Baterai
                        </h3>
                        <div class="checklist-section-subtitle">Informasi Baterai</div>

                        @php
                            $persen = $battery->kapasitas_battery_persen;
                            $performa = $battery->performa_baterai ?? 'BLM UJI BATT';

                            $badgeClass = 'status-neutral';
                            if ($persen === null || $persen <= 0) {
                                $badgeClass = 'status-neutral';
                            } elseif ($persen >= 90) {
                                $badgeClass = 'status-excellent'; // Hijau
                            } elseif ($persen >= 75) {
                                $badgeClass = 'status-good'; // Kuning (Good Enough)
                            } elseif ($persen >= 50) {
                                $badgeClass = 'status-warning'; // Orange (Warning)
                            } else {
                                $badgeClass = 'status-danger'; // Merah (Alert)
                            }
                        @endphp

                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ $battery->nomor_bank }}</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Baterai</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ $battery->merk_battery }}</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Baterai</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ strtoupper($battery->jenis_battery ?? 'LITHIUM') }}</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Baterai</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ $battery->tipe_battery ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V)</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ $battery->tegangan ?? 48 }} V</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Baterai (AH)</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">{{ $battery->kapasitas_battery }} AH</span>
                                </div>
                            </div>

                            <div class="checklist-row align-top">
                                <div class="checklist-label">Kapasitas Uji Baterai</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <div class="field-value-column">
                                        @if (strtoupper($battery->jenis_battery ?? '') === 'VRLA')
                                            <div class="uji-grid-4-detail">
                                                <div class="uji-cell-box">
                                                    <span class="uji-cell-label">Batt 1</span>
                                                    <span class="uji-cell-val">{{ $battery->vrla_1 !== null ? number_format($battery->vrla_1, 2) : ($battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) : '-') }} AH</span>
                                                </div>
                                                <div class="uji-cell-box">
                                                    <span class="uji-cell-label">Batt 2</span>
                                                    <span class="uji-cell-val">{{ $battery->vrla_2 !== null ? number_format($battery->vrla_2, 2) : ($battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) : '-') }} AH</span>
                                                </div>
                                                <div class="uji-cell-box">
                                                    <span class="uji-cell-label">Batt 3</span>
                                                    <span class="uji-cell-val">{{ $battery->vrla_3 !== null ? number_format($battery->vrla_3, 2) : ($battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) : '-') }} AH</span>
                                                </div>
                                                <div class="uji-cell-box">
                                                    <span class="uji-cell-label">Batt 4</span>
                                                    <span class="uji-cell-val">{{ $battery->vrla_4 !== null ? number_format($battery->vrla_4, 2) : ($battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) : '-') }} AH</span>
                                                </div>
                                            </div>
                                            @if ($battery->kapasitas_uji !== null)
                                                <div class="uji-avg-badge">
                                                    <i class="bi bi-speedometer2"></i>
                                                    <span>Rata-rata: <strong>{{ number_format($battery->kapasitas_uji, 2) }} AH</strong></span>
                                                </div>
                                            @endif
                                        @else
                                            <span>{{ $battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) . ' AH' : '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Baterai (%)</div>
                                <div class="checklist-field">
                                    <span class="field-colon">:</span>
                                    <span class="field-value">
                                        @if ($battery->kapasitas_battery_persen !== null && $battery->kapasitas_battery_persen > 0)
                                            <span class="capacity-percent">{{ number_format($battery->kapasitas_battery_persen, 2) }}%</span>
                                            <span class="status-badge {{ $badgeClass }}">{{ $performa }}</span>
                                        @else
                                            <span class="status-badge status-neutral">BLM UJI BATT</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3 + 4: UJI BATERAI & PHOTO (2-column side by side - OPSI A) --}}
                    <div class="detail-bottom-grid">

                        {{-- Kiri: Uji Baterai --}}
                        <div class="form-card bottom-card">
                            <h3 class="form-section-title">
                                <i class="bi bi-speedometer2"></i> Uji Baterai
                            </h3>

                            @php
                                $statusUjiClass = 'status-neutral';
                                $statusUpper = strtoupper($battery->status_uji ?? '');
                                if ($statusUpper === 'SUDAH UJI BATT' || $statusUpper === 'GOOD' || $statusUpper === 'EXCELLENT') {
                                    $statusUjiClass = 'status-excellent'; // Hijau
                                } elseif ($statusUpper === 'JADWAL UJI BATT' || $statusUpper === 'POOR' || $statusUpper === 'ALERT') {
                                    $statusUjiClass = 'status-danger'; // Merah
                                } elseif ($statusUpper === 'WARNING') {
                                    $statusUjiClass = 'status-warning'; // Orange
                                }
                            @endphp

                            <div class="checklist-table-container">
                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Uji Terakhir</div>
                                    <div class="checklist-field">
                                        <span class="field-colon">:</span>
                                        <span class="field-value">{{ $battery->tanggal_uji_terakhir ? $battery->tanggal_uji_terakhir->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>

                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Penggantian</div>
                                    <div class="checklist-field">
                                        <span class="field-colon">:</span>
                                        <span class="field-value">{{ $battery->tanggal_penggantian ? $battery->tanggal_penggantian->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>

                                <div class="checklist-row">
                                    <div class="checklist-label">Status Uji Baterai</div>
                                    <div class="checklist-field">
                                        <span class="field-colon">:</span>
                                        <span class="field-value">
                                            <span class="status-badge {{ $statusUjiClass }}">{{ $battery->status_uji ?? 'BLM UJI BATT' }}</span>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Kanan: Photo Battery (Figma Framed Card) --}}
                        <div class="form-card bottom-card photo-card-container">
                            <h3 class="form-section-title">
                                <i class="bi bi-camera-fill"></i> Photo Battery
                            </h3>

                            <div class="figma-photo-card">
                                <div class="figma-photo-img-area">
                                    @if (!empty($battery->photo_battery) && file_exists(public_path('storage/' . $battery->photo_battery)))
                                        <img src="{{ asset('storage/' . $battery->photo_battery) }}" 
                                             alt="Foto Baterai" 
                                             id="detailPhoto"
                                             onclick="previewPhoto('{{ asset('storage/' . $battery->photo_battery) }}', '{{ addslashes($battery->keterangan_gambar ?? 'Foto Baterai') }}')"
                                             title="Klik untuk melihat ukuran penuh">
                                    @else
                                        <div class="photo-empty-state">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada dokumentasi foto</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="figma-photo-caption-bar">
                                    <span>{{ $battery->keterangan_gambar ? $battery->keterangan_gambar : 'Keterangan Battery' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>{{-- end detail-bottom-grid --}}

                </div>
            </div>
        </main>
    </div>

    <script>
        function previewPhoto(url, title) {
            Swal.fire({
                title: title || 'Dokumentasi Baterai',
                imageUrl: url,
                imageAlt: title || 'Foto Baterai',
                showCloseButton: true,
                showConfirmButton: false,
                width: 'auto',
                customClass: {
                    popup: 'swal2-photo-modal'
                }
            });
        }
    </script>
</body>
</html>