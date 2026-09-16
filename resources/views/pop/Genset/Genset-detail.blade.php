<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-detail.css'
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
                    <div class="rectifier-page-info">
                        <a href="{{ route('gensets.index', $pop->id) }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                    <div class="page-action-buttons">
                        @can('gensets.index.update')
                        <a href="{{ route('gensets.edit', [$pop->id, $genset->id]) }}" class="btn-edit-form">
                            <i class="bi bi-pencil-fill"></i> Edit Data
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
                            <div class="detail-item">
                                <span class="detail-label">PIC</span>
                                <span class="detail-value">{{ $genset->pic }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Bentuk Fisik</span>
                                <span class="detail-value">{{ $genset->bentuk_fisik }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nomor Genset</span>
                                <span class="detail-value">{{ $genset->nomor_genset }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Genset</h3>
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Merk Genset</div>
                                <div class="checklist-field">{{ $genset->merk_genset }}</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Model</div>
                                <div class="checklist-field">{{ $genset->model }}</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">SN Genset</div>
                                <div class="checklist-field">{{ $genset->sn_genset }}</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas (KVA)</div>
                                <div class="checklist-field">{{ $genset->kapasitas_kva }} KVA</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Engine</div>
                                <div class="checklist-field">{{ $genset->tipe_engine }}</div>
                            </div>
                            <div class="checklist-row">
                                <div class="checklist-label">SN Engine</div>
                                <div class="checklist-field">{{ $genset->sn_engine }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Uji Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Genset</h3>
                        <div class="detail-grid-3">
                            <div class="detail-item">
                                <span class="detail-label">Tahun Pasang</span>
                                <span class="detail-value">{{ $genset->tahun_pasang }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal PM (Pemeliharaan Rutin)</span>
                                <span class="detail-value">
                                    {{ $genset->tanggal_pm ? $genset->tanggal_pm->translatedFormat('d F Y') : '-' }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status Genset</span>
                                <div class="detail-value">
                                    @php
                                        $status    = $genset->status_genset ?? 'Belum PM';
                                        $badgeClass = match($status) {
                                            'Sudah PM'  => 'status-excellent',
                                            'Jadwal PM' => 'status-warning',
                                            default     => 'status-neutral',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">{{ strtoupper($status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Section (Genset & Engine) -->
                    <div class="photo-grid-2">
                        <!-- Photo Genset -->
                        <div class="form-card photo-card">
                            <h3 class="form-section-title">Photo Genset</h3>
                            <div class="preview-box-large">
                                @if ($genset->photo_genset)
                                    <img src="{{ asset('storage/' . $genset->photo_genset) }}" alt="Foto Genset">
                                @else
                                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:200px; color:#94a3b8;">
                                        <i class="bi bi-image" style="font-size:2.5rem;"></i>
                                        <span style="font-size:0.875rem; margin-top:8px;">Belum ada foto</span>
                                    </div>
                                @endif
                            </div>
                            @if ($genset->keterangan_gambar_genset)
                            <div class="detail-item mt-3">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">{{ $genset->keterangan_gambar_genset }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Photo Engine -->
                        <div class="form-card photo-card">
                            <h3 class="form-section-title">Photo Engine</h3>
                            <div class="preview-box-large">
                                @if ($genset->photo_engine)
                                    <img src="{{ asset('storage/' . $genset->photo_engine) }}" alt="Foto Engine">
                                @else
                                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:200px; color:#94a3b8;">
                                        <i class="bi bi-image" style="font-size:2.5rem;"></i>
                                        <span style="font-size:0.875rem; margin-top:8px;">Belum ada foto</span>
                                    </div>
                                @endif
                            </div>
                            @if ($genset->keterangan_gambar_engine)
                            <div class="detail-item mt-3">
                                <span class="detail-label">Keterangan Gambar</span>
                                <span class="detail-value">{{ $genset->keterangan_gambar_engine }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Update Info -->
                    @if ($genset->diupdateOleh)
                    <div style="padding: 12px 16px; background: #f8fafc; border-radius: 8px; font-size: 0.8rem; color: #64748b; display:flex; align-items:center; gap:8px;">
                        <i class="bi bi-clock-history"></i>
                        <span>Terakhir diupdate oleh <strong>{{ $genset->diupdateOleh->name }}</strong> pada {{ $genset->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>