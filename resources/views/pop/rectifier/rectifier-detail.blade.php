<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Detail Rectifier - {{ $rectifier->merk ?? 'Rectifier' }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-detail.css'
    ])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="{{ (session('active_role') ?? (auth()->user()?->hasRole('manajer') ? 'manajer' : 'super_admin')) === 'manajer' ? 'manajer-mode' : '' }}">
<div class="app-container">

    {{-- SIDEBAR COMPONENT --}}
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">

        {{-- TOPBAR COMPONENT --}}
        <x-topbar />

        <div class="rectifier-detail-content">
            
            {{-- HEADER ATAS: Back + Breadcrumb As Title + Badge + Tombol Edit --}}
            <div class="detail-header-bar">
                <div class="header-left-group">
                    <a href="{{ route('rectifiers.index', $pop->id) }}" class="detail-back" title="Kembali ke Daftar Rectifier" aria-label="Kembali ke Daftar Rectifier">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="header-title-wrapper">
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->kode_pop . ': Rectifier', 'route' => 'rectifiers.index', 'params' => ['pop' => $pop->id]],
                                ['label' => $rectifier->nomor_recti ?? ($rectifier->merk . ' - ' . $rectifier->type)],
                            ]" />
                            @if($rectifier->merk)
                                <span class="device-badge">{{ $rectifier->merk }}</span>
                            @endif
                        </div>
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
                    Terakhir diperbarui oleh:
                    <strong>
                        @if($rectifier->diupdateOleh)
                            {{ $rectifier->diupdateOleh->name }} - {{ $rectifier->updated_at->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            {{ $rectifier->updated_at ? $rectifier->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                        @endif
                    </strong>
                </span>
            </div>

            {{-- Card Information Rectifier: full-width, icon-box style --}}
            <div class="detail-card information-card">
                <div class="detail-card-title">
                    <i class="bi bi-info-circle-fill"></i> Information Rectifier
                </div>

                <div class="info-box-grid">
                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-upc-scan"></i></div>
                        <div class="info-box-text"><span class="info-box-label">Nomor Rectifier</span><span class="info-box-value">{{ $rectifier->nomor_recti ?? '-' }}</span></div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="info-box-text"><span class="info-box-label">POP</span><span class="info-box-value">{{ $pop->nama_pop_display }}</span></div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-calendar-event"></i></div>
                        <div class="info-box-text"><span class="info-box-label">Tanggal Pemasangan</span><span class="info-box-value">{{ $rectifier->tanggal_pemasangan?->translatedFormat('d F Y') ?? 'Belum diisi' }}{{ $rectifier->umur_perangkat ? ' · ' . $rectifier->umur_perangkat : '' }}</span></div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-calendar-check-fill"></i></div>
                        <div class="info-box-text"><span class="info-box-label">Tanggal Pemeriksaan</span><span class="info-box-value">{{ $rectifier->tanggal_pemeriksaan?->translatedFormat('d F Y') ?? 'Belum diisi' }}</span></div>
                    </div>
                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-person-fill"></i></div>
                        <div class="info-box-text"><span class="info-box-label">PIC</span><span class="info-box-value">{{ $rectifier->pic ?: '-' }}</span></div>
                    </div>

                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-tag-fill"></i></div>
                        <div class="info-box-text">
                            <span class="info-box-label">Type POP</span>
                            <span class="info-box-value">{{ $pop->tipe_pop ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-box-icon"><i class="bi bi-building-fill"></i></div>
                        <div class="info-box-text">
                            <span class="info-box-label">Building</span>
                            <span class="info-box-value">{{ $pop->jenis_bangunan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-bottom-grid">
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
                                <div>{{ $module->sn_modul ?? '-' }}</div>
                            </div>
                        @empty
                            <div style="text-align:center; color:#64748b; padding:16px; font-size: 0.8rem;">
                                Belum ada data module terpasang.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Card Photo Rectifier --}}
                <div class="detail-card photo-card">
                    <div class="photo-header">
                        <i class="bi bi-camera-fill"></i>
                        <span class="photo-title">Photo Rectifier</span>
                    </div>

                    <div class="photo-wrapper">
                        @if($rectifier->foto_rectifier && \Illuminate\Support\Facades\Storage::disk('public')->exists($rectifier->foto_rectifier))
                            <img src="{{ asset('storage/' . $rectifier->foto_rectifier) }}" alt="Photo Rectifier"
                                 style="width:100%; height:auto; max-height:280px; object-fit:contain; border-radius:8px; cursor:pointer;"
                                 onclick="openRectifierPhoto('{{ asset('storage/' . $rectifier->foto_rectifier) }}', 'Photo Rectifier')"
                                 onerror="this.style.display='none'; document.getElementById('photoPlaceholder').style.display='flex';">
                            <div id="photoPlaceholder" class="photo-placeholder" style="display:none;">
                                <i class="bi bi-image"></i>
                                <span>Foto Rectifier</span>
                            </div>
                        @else
                            <div id="photoPlaceholder" class="photo-placeholder">
                                <i class="bi bi-image"></i>
                                <span>Belum ada foto</span>
                            </div>
                        @endif
                    </div>
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
                        <div class="checklist-value">: {{ $pop->nama_pop_display }}</div>
                        <div class="checklist-label">Tanggal</div>
                        <div class="checklist-value">: {{ $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->translatedFormat('d F Y') : ($rectifier->updated_at ? $rectifier->updated_at->translatedFormat('d F Y') : '-') }}</div>
                    </div>

                    <div class="checklist-row">
                        <div class="checklist-label">PIC</div>
                        <div class="checklist-value">: {{ $rectifier->pic ?? ($pop->user->name ?? '-') }}</div>
                        <div class="checklist-label">Deskripsi</div>
                        <div class="checklist-value">: {{ $rectifier->deskripsi ?? '-' }}</div>
                    </div>
                </div>

                <div class="checklist-section-title">{{ $rectifier->nomor_recti ?? ($rectifier->merk . ' - ' . $rectifier->type) }}</div>

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

            {{-- Card Output (MCB) --}}
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
        </div>

    </main>
</div>

<script>
const serialToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2200,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

function copySerial() {
    const serial = document.getElementById('snRectifier').textContent.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(serial)
            .then(() => serialToast.fire({
                icon: 'success',
                title: 'Serial number disalin',
                text: serial,
            }))
            .catch(() => serialToast.fire({
                icon: 'error',
                title: 'Gagal menyalin otomatis',
                text: serial,
            }));
    } else {
        serialToast.fire({
            icon: 'info',
            title: 'Serial number',
            text: serial,
        });
    }
}

function openRectifierPhoto(src, title) {
    if (!src) return;
    Swal.fire({
        title: title || 'Photo Rectifier',
        imageUrl: src,
        imageAlt: title || 'Photo Rectifier',
        showCloseButton: true,
        showConfirmButton: false,
        width: 'min(92vw, 900px)',
        heightAuto: false,
    });
}
</script>
</body>
</html>
