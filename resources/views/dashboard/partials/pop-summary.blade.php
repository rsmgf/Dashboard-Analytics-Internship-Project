<div class="pop-summary-header">
    <h2>{{ $pop->nama_pop }}</h2>
    <span>Kode: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }},
        {{ $pop->provinsi ?? 'Jambi' }}</span>
</div>

{{-- SECTION: RECTIFIER --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-hdd-stack-fill"></i></div>
        <div>
            <h3>Rectifier <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Ringkasan kelengkapan data rectifier di POP ini</p>
        </div>
    </div>

    <div class="dashboard-module-cards">
        @forelse ($rectifiers as $rectifier)
            @php $k = $rectifier->kelengkapan_form; @endphp
            <div class="dashboard-device-card">
                <div class="dashboard-device-icon"><i class="bi bi-hdd-stack"></i></div>
                <div class="dashboard-device-info">
                    <strong>{{ $rectifier->nama_alias }}</strong>
                    <div class="dashboard-device-stats">
                        <div>
                            <span class="dashboard-stat-label">Status Utilisasi</span>
                            <span class="status-auto-badge {{ $rectifier->status_badge_class }}">
                                <i class="bi {{ $rectifier->status_icon }}"></i>
                                {{ $rectifier->status_utilisasi ?? 'Belum diisi' }}
                                @if ($rectifier->utilisasi !== null)
                                    ({{ $rectifier->utilisasi_formatted }})
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="dashboard-stat-label">Form Belum Terisi</span>
                            <span>{{ $k['terisi'] }}/{{ $k['total'] }} Unit</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}" class="btn-tambah-rectifier"
                    style="padding:6px 14px; font-size:0.75rem;">
                    Lihat Detail
                </a>
            </div>
        @empty
            <div class="dashboard-device-empty">Belum ada data Rectifier di POP ini.</div>
        @endforelse
    </div>
</div>

{{-- SECTION: KWH --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <div>
            <h3>kWh <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Ringkasan kelengkapan data kWh di POP ini</p>
        </div>
    </div>

    <div class="dashboard-module-cards">
        @forelse ($kwhs as $kwh)
            @php $k = $kwh->kelengkapan_form; @endphp
            <div class="dashboard-device-card">
                <div class="dashboard-device-icon"><i class="bi bi-lightning-charge"></i></div>
                <div class="dashboard-device-info">
                    <strong>{{ $kwh->building }}</strong>
                    <div class="dashboard-device-stats">
                        <div>
                            <span class="dashboard-stat-label">Status Utilisasi</span>
                            <span class="status-auto-badge {{ $kwh->status_badge_class }}">
                                <i class="bi {{ $kwh->status_icon }}"></i> {{ $kwh->status_utilisasi }}
                                ({{ $kwh->persentase_utilisasi_formatted }})
                            </span>
                        </div>
                        <div>
                            <span class="dashboard-stat-label">Form Belum Terisi</span>
                            <span>{{ $k['terisi'] }}/{{ $k['total'] }} Unit</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('kwh.detail', [$pop->id, $kwh->id]) }}" class="btn-tambah-rectifier"
                    style="padding:6px 14px; font-size:0.75rem;">
                    Lihat Detail
                </a>
            </div>
        @empty
            <div class="dashboard-device-empty">Belum ada data kWh di POP ini.</div>
        @endforelse
    </div>
</div>

{{-- SECTION: BATTERY (placeholder) --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-battery-full"></i></div>
        <div>
            <h3>Battery <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Modul ini belum tersedia</p>
        </div>
    </div>
    <div class="dashboard-device-empty">
        <i class="bi bi-hourglass-split"></i> Segera Hadir
    </div>
</div>

{{-- SECTION: AC (placeholder) --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-fan"></i></div>
        <div>
            <h3>AC <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Modul ini belum tersedia</p>
        </div>
    </div>
    <div class="dashboard-device-empty">
        <i class="bi bi-hourglass-split"></i> Segera Hadir
    </div>
</div>
