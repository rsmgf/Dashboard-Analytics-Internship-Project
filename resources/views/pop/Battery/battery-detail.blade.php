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
                <div class="detail-header-bar">
                    <div class="header-left-group">
                        <a href="{{ route('batteries.index', $pop->id) }}" class="detail-back" title="Kembali ke Daftar Baterai">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-title-wrapper">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <x-breadcrumb :items="[
                                    ['label' => 'POP', 'route' => 'pops.index'],
                                    ['label' => $pop->nama_pop, 'route' => 'batteries.index', 'params' => ['pop' => $pop->id]],
                                    ['label' => $battery->nomor_bank],
                                ]" />
                                <span class="device-badge">{{ $battery->merk_battery }} &bull; {{ $battery->kapasitas_battery }} AH</span>
                            </div>
                            <span class="pop-sub-info">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</span>
                        </div>
                    </div>

                    @can('batteries.index.update')
                    <a href="{{ route('batteries.edit', [$pop->id, $battery->id]) }}" class="btn-edit">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                    @endcan
                </div>

                {{-- Flash Message Success --}}
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        });
                    </script>
                @endif

                <div class="detail-container">
                    {{-- SECTION 1: GENERAL INFORMATION --}}
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">{{ $pop->kode_pop }} - {{ $pop->nama_pop }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Building</span>
                                <span class="detail-value">{{ $battery->building ?? $pop->jenis_bangunan }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">PIC</span>
                                <span class="detail-value">{{ $battery->pic ?? '-' }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Type POP</span>
                                <span class="detail-value">{{ $battery->type_pop ?? $pop->tipe_pop }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Rectifier / Keterangan</span>
                                <span class="detail-value">{{ $battery->nomor_recti }} {{ $battery->recti ? '(' . $battery->recti . ')' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <p class="form-section-subtitle">Informasi Baterai</p>

                        @php
                            $persen = $battery->kapasitas_battery_persen;
                            $performa = $battery->performa_baterai ?? 'BLM UJI BATT';

                            $badgeClass = 'status-warning';
                            if ($persen === null || $persen <= 0) {
                                $badgeClass = 'status-warning';
                            } elseif ($persen >= 90) {
                                $badgeClass = 'status-good';
                            } elseif ($persen >= 75) {
                                $badgeClass = 'status-good';
                            } elseif ($persen >= 50) {
                                $badgeClass = 'status-warning';
                            } else {
                                $badgeClass = 'status-danger';
                            }
                        @endphp

                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti</div>
                                <div class="checklist-field">{{ $battery->nomor_recti }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank</div>
                                <div class="checklist-field">{{ $battery->nomor_bank }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery</div>
                                <div class="checklist-field">{{ $battery->merk_battery }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Battery</div>
                                <div class="checklist-field">{{ strtoupper($battery->jenis_battery ?? 'LITHIUM') }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery</div>
                                <div class="checklist-field">{{ $battery->tipe_battery }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V)</div>
                                <div class="checklist-field">{{ $battery->tegangan ?? 48 }} V</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH)</div>
                                <div class="checklist-field">{{ $battery->kapasitas_battery }} AH</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery</div>
                                <div class="checklist-field">
                                    <div class="uji-val-single">{{ $battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) . ' AH' : '-' }}</div>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery %</div>
                                <div class="checklist-field">
                                    @if ($battery->kapasitas_battery_persen !== null && $battery->kapasitas_battery_persen > 0)
                                        <span class="capacity-value">{{ number_format($battery->kapasitas_battery_persen, 2) }}%</span>
                                        <span class="status-badge {{ $badgeClass }}" style="margin-left: 8px;">{{ $performa }}</span>
                                    @else
                                        <span class="status-badge status-warning">BLM UJI BATT</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: UJI BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>

                        @php
                            $statusUjiClass = 'status-warning';
                            if ($battery->status_uji === 'SUDAH UJI BATT' || $battery->status_uji === 'good' || $battery->status_uji === 'excellent') {
                                $statusUjiClass = 'status-good';
                            } elseif ($battery->status_uji === 'JADWAL UJI BATT' || $battery->status_uji === 'poor') {
                                $statusUjiClass = 'status-danger';
                            }
                        @endphp

                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Tanggal Uji Terakhir</div>
                                <div class="checklist-field">{{ $battery->tanggal_uji_terakhir ? $battery->tanggal_uji_terakhir->format('d/m/Y') : '-' }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tanggal Penggantian</div>
                                <div class="checklist-field">{{ $battery->tanggal_penggantian ? $battery->tanggal_penggantian->format('d/m/Y') : '-' }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Status Uji Baterai</div>
                                <div class="checklist-field">
                                    <span class="status-badge {{ $statusUjiClass }}">{{ $battery->status_uji ?? 'BLM UJI BATT' }}</span>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Area STI</div>
                                <div class="checklist-field">{{ $battery->area_sti ?? $pop->kota_kabupaten }}</div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Terakhir Diperbarui Oleh</div>
                                <div class="checklist-field text-sub" style="font-size: 0.85rem; color: #64748b;">{{ $battery->diupdateOleh?->name ?? 'Admin' }} &middot; {{ $battery->updated_at->format('d M Y, H.i') }} WIB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Tambahan Photo Battery pada Detail -->
                    <div class="form-card">
                        <h3 class="form-section-title">Photo Battery</h3>
                        <div class="detail-grid-3" style="align-items: center;">
                            <div class="detail-item" style="grid-column: span 2;">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">{{ $battery->keterangan_gambar ?? 'Kondisi baterai di lokasi POP' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Dokumentasi Foto</span>
                                <div class="preview-box" style="height: 100px; width: 150px; margin-top: 5px;">
                                    @if (!empty($battery->photo_battery) && file_exists(public_path('storage/' . $battery->photo_battery)))
                                        <img src="{{ asset('storage/' . $battery->photo_battery) }}" alt="Foto Baterai" style="width: 100%; height: 100%; object-fit: cover; display: block;" id="detailPhoto">
                                    @else
                                        <div class="no-preview" id="noPhotoDetail">
                                            <i class="bi bi-image" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                                            <span style="font-size: 0.65rem;">Tidak ada foto</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <a href="{{ route('batteries.index', $pop->id) }}" class="btn-reset" style="height: 42px; padding: 0 24px; border: none; border-radius: 8px; background: #64748b; color: #ffffff; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Kembali</a>
                        @can('batteries.index.update')
                        <a href="{{ route('batteries.edit', [$pop->id, $battery->id]) }}" class="btn-submit btn-edit-detail" style="height: 42px; padding: 0 28px; border-radius: 8px; background: #0070d8; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                            <i class="bi bi-pencil-fill"></i>
                            Edit Data Baterai
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>