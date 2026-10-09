<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Detail Air Conditioner - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite(['resources/css/sidebar.css', 'resources/css/ac-detail.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    class="{{ (session('active_role') ?? (auth()->user()?->hasRole('manajer') ? 'manajer' : 'super_admin')) === 'manajer' ? 'manajer-mode' : '' }}">
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="ac-content">
                <div class="detail-header-bar">
                    <div class="header-left-group">
                        <a href="{{ route('acs.index', $pop->id) }}" class="detail-back" title="Kembali ke List AC" aria-label="Kembali ke List AC">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-title-wrapper">
                            <div class="title-with-badge">
                                <x-breadcrumb :items="[
                                    ['label' => 'POP', 'route' => 'pops.index'],
                                    ['label' => $pop->kode_pop . ': AC', 'route' => 'acs.index', 'params' => ['pop' => $pop->id]],
                                    ['label' => $ac->nomor_ac],
                                ]" />
                                <span class="device-badge">{{ $ac->merk_ac }} &bull; {{ $ac->pk }} PK</span>
                            </div>
                        </div>
                    </div>

                    @can('acs.index.update')
                    <a href="{{ route('acs.edit', [$pop->id, $ac->id]) }}" class="btn-edit">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                    @endcan
                </div>

                {{-- Alert Banner Last Update --}}
                <div class="alert-info-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>
                        Terakhir diperbarui oleh:
                        <strong>
                            @if ($ac->diupdateOleh)
                                {{ $ac->diupdateOleh->name }} &middot;
                                {{ $ac->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                {{ $ac->updated_at ? $ac->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                            @endif
                        </strong>
                    </span>
                </div>

                <div class="detail-container">

                    <!-- General Information -->
                    <section class="detail-card information-card">
                        <div class="detail-card-title">
                            <i class="bi bi-info-circle-fill"></i> General Information
                        </div>
                        <div class="info-box-grid">
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-upc-scan"></i></div>
                                <div class="info-box-text"><span class="info-box-label">Nomor AC</span><span class="info-box-value">{{ $ac->nomor_ac }}</span></div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                <div class="info-box-text">
                                    <span class="info-box-label">POP</span>
                                    <span class="info-box-value">{{ $pop->nama_pop_display }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-building-fill"></i></div>
                                <div class="info-box-text"><span class="info-box-label">Building / Jenis Bangunan</span><span class="info-box-value">{{ $pop->jenis_bangunan ?? '-' }}</span></div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-tag-fill"></i></div>
                                <div class="info-box-text">
                                    <span class="info-box-label">Tipe POP</span>
                                    <span class="info-box-value">{{ $pop->tipe_pop ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-calendar-check-fill"></i></div>
                                <div class="info-box-text"><span class="info-box-label">Tanggal Pemeriksaan</span><span class="info-box-value">{{ $ac->tanggal_pemeriksaan?->translatedFormat('d F Y') ?? 'Belum diisi' }}</span></div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-calendar-event"></i></div>
                                <div class="info-box-text"><span class="info-box-label">Tahun Manufaktur</span><span class="info-box-value">{{ $ac->tahun_manufaktur ?? '-' }}</span></div>
                            </div>
                            <div class="info-box">
                                <div class="info-box-icon"><i class="bi bi-person-fill"></i></div>
                                <div class="info-box-text"><span class="info-box-label">PIC</span><span class="info-box-value">{{ $ac->pic ?? '-' }}</span></div>
                            </div>
                        </div>
                    </section>

                    <div class="detail-bottom-grid">

                        <!-- Checklist AC -->
                        <section class="detail-card">
                            <div class="detail-card-title">
                                <i class="bi bi-clipboard-check-fill"></i> Checklist Air Conditioner
                            </div>
                            <div class="detail-card-body">
                            <div class="checklist-table-container">
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
                                                'Jadwal PM' => 'status-danger',
                                                'Belum PM'  => 'status-warning',
                                                default     => 'status-warning',
                                            };
                                        @endphp
                                        {{ $ac->tanggal_terakhir_pm ? $ac->tanggal_terakhir_pm->translatedFormat('d F Y') : '-' }}
                                        &nbsp;
                                        <span
                                            class="status-badge {{ $badgeClass }}">{{ strtoupper($status) }}</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </section>

                        <!-- Photo AC -->
                        <section class="detail-card photo-card-wrapper">
                            <div class="detail-card-title">
                                <i class="bi bi-camera-fill"></i> Photo Air Conditioner
                            </div>
                            <div class="detail-card-body">
                            <div class="detail-photo-box">
                                @if ($ac->photo_ac && \Illuminate\Support\Facades\Storage::disk('public')->exists($ac->photo_ac))
                                    <img src="{{ asset('storage/' . $ac->photo_ac) }}" alt="Foto Kondisi AC" id="detailPhoto"
                                         onclick="Swal.fire({ title: '{{ addslashes($ac->keterangan_gambar_ac ?? 'Foto AC') }}', imageUrl: this.src, imageAlt: 'Foto AC', showCloseButton: true, showConfirmButton: false, width: 'min(92vw, 900px)', heightAuto: false, customClass: { popup: 'swal-popup-custom' } })"
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
                        </section>

                    </div>

                </div>
            </div>
        </main>
    </div>
</body>

</html>
