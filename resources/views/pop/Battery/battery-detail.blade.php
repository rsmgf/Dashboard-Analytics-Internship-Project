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
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <p class="form-section-subtitle">Informasi Spesifikasi & Kapasitas</p>

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

                        <div class="table-detail-container">
                            <table class="table-detail">
                                <tbody>
                                    <tr>
                                        <td class="td-label">Nomor Bank</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val font-semibold">{{ $battery->nomor_bank }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Nomor Rectifier</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->nomor_recti }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Merk Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->merk_battery }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Jenis Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->jenis_battery ?? 'Lithium' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Tipe Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->tipe_battery }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Baterai (AH)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->kapasitas_battery }} AH</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Uji (AH)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2) . ' AH' : '-' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Kapasitas Baterai (%)</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">
                                            @if ($battery->kapasitas_battery_persen !== null && $battery->kapasitas_battery_persen > 0)
                                                <span class="capacity-value" style="font-weight: 700;">{{ number_format($battery->kapasitas_battery_persen, 2) }}%</span>
                                                <span class="status-badge {{ $badgeClass }}" style="margin-left: 8px;">{{ $performa }}</span>
                                            @else
                                                <span class="status-badge status-warning">BLM UJI BATT</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SECTION 3: UJI BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>

                        @php
                            $statusUjiClass = 'status-warning';
                            if ($battery->status_uji === 'SUDAH UJI BATT') {
                                $statusUjiClass = 'status-good';
                            } elseif ($battery->status_uji === 'JADWAL UJI BATT') {
                                $statusUjiClass = 'status-danger';
                            }
                        @endphp

                        <div class="table-detail-container">
                            <table class="table-detail">
                                <tbody>
                                    <tr>
                                        <td class="td-label">Tanggal Uji Terakhir</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->tanggal_uji_terakhir ? $battery->tanggal_uji_terakhir->format('d/m/Y') : '-' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Tanggal Penggantian</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->tanggal_penggantian ? $battery->tanggal_penggantian->format('d/m/Y') : '-' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Status Uji Baterai</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">
                                            <span class="status-badge {{ $statusUjiClass }}">{{ $battery->status_uji ?? 'BLM UJI BATT' }}</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Area STI</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val">{{ $battery->area_sti ?? $pop->kota_kabupaten }}</td>
                                    </tr>

                                    <tr>
                                        <td class="td-label">Terakhir Diperbarui Oleh</td>
                                        <td class="td-separator">:</td>
                                        <td class="td-val text-sub">{{ $battery->diupdateOleh?->name ?? 'Admin' }} &middot; {{ $battery->updated_at->format('d M Y, H.i') }} WIB</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('batteries.index', $pop->id) }}" class="btn-reset" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Kembali</a>
                        @can('batteries.index.update')
                        <a href="{{ route('batteries.edit', [$pop->id, $battery->id]) }}" class="btn-submit btn-edit-detail" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
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