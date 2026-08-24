<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Rectifier - {{ $pop->nama_pop }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-card.css'
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

        <div class="rectifier-content">

            {{-- Header: Info POP + Tombol Tambah --}}
            <div class="rectifier-page-header">
                <div class="rectifier-page-info">
                    <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <span class="rectifier-pop-name">{{ $pop->nama_pop }}</span>
                        <span class="rectifier-pop-sub">{{ $pop->kota_kabupaten }}, {{ $pop->provinsi }} &mdash; {{ $rectifiers->count() }} Rectifier</span>
                    </div>
                </div>

                @can('rectifiers.index.create')
                    <a href="{{ route('rectifiers.create', $pop->id) }}" class="btn-tambah-rectifier">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </a>
                @endcan
            </div>

            {{-- Rectifier Grid --}}
            <div class="rectifier-grid">
                @forelse ($rectifiers as $rectifier)
                    <div class="rectifier-card">
                        {{-- Header --}}
                        <div class="rectifier-card-header">
                            <div class="checklist-icon">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>

                            <div class="checklist-title">
                                <h3>Checklist Rectifier</h3>
                                <p>{{ $rectifier->nama_alias ?? 'Data Rectifier' }}</p>
                            </div>

                            <span class="rectifier-number">
                                Rectifier #{{ $loop->iteration }}
                            </span>
                        </div>

                        {{-- Information --}}
                        <div class="rectifier-information">
                            <div class="equipment-info">{{ $rectifier->merk ?? '-' }}</div>
                            <div class="equipment-info">{{ $rectifier->type ?? '-' }}</div>
                            <div class="equipment-info serial">{{ $rectifier->sn_rectifier ?? '-' }}</div>
                        </div>

                        {{-- Meta: PIC & Tanggal Pemeriksaan --}}
                        <div class="rectifier-meta">
                            <div class="meta-item">
                                <i class="bi bi-person-fill"></i>
                                <span>{{ $rectifier->pic ?? '-' }}</span>
                            </div>

                            <div class="meta-item">
                                <i class="bi bi-calendar-fill"></i>
                                <span>{{ $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        {{-- Last Updated --}}
                        <div class="rectifier-last-updated">
                            <i class="bi bi-clock-history"></i>
                            <span>
                                @if($rectifier->diupdateOleh)
                                    {{ $rectifier->diupdateOleh->name }} &middot; {{ $rectifier->updated_at->format('d M Y, H:i') }}
                                @else
                                    Belum ada pembaruan
                                @endif
                            </span>
                        </div>

                        {{-- Footer --}}
                        <div class="rectifier-card-footer">
                            <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}" class="detail-button">
                                <span>Detail</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1; text-align:center; padding:40px; color:#64748b;">
                        <i class="bi bi-inbox" style="font-size:2rem; display:block; margin-bottom:12px;"></i>
                        Belum ada data Rectifier untuk POP ini.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
</body>

</html>

