<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rectifier - {{ $rectifier->merk ?? 'Rectifier' }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-card.css',
        'resources/css/rectifier-detail.css'
    ])
</head>

<body>
<div class="app-container">

    {{-- SIDEBAR COMPONENT --}}
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">

        {{-- TOPBAR COMPONENT --}}
        <x-topbar />

        <div class="rectifier-detail-content">
            
            {{-- BREADCRUMB --}}
            <x-breadcrumb :items="[
                ['label' => 'POP', 'route' => 'pops.index'],
                ['label' => $pop->nama_pop . ': Rectifier', 'route' => 'rectifiers.index', 'params' => ['pop' => $pop->id]],
                ['label' => 'Detail Rectifier: ' . ($rectifier->merk ?? 'Rectifier')],
            ]" />

            {{-- HEADER ATAS: Back + Judul Informatif + Tombol Edit --}}
            <div class="detail-header-bar">
                <div class="header-left-group">
                    <a href="{{ route('rectifiers.index', $pop->id) }}" class="detail-back" title="Kembali ke Daftar Rectifier">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="header-title-wrapper">
                        <div class="title-with-badge">
                            <h1 class="detail-rectifier-name">{{ $rectifier->nama_alias ?? ($rectifier->merk . ' - ' . $rectifier->type) }}</h1>
                            <span class="device-badge">{{ $rectifier->merk ?? 'Rectifier' }}</span>
                        </div>
                        <span class="pop-sub-info">Point of Presence: <strong>{{ $pop->nama_pop }}</strong> ({{ $pop->kode_pop }})</span>
                    </div>
                </div>

                @can('rectifiers.index.update')
                    <a href="{{ route('rectifiers.edit', [$pop->id, $rectifier->id]) }}" class="btn-edit">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                @endcan
            </div>

            {{-- ALERT BANNER LAST UPDATE --}}
            <div class="alert-info-custom">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>
                    Terakhir diperbarui: 
                    <strong>
                        @if($rectifier->diupdateOleh)
                            {{ $rectifier->diupdateOleh->name }} &middot; {{ $rectifier->updated_at->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            {{ $rectifier->updated_at ? $rectifier->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                        @endif
                    </strong>
                </span>
            </div>

            <div class="detail-top-grid">
                <div class="detail-left-column">
                    {{-- Card Information Rectifier --}}
                    <div class="detail-card information-card">
                        <div class="detail-card-title">
                            <i class="bi bi-info-circle-fill"></i> Information Rectifier
                        </div>

                        <div class="information-grid">
                            <div class="information-left">
                                <div class="info-row">
                                    <span class="info-label">Merk</span>
                                    <span class="info-value">{{ $rectifier->merk ?? '-' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Type</span>
                                    <span class="info-value">{{ $rectifier->type ?? '-' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Type POP</span>
                                    <span class="info-value">{{ $pop->tipe_pop ?? '-' }}</span>
                                </div>

                                <div class="serial-box">
                                    <span class="serial-label">Serial Number (SN)</span>
                                    <div class="serial-value">
                                        <span id="snRectifier">{{ $rectifier->sn_rectifier ?? '-' }}</span>
                                        @if($rectifier->sn_rectifier)
                                            <button type="button" onclick="copySerial()" title="Copy Serial Number">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="information-right">
                                <div class="info-small-row">
                                    <span class="info-label">Jumlah Modul Terpasang</span>
                                    <span class="info-value">
                                        {{ $rectifier->modules->count() }} Modul <small style="color: #64748b; font-weight: 500;">(dari {{ $rectifier->kapasitas_slot ?? '-' }} slot)</small>
                                    </span>
                                </div>

                                <div class="info-small-row">
                                    <span class="info-label">Kapasitas / Module</span>
                                    <span class="info-value">
                                        @if($rectifier->modules->isNotEmpty() && $rectifier->modules->first()->kapasitas_ampere)
                                            {{ $rectifier->modules->first()->kapasitas_ampere }}
                                        @elseif($rectifier->kapasitas_rectifier)
                                            {{ $rectifier->kapasitas_rectifier }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="info-small-row">
                                    <span class="info-label">Building</span>
                                    <span class="info-value">{{ $pop->jenis_bangunan ?? '-' }}</span>
                                </div>

                                <div class="info-small-row">
                                    <span class="info-label">Couple</span>
                                    <span class="info-value">{{ $rectifier->couple ?? '-' }}</span>
                                </div>

                                <div class="info-small-row">
                                    <span class="info-label">Utilisasi</span>
                                    <span class="info-value">{{ $rectifier->utilisasi ? $rectifier->utilisasi . '%' : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Serial Number Module --}}
                    <div class="detail-card module-card">
                        <div class="section-heading">
                            <span><i class="bi bi-cpu-fill"></i> Serial Number Module</span>
                            <small>{{ $rectifier->modules->count() }} dari {{ $rectifier->kapasitas_slot ?? '?' }} module terpasang</small>
                        </div>

                        <div class="module-table">
                            @forelse($rectifier->modules as $module)
                                <div class="module-row">
                                    <div><strong>Module {{ $loop->iteration }}</strong></div>
                                    <div><code>{{ $module->sn_modul ?? '-' }}</code></div>
                                </div>
                            @empty
                                <div style="text-align:center; color:#64748b; padding:16px; font-size: 0.8rem;">
                                    Belum ada data module terpasang.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Card Photo Rectifier --}}
                <div class="detail-card photo-card">
                    <div class="photo-header">
                        <div class="photo-icon">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                        <span class="photo-title">Photo Rectifier</span>
                    </div>

                    <div class="photo-wrapper">
                        @if($rectifier->foto_rectifier)
                            <img src="{{ asset('storage/' . $rectifier->foto_rectifier) }}" alt="Photo Rectifier"
                                 onerror="this.style.display='none'; document.getElementById('photoPlaceholder').style.display='flex';">
                            <div id="photoPlaceholder" class="photo-placeholder" style="display:none;">
                                <i class="bi bi-image"></i>
                                <span>Foto Rectifier</span>
                            </div>
                        @else
                            <img src="{{ asset('images/rectifier.png') }}" alt="Photo Rectifier"
                                 onerror="this.style.display='none'; document.getElementById('photoPlaceholder').style.display='flex';">
                            <div id="photoPlaceholder" class="photo-placeholder" style="display:none;">
                                <i class="bi bi-image"></i>
                                <span>Foto Rectifier</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Output --}}
            <div class="detail-card output-card">
                <div class="section-heading">
                    <span><i class="bi bi-lightning-charge-fill"></i> Output (MCB)</span>
                </div>

                @php
                    $outputs = $rectifier->outputs;
                    $half    = (int) ceil(max($outputs->count(), 1) / 2);
                    $left    = $outputs->take($half);
                    $right   = $outputs->skip($half);
                @endphp

                <div class="output-grid">
                    <table class="output-table">
                        <thead>
                            <tr>
                                <th>MCB</th>
                                <th>Merk</th>
                                <th>Kapasitas</th>
                                <th>Peruntukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($left as $output)
                                <tr>
                                    <td><strong>{{ $output->nama_mcb ?? 'MCB ' . $loop->iteration }}</strong></td>
                                    <td>{{ $output->merk_mcb ?? '-' }}</td>
                                    <td>{{ $output->kapasitas_mcb ?? '-' }}</td>
                                    <td>{{ $output->peruntukan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center; color:#64748b; padding:12px;">Belum ada data output.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($right->isNotEmpty())
                        <table class="output-table">
                            <thead>
                                <tr>
                                    <th>MCB</th>
                                    <th>Merk</th>
                                    <th>Kapasitas</th>
                                    <th>Peruntukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($right as $output)
                                    <tr>
                                        <td><strong>{{ $output->nama_mcb ?? 'MCB ' . ($loop->iteration + $half) }}</strong></td>
                                        <td>{{ $output->merk_mcb ?? '-' }}</td>
                                        <td>{{ $output->kapasitas_mcb ?? '-' }}</td>
                                        <td>{{ $output->peruntukan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Card Checklist Rectifier --}}
            <div class="detail-card checklist-card">
                <div class="section-heading checklist-heading">
                    <span><i class="bi bi-clipboard-check-fill"></i> Checklist Rectifier</span>
                </div>

                <div class="checklist-header-box">
                    <div class="checklist-row">
                        <div class="checklist-label">POP</div>
                        <div class="checklist-value">: {{ $pop->nama_pop }} ({{ $pop->kode_pop }})</div>
                        <div class="checklist-label">Tanggal</div>
                        <div class="checklist-value">: <strong>{{ $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->translatedFormat('d F Y') : ($rectifier->updated_at ? $rectifier->updated_at->translatedFormat('d F Y') : '-') }}</strong></div>
                    </div>

                    <div class="checklist-row">
                        <div class="checklist-label">PIC</div>
                        <div class="checklist-value">: {{ $rectifier->pic ?? ($pop->user->name ?? '-') }}</div>
                        <div class="checklist-label">Deskripsi</div>
                        <div class="checklist-value">: {{ $rectifier->deskripsi ?? '-' }}</div>
                    </div>
                </div>

                <div class="checklist-section-title">{{ $rectifier->nama_alias ?? ($rectifier->merk . ' - ' . $rectifier->type) }}</div>

                <div class="spec-table-wrapper">
                    <table class="spec-table">
                        <tbody>
                            <tr>
                                <th>Merk</th>
                                <td>: {{ $rectifier->merk ?? '-' }}</td>
                                <th>Type Modul Controller</th>
                                <td>: {{ $rectifier->type_modul_controller ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>: {{ $rectifier->type ?? '-' }}</td>
                                <th>Type Modul Power</th>
                                <td>: {{ $rectifier->type_modul_power ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>SN Rectifier</th>
                                <td>: {{ $rectifier->sn_rectifier ?? '-' }}</td>
                                <th>Kapasitas Rectifier</th>
                                <td>: {{ $rectifier->kapasitas_rectifier ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Couple / Tidak</th>
                                <td>: {{ $rectifier->couple ?? '-' }}</td>
                                <th>Beban (A)</th>
                                <td>: {{ $rectifier->beban ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Slot Modul</th>
                                <td>: {{ $rectifier->kapasitas_slot ?? '-' }}</td>
                                <th>Utilisasi (%)</th>
                                <td>: {{ $rectifier->utilisasi ? $rectifier->utilisasi . '%' : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Modul Terpasang</th>
                                <td>: {{ $rectifier->modules->count() }} modul</td>
                                <th>Building</th>
                                <td>: {{ $pop->jenis_bangunan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Type POP</th>
                                <td>: {{ $pop->tipe_pop ?? '-' }}</td>
                                <th>Lokasi</th>
                                <td>: {{ $pop->kota_kabupaten }}, {{ $pop->provinsi }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function copySerial() {
    const serial = document.getElementById('snRectifier').textContent.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(serial)
            .then(() => alert('Serial number berhasil disalin: ' + serial))
            .catch(() => alert('Serial number: ' + serial));
    } else {
        alert('Serial number: ' + serial);
    }
}
</script>
</body>
</html>
