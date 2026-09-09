<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Data Baterai - PLN Icon Plus</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Vite CSS --}}
    @vite([
        'resources/css/sidebar.css',
        'resources/css/battery-card.css'
    ])
</head>

<body>

<div class="app-container">

    {{-- ================= SIDEBAR ================= --}}
    <x-sidebar />

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    {{-- ================= MAIN ================= --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <x-topbar />

        {{-- CONTENT --}}
        <div class="rectifier-content">

            {{-- HEADER HALAMAN --}}
            <div class="rectifier-page-header">

                <div class="rectifier-page-info">

                    <a href="{{ route('pops.index') }}"
                       class="back-button"
                       title="Kembali ke Daftar POP">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <div class="rectifier-header-text">

                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop]
                        ]" />

                        <span class="rectifier-pop-sub">
                            Kode: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }} &mdash; {{ $batteries->count() }} Baterai
                        </span>

                    </div>

                </div>

                @can('batteries.index.create')
                <a href="{{ route('batteries.create', $pop->id) }}"
                   class="btn-tambah-rectifier">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Baterai
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

            {{-- ==================================================
                BATTERY SECTIONS GROUPED BY RECTIFIER
            ================================================== --}}
            @forelse ($groupedBatteries as $nomorRecti => $batteryGroup)
                @php
                    $stats = $rectifierBackupStats[$nomorRecti] ?? null;
                    $badgeClass = $stats['badge_class'] ?? 'status-warning';
                    $performaText = $stats['performa_backup'] ?? 'BLM UJI BATT';
                    $backupJam = $stats['backup_time'] !== null ? $stats['backup_time'] . ' Jam' : '-';
                @endphp

                <div class="rectifier-section">

                    <div class="rectifier-header">
                        <div class="rectifier-title">
                            Rectifier : {{ $nomorRecti }}
                        </div>
                        <div class="backup-time {{ $badgeClass }}">
                            Performance Backup Time : {{ $backupJam }} ({{ $performaText }})
                        </div>
                    </div>

                    <div class="rectifier-grid">

                        @foreach ($batteryGroup as $battery)
                            @php
                                $persen = $battery->kapasitas_battery_persen;
                                $performa = $battery->performa_baterai;

                                $badgeStyleClass = 'status-excellent';
                                $dotClass = 'dot-excellent';

                                if ($persen === null || $persen <= 0) {
                                    $badgeStyleClass = 'status-warning';
                                    $dotClass = 'dot-warning';
                                    $performaBadgeText = 'BLM UJI BATT';
                                } elseif ($persen >= 90) {
                                    $badgeStyleClass = 'status-excellent';
                                    $dotClass = 'dot-excellent';
                                    $performaBadgeText = $persen . '% - EXCELLENT';
                                } elseif ($persen >= 75) {
                                    $badgeStyleClass = 'status-good-enough';
                                    $dotClass = 'dot-good-enough';
                                    $performaBadgeText = $persen . '% - GOOD ENOUGH';
                                } elseif ($persen >= 50) {
                                    $badgeStyleClass = 'status-warning';
                                    $dotClass = 'dot-warning';
                                    $performaBadgeText = $persen . '% - WARNING';
                                } else {
                                    $badgeStyleClass = 'status-danger';
                                    $dotClass = 'dot-danger';
                                    $performaBadgeText = $persen . '% - ALERT';
                                }
                            @endphp

                            <div class="rectifier-card battery-card" id="battery-card-{{ $battery->id }}">

                                {{-- Card Header --}}
                                <div class="rectifier-card-header">
                                    <div class="card-icon">
                                        <i class="bi bi-battery-charging"></i>
                                    </div>
                                    <div class="card-title-text">
                                        <h3>{{ $battery->nomor_bank }}</h3>
                                        <span>Data Baterai ({{ $battery->jenis_battery ?? 'Lithium' }})</span>
                                    </div>
                                </div>

                                <div class="rectifier-information">
                                    <div class="equipment-info">
                                        <strong>Merk</strong>
                                        <span class="info-sep">:</span>
                                        <span class="data-value">{{ $battery->merk_battery }}</span>
                                    </div>
                                    <div class="equipment-info">
                                        <strong>Tipe</strong>
                                        <span class="info-sep">:</span>
                                        <span class="data-value">{{ $battery->tipe_battery }}</span>
                                    </div>
                                    <div class="equipment-info">
                                        <strong>Kapasitas</strong>
                                        <span class="info-sep">:</span>
                                        <span class="data-value">{{ $battery->kapasitas_battery }} AH</span>
                                    </div>
                                    <div class="equipment-info">
                                        <strong>Performa Baterai</strong>
                                        <span class="info-sep">:</span>
                                        <span class="data-value">
                                            <span class="status-badge {{ $badgeStyleClass }}">
                                                {{ $performaBadgeText }}
                                                <span class="status-dot {{ $dotClass }}"></span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="equipment-info">
                                        <strong>Status Uji</strong>
                                        <span class="info-sep">:</span>
                                        <span class="data-value">{{ $battery->status_uji ?? 'BLM UJI BATT' }}</span>
                                    </div>
                                </div>

                                <div class="rectifier-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event-fill"></i>
                                        <span>{{ $battery->tanggal_uji_terakhir ? $battery->tanggal_uji_terakhir->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $battery->area_sti ?? $pop->kota_kabupaten }}</span>
                                    </div>
                                </div>

                                <div class="rectifier-last-updated">
                                    <i class="bi bi-clock-history"></i>
                                    <span>{{ $battery->diupdateOleh?->name ?? 'Admin' }} &middot; {{ $battery->updated_at->format('d M Y, H.i') }} WIB</span>
                                </div>

                                <div class="rectifier-card-footer">
                                    @can('batteries.index.delete')
                                    <button type="button" class="btn-hapus" onclick="hapusBaterai('{{ route('batteries.destroy', [$pop->id, $battery->id]) }}', '{{ $battery->nomor_bank }}')">
                                        <i class="bi bi-trash3-fill"></i>
                                        Hapus
                                    </button>
                                    @endcan
                                    <a href="{{ route('batteries.show', [$pop->id, $battery->id]) }}" class="detail-button">
                                        <span>Detail</span>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            @empty
                <div class="empty-battery">
                    <i class="bi bi-battery"></i>
                    <h3>Belum Ada Data Baterai</h3>
                    <p>Silakan tambahkan data baterai untuk POP <strong>{{ $pop->nama_pop }}</strong> menggunakan tombol <strong>Tambah Baterai</strong> di kanan atas.</p>
                </div>
            @endforelse

        </div>

    </main>

</div>

{{-- SweetAlert2 Delete Handler --}}
<script>
    function hapusBaterai(deleteUrl, batteryName) {
        Swal.fire({
            title: 'Hapus Baterai?',
            html: `Apakah Anda yakin ingin menghapus data <strong>"${batteryName}"</strong>?<br><small style="color: #64748b;">Data baterai dan riwayat pengujiannya akan dihapus permanen.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

</body>
</html>