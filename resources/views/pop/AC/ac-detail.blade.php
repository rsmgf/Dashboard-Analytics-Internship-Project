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
                        <a href="{{ route('acs.index', $pop->id) }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                    <div>
                        @can('acs.index.update')
                        <a href="{{ route('acs.edit', [$pop->id, $ac->id]) }}" class="btn-edit-form">
                            <i class="bi bi-pencil-fill"></i> Edit Form
                        </a>
                        @endcan
                    </div>
                </div>

                @if (session('success'))
                    <div style="margin-bottom: 16px; padding: 12px 16px; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46; font-size: 0.875rem;">
                        <i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="detail-container">

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">POP</span>
                                <span class="detail-value">{{ $pop->kode_pop }}</span>
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
                                    <img src="{{ asset('storage/' . $ac->photo_ac) }}" alt="Foto Kondisi AC" id="detailPhoto">
                                @else
                                    <div id="noDetailPhoto" class="no-preview">
                                        <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                        <span style="font-size: 0.8rem; color: #64748b;">Tidak ada foto tersedia</span>
                                    </div>
                                @endif
                            </div>
                            @if ($ac->keterangan_gambar_ac)
                            <div class="detail-item" style="padding: 12px 16px; border-top: 1px solid #f1f5f9;">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">{{ $ac->keterangan_gambar_ac }}</span>
                            </div>
                            @endif
                        </div>

                    </div>

                    <!-- Update Info -->
                    @if ($ac->diupdateOleh)
                    <div style="padding: 12px 16px; background: #f8fafc; border-radius: 8px; font-size: 0.8rem; color: #64748b; display:flex; align-items:center; gap:8px; margin-top: 16px;">
                        <i class="bi bi-clock-history"></i>
                        <span>Terakhir diupdate oleh <strong>{{ $ac->diupdateOleh->name }}</strong> pada {{ $ac->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
</body>
</html>