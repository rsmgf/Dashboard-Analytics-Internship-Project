<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Air Conditioner - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-detail.css'
    ])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="ac-content">
                <div class="detail-page-header">
                    <div class="ac-page-info" style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('acs.index', $pop->id) }}" class="back-button" title="Kembali ke List AC">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop_display . ': AC', 'route' => 'acs.index', 'params' => ['pop' => $pop->id]],
                            ['label' => $ac->nomor_ac],
                        ]" />
                    </div>
                    <div>
                        @can('acs.index.update')
                        <a href="{{ route('acs.edit', [$pop->id, $ac->id]) }}" class="btn-edit-form">
                            <i class="bi bi-pencil-fill"></i> Edit Form
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- Alert Banner Last Update --}}
                <div class="alert-info-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>
                        Terakhir diperbarui:
                        <strong>
                            @if($ac->diupdateOleh)
                                {{ $ac->diupdateOleh->name }} &middot; {{ $ac->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                {{ $ac->updated_at ? $ac->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                            @endif
                        </strong>
                    </span>
                </div>

                <div class="detail-container">

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">{{ $pop->nama_pop_display }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Kota / Kabupaten</span>
                                <span class="detail-value">{{ $pop->kota_kabupaten }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tipe POP</span>
                                <span class="detail-value">{{ $pop->tipe_pop ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-bottom-grid">

                        <!-- Checklist AC -->
                        <div class="form-card mb-0">
                            <h3 class="form-section-title">Checklist Air Conditioner</h3>
                            <div class="checklist-table-container">
                                <div class="checklist-row">
                                    <div class="checklist-label">Nomor AC</div>
                                    <div class="checklist-field">{{ $ac->nomor_ac }}</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Merk AC</div>
                                    <div class="checklist-field">{{ $ac->merk_ac }}</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Type AC</div>
                                    <div class="checklist-field">{{ $ac->type_ac }}</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">PK</div>
                                    <div class="checklist-field">{{ $ac->pk }} PK</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Jenis Freon</div>
                                    <div class="checklist-field">{{ $ac->jenis_freon }}</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tahun Manufaktur</div>
                                    <div class="checklist-field">{{ $ac->tahun_manufaktur }}</div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Instalasi</div>
                                    <div class="checklist-field">
                                        {{ $ac->tanggal_instalasi ? $ac->tanggal_instalasi->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                                <div class="checklist-row">
                                    <div class="checklist-label">Tanggal Terakhir PM</div>
                                    <div class="checklist-field">
                                        @php
                                            $status     = $ac->status_ac ?? 'Belum PM';
                                            $badgeClass = match($status) {
                                                'Sudah PM'  => 'status-excellent',
                                                'Jadwal PM' => 'status-warning',
                                                default     => 'status-neutral',
                                            };
                                        @endphp
                                        {{ $ac->tanggal_terakhir_pm ? $ac->tanggal_terakhir_pm->translatedFormat('d F Y') : '-' }}
                                        &nbsp;
                                        <span class="status-badge {{ $badgeClass }}">{{ strtoupper($status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photo AC -->
                        <div class="form-card mb-0 photo-card-wrapper">
                            <h3 class="form-section-title">Photo Air Conditioner</h3>
                            <div class="detail-photo-box">
                                @if ($ac->photo_ac)
                                    <img src="{{ asset('storage/' . $ac->photo_ac) }}" alt="Foto Kondisi AC" id="detailPhoto"
                                         onclick="Swal.fire({ title: '{{ addslashes($ac->keterangan_gambar_ac ?? 'Foto AC') }}', imageUrl: this.src, imageAlt: 'Foto AC', showCloseButton: true, showConfirmButton: false, width: 'auto', customClass: { popup: 'swal-popup-custom' } })"
                                         title="Klik untuk melihat ukuran penuh">
                                @else
                                    <div id="noDetailPhoto" class="no-preview">
                                        <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                        <span style="font-size: 0.8rem; color: #64748b;">Tidak ada foto tersedia</span>
                                    </div>
                                @endif
                            </div>
                            @if ($ac->keterangan_gambar_ac)
                            <div class="detail-item mt-3">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">{{ $ac->keterangan_gambar_ac }}</span>
                            </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>