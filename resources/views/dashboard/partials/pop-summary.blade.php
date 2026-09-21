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
            <p>Ringkasan kelengkapan data rectifier di semua POP</p>
        </div>
    </div>

    @if ($rectifiers->isEmpty())
        <div class="dashboard-device-empty">Belum ada data Rectifier di POP ini.</div>
    @else
        <div class="dashboard-carousel-wrapper">
            <button type="button" class="dashboard-carousel-arrow arrow-left" id="rectifierArrowLeft"
                onclick="scrollCarousel('rectifierTrack', 'rectifierDots', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="dashboard-carousel-track" id="rectifierTrack">
                @foreach ($rectifiers as $index => $rectifier)
                    @php
                        $k = $rectifier->kelengkapan_form;
                        $persenLengkap = $rectifier->persen_kelengkapan;

                        $lengkapColor = match (true) {
                            $persenLengkap >= 80 => '#22c55e',
                            $persenLengkap >= 50 => '#f59e0b',
                            default => '#f87171',
                        };
                        $lengkapTrack = match (true) {
                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)',
                            $persenLengkap >= 50 => 'rgba(245,158,11,0.15)',
                            default => 'rgba(248,113,113,0.15)',
                        };

                        $utilisasiPersen = $rectifier->utilisasi ?? 0;

                        $utilisasiColor = match ($rectifier->status_utilisasi) {
                            'Good' => '#22c55e',
                            'Warning' => '#eab308',
                            'Alert' => '#ef4444',
                            default => '#cbd5e1',
                        };
                        $utilisasiTrack = match ($rectifier->status_utilisasi) {
                            'Good' => 'rgba(34,197,94,0.15)',
                            'Warning' => 'rgba(234,179,8,0.15)',
                            'Alert' => 'rgba(239,68,68,0.15)',
                            default => 'rgba(203,213,225,0.3)',
                        };
                        $utilisasiClass = match ($rectifier->status_utilisasi) {
                            'Good' => 'perf-excellent',
                            'Warning' => 'perf-caution',
                            'Alert' => 'perf-alert',
                            default => 'perf-none',
                        };
                    @endphp

                    <div class="donut-device-card {{ $rectifier->status_utilisasi === 'Alert' ? 'card-alert-blink' : '' }}">
                        <div class="donut-device-header">
                            <div class="donut-device-icon"><i class="bi bi-hdd-stack"></i></div>
                            <span class="donut-device-number">{{ $rectifier->nama_alias ?? '-' }}</span>
                        </div>

                        <div class="donut-row">
                            <div class="donut-item">
                                <div class="donut-chart" title="Utilitas saat ini: {{ round($utilisasiPersen) }}%"
                                    style="--donut-percent: {{ $utilisasiPersen }}; --donut-color: {{ $utilisasiColor }}; --donut-track: {{ $utilisasiTrack }};">
                                    <span class="donut-value">{{ round($utilisasiPersen) }}%</span>
                                </div>
                                <div class="donut-caption">
                                    Utilitas Rectifier
                                    <strong class="perf-text {{ $utilisasiClass }}">{{ strtoupper($rectifier->status_utilisasi ?? '-') }}</strong>
                                </div>
                            </div>

                            <div class="donut-item">
                                <div class="donut-chart" title="Belum terisi: {{ empty($k['belum_diisi']) ? '-' : implode(', ', $k['belum_diisi']) }}"
                                    style="--donut-percent: {{ $persenLengkap }}; --donut-color: {{ $lengkapColor }}; --donut-track: {{ $lengkapTrack }};">
                                    <span class="donut-value">{{ round($persenLengkap) }}%</span>
                                </div>
                                <div class="donut-caption">
                                    Form Belum Terisi
                                    <strong>{{ $k['terisi'] }}/{{ $k['total'] }} Unit</strong>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}" class="donut-detail-btn">
                            Detail Form <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <button type="button" class="dashboard-carousel-arrow arrow-right" id="rectifierArrowRight"
                onclick="scrollCarousel('rectifierTrack', 'rectifierDots', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="dashboard-carousel-dots" id="rectifierDots"></div>
    @endif
</div>

{{-- SECTION: KWH --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <div>
            <h3>kWh <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Ringkasan kelengkapan data kWh di semua POP</p>
        </div>
    </div>

    @if ($kwhs->isEmpty())
        <div class="dashboard-device-empty">Belum ada data kWh di POP ini.</div>
    @else
        <div class="dashboard-carousel-wrapper">
            <button type="button" class="dashboard-carousel-arrow arrow-left" id="kwhArrowLeft"
                onclick="scrollCarousel('kwhTrack', 'kwhDots', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="dashboard-carousel-track" id="kwhTrack">
                @foreach ($kwhs as $index => $kwh)
                    @php
                        $k = $kwh->kelengkapan_form;
                        $persenLengkap = $kwh->persen_kelengkapan;

                        $lengkapColor = match (true) {
                            $persenLengkap >= 80 => '#22c55e',
                            $persenLengkap >= 50 => '#f59e0b',
                            default => '#f87171',
                        };
                        $lengkapTrack = match (true) {
                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)',
                            $persenLengkap >= 50 => 'rgba(245,158,11,0.15)',
                            default => 'rgba(248,113,113,0.15)',
                        };

                        $utilisasiPersen = $kwh->persentase_utilisasi ?? 0;

                        $utilisasiColor = match ($kwh->status_utilisasi) {
                            'Good' => '#22c55e',
                            'Warning' => '#eab308',
                            'Alert' => '#ef4444',
                            default => '#cbd5e1',
                        };
                        $utilisasiTrack = match ($kwh->status_utilisasi) {
                            'Good' => 'rgba(34,197,94,0.15)',
                            'Warning' => 'rgba(234,179,8,0.15)',
                            'Alert' => 'rgba(239,68,68,0.15)',
                            default => 'rgba(203,213,225,0.3)',
                        };
                        $utilisasiClass = match ($kwh->status_utilisasi) {
                            'Good' => 'perf-excellent',
                            'Warning' => 'perf-caution',
                            'Alert' => 'perf-alert',
                            default => 'perf-none',
                        };
                    @endphp

                    <div class="donut-device-card {{ $kwh->status_utilisasi === 'Alert' ? 'card-alert-blink' : '' }}">
                        <div class="donut-device-header">
                            <div class="donut-device-icon"><i class="bi bi-lightning-charge"></i></div>
                            <span class="donut-device-number">{{ $kwh->building ?? '-' }}</span>
                        </div>

                        <div class="donut-row">
                            <div class="donut-item donut-item-with-info">
                                <div class="donut-side-info">
                                    <div class="donut-side-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                                    <div class="donut-side-text">
                                        <span class="donut-side-label">Total Daya Terpakai</span>
                                        <span class="donut-side-value">{{ $kwh->total_daya_terpakai_formatted }}</span>
                                    </div>
                                </div>

                                <div class="donut-chart-group">
                                    <div class="donut-chart" title="Utilitas saat ini: {{ round($utilisasiPersen) }}%"
                                        style="--donut-percent: {{ $utilisasiPersen }}; --donut-color: {{ $utilisasiColor }}; --donut-track: {{ $utilisasiTrack }};">
                                        <span class="donut-value">{{ round($utilisasiPersen) }}%</span>
                                    </div>
                                    <div class="donut-caption">
                                        Utilitas kWh
                                        <strong class="perf-text {{ $utilisasiClass }}">{{ strtoupper($kwh->status_utilisasi ?? '-') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="donut-item">
                                <div class="donut-chart" title="Belum terisi: {{ empty($k['belum_diisi']) ? '-' : implode(', ', $k['belum_diisi']) }}"
                                    style="--donut-percent: {{ $persenLengkap }}; --donut-color: {{ $lengkapColor }}; --donut-track: {{ $lengkapTrack }};">
                                    <span class="donut-value">{{ round($persenLengkap) }}%</span>
                                </div>
                                <div class="donut-caption">
                                    Form Belum Terisi
                                    <strong>{{ $k['terisi'] }}/{{ $k['total'] }} Unit</strong>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('kwh.detail', [$pop->id, $kwh->id]) }}" class="donut-detail-btn">
                            Detail Form <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <button type="button" class="dashboard-carousel-arrow arrow-right" id="kwhArrowRight"
                onclick="scrollCarousel('kwhTrack', 'kwhDots', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="dashboard-carousel-dots" id="kwhDots"></div>
    @endif
</div>

{{-- SECTION: BATTERY --}}
<div class="dashboard-module-section">
    <div class="dashboard-module-header">
        <div class="dashboard-module-icon"><i class="bi bi-hdd-stack-fill"></i></div>
        <div>
            <h3>Battery <span class="dashboard-module-badge">Summary dari POP</span></h3>
            <p>Ringkasan kelengkapan data battery di semua POP</p>
        </div>
    </div>


    @if ($batteryGroups->isEmpty())
        <div class="dashboard-device-empty">Belum ada data Battery di POP ini.</div>
    @else
        <div class="dashboard-carousel-wrapper">
            <button type="button" class="dashboard-carousel-arrow arrow-left" id="batteryOuterArrowLeft"
                onclick="scrollCarousel('batteryOuterTrack', 'batteryOuterDots', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="dashboard-carousel-track" id="batteryOuterTrack">
                @foreach ($batteryGroups as $groupIndex => $group)
                    <div class="rectifier-group-slide">
                        <div class="rectifier-group-line">
                            <div class="rectifier-group-line-left">
                                <span class="dashboard-module-badge">Rectifier #{{ $groupIndex + 1 }}</span>
                                <span
                                    class="rectifier-group-sn">{{ $group['banks']->first()->nomor_recti ?? '-' }}</span>
                            </div>
                            <div class="rectifier-group-line-right">
                                <div class="backup-time-badge {{ $group['performa_class'] }}">
                                    Performance Backup Time :
                                    {{ $group['backup_time'] !== null ? $group['backup_time'] . ' Jam' : '-' }}
                                    @if($group['performa_label'])
                                        <strong>({{ $group['performa_label'] }})</strong>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-carousel-wrapper battery-inner-wrapper {{ $group['performa_class'] === 'status-danger' ? 'card-alert-blink' : '' }}">
                            @if ($group['banks']->count() > 2)
                                <button type="button" class="dashboard-carousel-arrow arrow-left small"
                                    id="batteryInner{{ $groupIndex }}ArrowLeft"
                                    onclick="scrollCarousel('batteryInner{{ $groupIndex }}Track', null, -1)">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            @endif

                            <div class="dashboard-carousel-track battery-inner-track"
                                id="batteryInner{{ $groupIndex }}Track">
                                @foreach ($group['banks'] as $battery)
                                    @php
                                        $k = $battery->kelengkapan_form;
                                        $persenLengkap = $battery->persen_kelengkapan;
                                        $lengkapColor = match (true) {
                                            $persenLengkap >= 80 => '#22c55e',
                                            $persenLengkap >= 50 => '#f59e0b',
                                            default => '#f87171',
                                        };
                                        $lengkapTrack = match (true) {
                                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)',
                                            $persenLengkap >= 50 => 'rgba(245,158,11,0.15)',
                                            default => 'rgba(248,113,113,0.15)',
                                        };
                                        $performaPersen = $battery->kapasitas_battery_persen ?? 0;
                                    @endphp

                                    <div class="donut-device-card battery-bank-card {{ $battery->performa_baterai === '4-ALERT' ? 'card-alert-blink' : '' }}">
                                        <div class="battery-bank-header-stacked">
                                            <div class="battery-bank-chip">
                                                <i class="bi bi-battery-charging"></i> Nomor Bank :
                                                {{ $battery->nomor_bank }}
                                            </div>
                                            <span
                                                class="uji-pill {{ $battery->status_uji_badge_class }}">{{ $battery->status_uji_label }}</span>
                                        </div>

                                        <div class="donut-row">
                                            <div class="donut-item-info-col">
                                                <div class="pm-info">
                                                    <i class="bi bi-calendar-event"></i>
                                                    <div>
                                                        <span class="pm-label">PM Terakhir</span>
                                                        <strong>{{ $battery->tanggal_uji_terakhir?->translatedFormat('d F Y') ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="pm-info">
                                                    <i class="bi bi-calendar-check"></i>
                                                    <div>
                                                        <span class="pm-label">PM Berikutnya</span>
                                                        <strong>{{ $battery->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="donut-item">
                                                <div class="donut-chart" title="Kapasitas Baterai: {{ round($performaPersen) }}%"
                                                    style="--donut-percent: {{ $performaPersen }}; --donut-color: {{ $battery->performa_color }}; --donut-track: {{ $battery->performa_track }};">
                                                    <span class="donut-value">{{ round($performaPersen) }}%</span>
                                                </div>
                                                <div class="donut-caption">
                                                    Kapasitas Baterai
                                                    <strong
                                                        class="perf-text {{ $battery->performa_badge_class }}">{{ $battery->performa_label_bersih }}</strong>
                                                </div>
                                            </div>

                                            <div class="donut-item">
                                                <div class="donut-chart" title="Belum terisi: {{ empty($k['belum_diisi']) ? '-' : implode(', ', $k['belum_diisi']) }}"
                                                    style="--donut-percent: {{ $persenLengkap }}; --donut-color: {{ $lengkapColor }}; --donut-track: {{ $lengkapTrack }};">
                                                    <span class="donut-value">{{ round($persenLengkap) }}%</span>
                                                </div>
                                                <div class="donut-caption">
                                                    Form Belum Terisi
                                                    <strong>{{ $k['terisi'] }}/{{ $k['total'] }} Unit</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('batteries.show', [$pop->id, $battery->id]) }}"
                                            class="donut-detail-btn">
                                            Detail Form <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            @if ($group['banks']->count() > 2)
                                <button type="button" class="dashboard-carousel-arrow arrow-right small"
                                    id="batteryInner{{ $groupIndex }}ArrowRight"
                                    onclick="scrollCarousel('batteryInner{{ $groupIndex }}Track', null, 1)">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="dashboard-carousel-arrow arrow-right" id="batteryOuterArrowRight"
                onclick="scrollCarousel('batteryOuterTrack', 'batteryOuterDots', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="dashboard-carousel-dots" id="batteryOuterDots"></div>
    @endif
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
