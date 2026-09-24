{{-- POP HEADER + PAGE-LEVEL EXPORT --}}
<div class="pop-summary-header" id="pop-summary-page" style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
        <h2>{{ $pop->nama_pop }}</h2>
        <span>Kode: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }},
            {{ $pop->provinsi ?? 'Jambi' }}</span>
    </div>

    <div x-data="{ open: false }" class="export-menu-wrapper">
        <button @click="open = !open" @click.away="open = false" class="export-menu-btn" title="Export POP">
            <i class="bi bi-list"></i>
        </button>
        <div x-show="open" style="display:none;" class="export-dropdown">
            <button @click="printArea('pop-summary-page'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Summary</button>
            <div class="export-dropdown-divider"></div>
            <button @click="exportImage('pop-summary-page', 'png', 'POP_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
            <button @click="exportImage('pop-summary-page', 'jpeg', 'POP_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
            <button @click="exportPDF('pop-summary-page', 'POP_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
        </div>
    </div>
</div>


{{-- SECTION: RECTIFIER --}}
<div class="dashboard-module-section" id="section-rectifier">
    <div class="dashboard-module-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="dashboard-module-icon"><i class="bi bi-hdd-stack-fill"></i></div>
            <div>
                <h3>Rectifier <span class="dashboard-module-badge">Summary dari POP</span></h3>
                <p>Ringkasan kelengkapan data rectifier di semua POP</p>
            </div>
        </div>
        <div x-data="{ open: false }" class="export-menu-wrapper">
            <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
            <div x-show="open" style="display:none;" class="export-dropdown">
                <button @click="printArea('section-rectifier'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Section</button>
                <div class="export-dropdown-divider"></div>
                <button @click="exportImage('section-rectifier', 'png', 'Rectifier_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                <button @click="exportImage('section-rectifier', 'jpeg', 'Rectifier_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                <button @click="exportPDF('section-rectifier', 'Rectifier_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
            </div>
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
                            'Good' => '#22c55e', 'Warning' => '#eab308', 'Alert' => '#ef4444', default => '#cbd5e1',
                        };
                        $utilisasiTrack = match ($rectifier->status_utilisasi) {
                            'Good' => 'rgba(34,197,94,0.15)', 'Warning' => 'rgba(234,179,8,0.15)', 'Alert' => 'rgba(239,68,68,0.15)', default => 'rgba(203,213,225,0.3)',
                        };
                        $utilisasiClass = match ($rectifier->status_utilisasi) {
                            'Good' => 'perf-excellent', 'Warning' => 'perf-caution', 'Alert' => 'perf-alert', default => 'perf-none',
                        };
                        $cardId = 'rectifier-card-' . $rectifier->id;
                    @endphp

                    <div class="donut-device-card {{ $rectifier->status_utilisasi === 'Alert' ? 'card-alert-blink' : '' }}"
                         id="{{ $cardId }}" x-data="{ showTable: false }">

                        <div class="donut-device-header" style="justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="donut-device-icon"><i class="bi bi-hdd-stack"></i></div>
                                <span class="donut-device-number">{{ $rectifier->nama_alias ?? '-' }}</span>
                            </div>
                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                <div x-show="open" style="display:none;" class="export-dropdown">
                                    <button @click="exportImage('{{ $cardId }}', 'png', 'Rectifier_{{ $rectifier->nama_alias ?? $rectifier->id }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                    <button @click="exportImage('{{ $cardId }}', 'jpeg', 'Rectifier_{{ $rectifier->nama_alias ?? $rectifier->id }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                    <button @click="exportPDF('{{ $cardId }}', 'Rectifier_{{ $rectifier->nama_alias ?? $rectifier->id }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    <div class="export-dropdown-divider"></div>
                                    <button @click="showTable = !showTable; open = false"><i class="bi bi-table" style="margin-right:8px;"></i> <span x-text="showTable ? 'Sembunyikan Tabel' : 'Lihat Data Tabel'"></span></button>
                                </div>
                            </div>
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

                        {{-- Accordion Data Table --}}
                        <div x-show="showTable" style="display:none;" class="data-table-wrapper">
                            <span class="data-table-title"><i class="bi bi-table" style="margin-right:6px;"></i>Detail Data Rectifier</span>
                            <div class="data-table-grid">
                                <div class="dt-item"><span class="dt-label">Nomor Recti</span><span class="dt-value">{{ $rectifier->nama_alias ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Kapasitas</span><span class="dt-value">{{ $rectifier->kapasitas_rectifier ?? '-' }} A</span></div>
                                <div class="dt-item"><span class="dt-label">Beban</span><span class="dt-value">{{ $rectifier->beban ?? '-' }} A</span></div>
                                <div class="dt-item"><span class="dt-label">Sisa Kapasitas</span><span class="dt-value">{{ (is_numeric($rectifier->kapasitas_rectifier) && is_numeric($rectifier->beban)) ? ($rectifier->kapasitas_rectifier - $rectifier->beban) . ' A' : '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Status Utilisasi</span><span class="dt-value">{{ $rectifier->status_utilisasi ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Utilisasi</span><span class="dt-value">{{ round($utilisasiPersen) }}%</span></div>
                                <div class="dt-item"><span class="dt-label">Form Terisi</span><span class="dt-value">{{ $k['terisi'] }}/{{ $k['total'] }} field</span></div>
                                @if(!empty($k['belum_diisi']))
                                    <div class="dt-item" style="grid-column:span 2;"><span class="dt-label">Field Belum Terisi</span><span class="dt-value" style="color:#ef4444;">{{ implode(', ', $k['belum_diisi']) }}</span></div>
                                @endif
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

{{-- ─────────────────────────────────────────── --}}
{{-- SECTION: KWH                               --}}
{{-- ─────────────────────────────────────────── --}}
<div class="dashboard-module-section" id="section-kwh">
    <div class="dashboard-module-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="dashboard-module-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <h3>kWh <span class="dashboard-module-badge">Summary dari POP</span></h3>
                <p>Ringkasan kelengkapan data kWh di semua POP</p>
            </div>
        </div>
        <div x-data="{ open: false }" class="export-menu-wrapper">
            <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
            <div x-show="open" style="display:none;" class="export-dropdown">
                <button @click="printArea('section-kwh'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Section</button>
                <div class="export-dropdown-divider"></div>
                <button @click="exportImage('section-kwh', 'png', 'kWh_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                <button @click="exportImage('section-kwh', 'jpeg', 'kWh_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                <button @click="exportPDF('section-kwh', 'kWh_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
            </div>
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
                            $persenLengkap >= 80 => '#22c55e', $persenLengkap >= 50 => '#f59e0b', default => '#f87171',
                        };
                        $lengkapTrack = match (true) {
                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)', $persenLengkap >= 50 => 'rgba(245,158,11,0.15)', default => 'rgba(248,113,113,0.15)',
                        };
                        $utilisasiPersen = $kwh->persentase_utilisasi ?? 0;
                        $utilisasiColor = match ($kwh->status_utilisasi) {
                            'Good' => '#22c55e', 'Warning' => '#eab308', 'Alert' => '#ef4444', default => '#cbd5e1',
                        };
                        $utilisasiTrack = match ($kwh->status_utilisasi) {
                            'Good' => 'rgba(34,197,94,0.15)', 'Warning' => 'rgba(234,179,8,0.15)', 'Alert' => 'rgba(239,68,68,0.15)', default => 'rgba(203,213,225,0.3)',
                        };
                        $utilisasiClass = match ($kwh->status_utilisasi) {
                            'Good' => 'perf-excellent', 'Warning' => 'perf-caution', 'Alert' => 'perf-alert', default => 'perf-none',
                        };
                        $cardId = 'kwh-card-' . $kwh->id;
                    @endphp

                    <div class="donut-device-card {{ $kwh->status_utilisasi === 'Alert' ? 'card-alert-blink' : '' }}"
                         id="{{ $cardId }}" x-data="{ showTable: false }">

                        <div class="donut-device-header" style="justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="donut-device-icon"><i class="bi bi-lightning-charge"></i></div>
                                <span class="donut-device-number">{{ $kwh->building ?? '-' }}</span>
                            </div>
                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                <div x-show="open" style="display:none;" class="export-dropdown">
                                    <button @click="exportImage('{{ $cardId }}', 'png', 'kWh_{{ $kwh->building ?? $kwh->id }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                    <button @click="exportImage('{{ $cardId }}', 'jpeg', 'kWh_{{ $kwh->building ?? $kwh->id }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                    <button @click="exportPDF('{{ $cardId }}', 'kWh_{{ $kwh->building ?? $kwh->id }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    <div class="export-dropdown-divider"></div>
                                    <button @click="showTable = !showTable; open = false"><i class="bi bi-table" style="margin-right:8px;"></i> <span x-text="showTable ? 'Sembunyikan Tabel' : 'Lihat Data Tabel'"></span></button>
                                </div>
                            </div>
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

                        {{-- Accordion Data Table --}}
                        <div x-show="showTable" style="display:none;" class="data-table-wrapper">
                            <span class="data-table-title"><i class="bi bi-table" style="margin-right:6px;"></i>Detail Data kWh</span>
                            <div class="data-table-grid">
                                <div class="dt-item"><span class="dt-label">Building</span><span class="dt-value">{{ $kwh->building ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Daya Listrik (VA)</span><span class="dt-value">{{ $kwh->daya_ps_gi_formatted ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Total Daya Terpakai</span><span class="dt-value">{{ $kwh->total_daya_terpakai_formatted }}</span></div>
                                <div class="dt-item"><span class="dt-label">Persentase Utilisasi</span><span class="dt-value">{{ round($utilisasiPersen) }}%</span></div>
                                <div class="dt-item"><span class="dt-label">Status Utilisasi</span><span class="dt-value">{{ $kwh->status_utilisasi ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Form Terisi</span><span class="dt-value">{{ $k['terisi'] }}/{{ $k['total'] }} field</span></div>
                                @if(!empty($k['belum_diisi']))
                                    <div class="dt-item" style="grid-column:span 2;"><span class="dt-label">Field Belum Terisi</span><span class="dt-value" style="color:#ef4444;">{{ implode(', ', $k['belum_diisi']) }}</span></div>
                                @endif
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

{{-- ─────────────────────────────────────────── --}}
{{-- SECTION: BATTERY                           --}}
{{-- ─────────────────────────────────────────── --}}
<div class="dashboard-module-section" id="section-battery">
    <div class="dashboard-module-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="dashboard-module-icon"><i class="bi bi-battery-charging"></i></div>
            <div>
                <h3>Battery <span class="dashboard-module-badge">Summary dari POP</span></h3>
                <p>Ringkasan kelengkapan data battery di semua POP</p>
            </div>
        </div>
        <div x-data="{ open: false }" class="export-menu-wrapper">
            <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
            <div x-show="open" style="display:none;" class="export-dropdown">
                <button @click="printArea('section-battery'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Section</button>
                <div class="export-dropdown-divider"></div>
                <button @click="exportImage('section-battery', 'png', 'Battery_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                <button @click="exportImage('section-battery', 'jpeg', 'Battery_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                <button @click="exportPDF('section-battery', 'Battery_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
            </div>
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
                                <span class="rectifier-group-sn">{{ $group['banks']->first()->nomor_recti ?? '-' }}</span>
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
                                            $persenLengkap >= 80 => '#22c55e', $persenLengkap >= 50 => '#f59e0b', default => '#f87171',
                                        };
                                        $lengkapTrack = match (true) {
                                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)', $persenLengkap >= 50 => 'rgba(245,158,11,0.15)', default => 'rgba(248,113,113,0.15)',
                                        };
                                        $performaPersen = $battery->kapasitas_battery_persen ?? 0;
                                        $battCardId = 'battery-card-' . $battery->id;
                                    @endphp

                                    <div class="donut-device-card battery-bank-card {{ $battery->performa_baterai === '4-ALERT' ? 'card-alert-blink' : '' }}"
                                         id="{{ $battCardId }}" x-data="{ showTable: false }">

                                        <div class="battery-bank-header-stacked" style="justify-content:space-between; display:flex; align-items:center; flex-direction:row; margin-bottom:16px;">
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <div class="battery-bank-chip">
                                                    <i class="bi bi-battery-charging"></i> Nomor Bank :
                                                    {{ $battery->nomor_bank }}
                                                </div>
                                            </div>
                                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                                <div x-show="open" style="display:none;" class="export-dropdown">
                                                    
                                                    <button @click="exportImage('{{ $battCardId }}', 'png', 'Battery_Bank{{ $battery->nomor_bank }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                                    <button @click="exportImage('{{ $battCardId }}', 'jpeg', 'Battery_Bank{{ $battery->nomor_bank }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                                    <button @click="exportPDF('{{ $battCardId }}', 'Battery_Bank{{ $battery->nomor_bank }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                                    <div class="export-dropdown-divider"></div>
                                                    <button @click="showTable = !showTable; open = false"><i class="bi bi-table" style="margin-right:8px;"></i> <span x-text="showTable ? 'Sembunyikan Tabel' : 'Lihat Data Tabel'"></span></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="donut-row">
                                            <div class="donut-item-info-col">
                                                <div style="margin-bottom: 20px;">
                                                    <span class="uji-pill {{ $battery->status_uji_badge_class }}">{{ $battery->status_uji_label }}</span>
                                                </div>
                                                <div class="pm-info">
                                                    <i class="bi bi-calendar-event"></i>
                                                    <div>
                                                        <span class="pm-label" style="font-size:0.65rem;">Uji Terakhir</span>
                                                        <strong style="font-size:0.8rem; white-space:nowrap;">{{ $battery->tanggal_uji_terakhir?->translatedFormat('d F Y') ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="pm-info">
                                                    <i class="bi bi-calendar-check"></i>
                                                    <div>
                                                        <span class="pm-label" style="font-size:0.65rem;">Uji Berikutnya</span>
                                                        <strong style="font-size:0.8rem; white-space:nowrap;">{{ $battery->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</strong>
                                                        @if($battery->status_uji_text !== '-')
                                                            <div style="font-size:0.65rem; color:#64748b; margin-top:2px; line-height:1.2;">{{ $battery->status_uji_text }}</div>
                                                        @endif
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
                                                    <strong class="perf-text {{ $battery->performa_badge_class }}">{{ $battery->performa_label_bersih }}</strong>
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

                                        {{-- Accordion Data Table --}}
                                        <div x-show="showTable" style="display:none;" class="data-table-wrapper">
                                            <span class="data-table-title"><i class="bi bi-table" style="margin-right:6px;"></i>Detail Data Battery Bank {{ $battery->nomor_bank }}</span>
                                            <div class="data-table-grid">
                                                <div class="dt-item"><span class="dt-label">Nomor Bank</span><span class="dt-value">{{ $battery->nomor_bank ?? '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Jenis Battery</span><span class="dt-value">{{ $battery->jenis_battery ?? '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Kapasitas Battery (AH)</span><span class="dt-value">{{ $battery->kapasitas_battery ?? '-' }} Ah</span></div>
                                                <div class="dt-item"><span class="dt-label">Kapasitas Uji (AH)</span><span class="dt-value">{{ $battery->kapasitas_uji_formatted }}</span></div>
                                                @if(strtoupper($battery->jenis_battery) === 'VRLA')
                                                <div class="dt-item"><span class="dt-label">Kapasitas Uji 1 (AH)</span><span class="dt-value">{{ $battery->vrla_1 !== null ? $battery->vrla_1 . ' Ah' : '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Kapasitas Uji 2 (AH)</span><span class="dt-value">{{ $battery->vrla_2 !== null ? $battery->vrla_2 . ' Ah' : '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Kapasitas Uji 3 (AH)</span><span class="dt-value">{{ $battery->vrla_3 !== null ? $battery->vrla_3 . ' Ah' : '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Kapasitas Uji 4 (AH)</span><span class="dt-value">{{ $battery->vrla_4 !== null ? $battery->vrla_4 . ' Ah' : '-' }}</span></div>
                                                @endif
                                                <div class="dt-item"><span class="dt-label">Kapasitas Battery (%)</span><span class="dt-value">{{ $battery->kapasitas_battery_persen !== null ? round($battery->kapasitas_battery_persen) . '%' : '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Performa Battery</span><span class="dt-value">{{ $battery->performa_label_bersih }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Uji Terakhir</span><span class="dt-value">{{ $battery->tanggal_uji_terakhir?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Uji Berikutnya</span><span class="dt-value">{{ $battery->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Status Uji</span><span class="dt-value">{{ $battery->status_uji_label }}</span></div>
                                                <div class="dt-item"><span class="dt-label">Form Terisi</span><span class="dt-value">{{ $k['terisi'] }}/{{ $k['total'] }} field</span></div>
                                                @if(!empty($k['belum_diisi']))
                                                    <div class="dt-item" style="grid-column:span 2;"><span class="dt-label">Field Belum Terisi</span><span class="dt-value" style="color:#ef4444;">{{ implode(', ', $k['belum_diisi']) }}</span></div>
                                                @endif
                                            </div>
                                        </div>

                                        <a href="{{ route('batteries.show', [$pop->id, $battery->id]) }}" class="donut-detail-btn">
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

{{-- ─────────────────────────────────────────── --}}
{{-- SECTION: AC                                --}}
{{-- ─────────────────────────────────────────── --}}
<div class="dashboard-module-section" id="section-ac">
    <div class="dashboard-module-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="dashboard-module-icon"><i class="bi bi-fan"></i></div>
            <div>
                <h3>AC <span class="dashboard-module-badge">Summary dari POP</span></h3>
                <p>Ringkasan kelengkapan data AC di semua POP</p>
            </div>
        </div>
        <div x-data="{ open: false }" class="export-menu-wrapper">
            <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
            <div x-show="open" style="display:none;" class="export-dropdown">
                <button @click="printArea('section-ac'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Section</button>
                <div class="export-dropdown-divider"></div>
                <button @click="exportImage('section-ac', 'png', 'AC_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                <button @click="exportImage('section-ac', 'jpeg', 'AC_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                <button @click="exportPDF('section-ac', 'AC_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
            </div>
        </div>
    </div>

    @if ($acs->isEmpty())
        <div class="dashboard-device-empty">Belum ada data AC di POP ini.</div>
    @else
        <div class="dashboard-carousel-wrapper">
            <button type="button" class="dashboard-carousel-arrow arrow-left" id="acArrowLeft"
                onclick="scrollCarousel('acTrack', 'acDots', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="dashboard-carousel-track" id="acTrack">
                @foreach ($acs as $index => $ac)
                    @php
                        $k = $ac->kelengkapan_form;
                        $persenLengkap = $ac->persen_kelengkapan;
                        $lengkapColor = match (true) {
                            $persenLengkap >= 80 => '#22c55e', $persenLengkap >= 50 => '#f59e0b', default => '#f87171',
                        };
                        $lengkapTrack = match (true) {
                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)', $persenLengkap >= 50 => 'rgba(245,158,11,0.15)', default => 'rgba(248,113,113,0.15)',
                        };
                        $statusPm = $ac->status_pm;
                        $cardId = 'ac-card-' . $ac->id;
                    @endphp

                    <div class="donut-device-card {{ $statusPm['class'] === 'pm-badge-danger' ? 'card-alert-blink' : '' }}"
                         id="{{ $cardId }}" x-data="{ showTable: false }">

                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                            <span class="uji-pill {{ $statusPm['class'] }}" style="border-radius:4px; padding:4px 8px; border:1px solid currentColor;">
                                {{ $statusPm['status'] }}
                            </span>
                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                <div x-show="open" style="display:none;" class="export-dropdown">
                                    
                                    <button @click="exportImage('{{ $cardId }}', 'png', 'AC_{{ $ac->nomor_ac ?? $ac->id }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                    <button @click="exportImage('{{ $cardId }}', 'jpeg', 'AC_{{ $ac->nomor_ac ?? $ac->id }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                    <button @click="exportPDF('{{ $cardId }}', 'AC_{{ $ac->nomor_ac ?? $ac->id }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    <div class="export-dropdown-divider"></div>
                                    <button @click="showTable = !showTable; open = false"><i class="bi bi-table" style="margin-right:8px;"></i> <span x-text="showTable ? 'Sembunyikan Tabel' : 'Lihat Data Tabel'"></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="donut-row" style="align-items:flex-start; justify-content:space-between; gap:15px;">
                            <div class="donut-item-info-col" style="flex:1; align-items:flex-start;">
                                <div class="pm-info" style="margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                                    <i class="bi bi-calendar-event" style="font-size:1.4rem; color:#3b82f6; margin-top:-2px;"></i>
                                    <div>
                                        <span class="pm-label" style="display:block; font-size:0.75rem; color:#64748b; font-weight:500;">PM Terakhir</span>
                                        <strong style="font-size:0.95rem; color:#1e293b; white-space:nowrap;">{{ $ac->tanggal_terakhir_pm?->translatedFormat('d F Y') ?? '-' }}</strong>
                                    </div>
                                </div>
                                <div class="pm-info" style="display:flex; align-items:flex-start; gap:10px;">
                                    <i class="bi bi-calendar-check" style="font-size:1.4rem; color:#3b82f6; margin-top:-2px;"></i>
                                    <div>
                                        <span class="pm-label" style="display:block; font-size:0.75rem; color:#64748b; font-weight:500;">PM Berikutnya</span>
                                        <strong style="display:block; font-size:0.95rem; color:#1e293b; white-space:nowrap;">{{ $ac->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</strong>
                                        @if($statusPm['text'] !== '-')
                                            <span style="display:block; font-size:0.75rem; color:#64748b; margin-top:2px;">{{ $statusPm['text'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="donut-item" style="flex-shrink:0; min-height:unset; margin-top:-10px;">
                                <div class="donut-chart" title="Belum terisi: {{ empty($k['belum_diisi']) ? '-' : implode(', ', $k['belum_diisi']) }}"
                                    style="--donut-percent: {{ $persenLengkap }}; --donut-color: {{ $lengkapColor }}; --donut-track: {{ $lengkapTrack }};">
                                    <span class="donut-value">{{ round($persenLengkap) }}%</span>
                                </div>
                                <div class="donut-caption">
                                    Form Belum Terisi
                                    <strong style="display:block; font-size:0.85rem; color:#1e293b; margin-top:2px;">{{ $k['terisi'] }}/{{ $k['total'] }} Unit</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Accordion Data Table --}}
                        <div x-show="showTable" style="display:none;" class="data-table-wrapper">
                            <span class="data-table-title"><i class="bi bi-table" style="margin-right:6px;"></i>Detail Data AC #{{ $ac->nomor_ac ?? $ac->id }}</span>
                            <div class="data-table-grid">
                                <div class="dt-item"><span class="dt-label">Nomor AC</span><span class="dt-value">{{ $ac->nomor_ac ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Type AC</span><span class="dt-value">{{ $ac->type_ac ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">PK</span><span class="dt-value">{{ $ac->pk ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Tahun Manufaktur</span><span class="dt-value">{{ $ac->tahun_manufaktur ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">PM Terakhir</span><span class="dt-value">{{ $ac->tanggal_terakhir_pm?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">PM Berikutnya</span><span class="dt-value">{{ $ac->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                
                                <div class="dt-item"><span class="dt-label">Form Terisi</span><span class="dt-value">{{ $k['terisi'] }}/{{ $k['total'] }} field</span></div>
                                @if(!empty($k['belum_diisi']))
                                    <div class="dt-item" style="grid-column:span 2;"><span class="dt-label">Field Belum Terisi</span><span class="dt-value" style="color:#ef4444;">{{ implode(', ', $k['belum_diisi']) }}</span></div>
                                @endif
                                <div class="dt-item"><span class="dt-label">Status PM</span><span class="dt-value">{{ $statusPm['status'] }}</span></div>
                            </div>
                        </div>

                        <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="donut-detail-btn" style="margin-top:15px;">
                            Detail Form <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <button type="button" class="dashboard-carousel-arrow arrow-right" id="acArrowRight"
                onclick="scrollCarousel('acTrack', 'acDots', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="dashboard-carousel-dots" id="acDots"></div>
    @endif
</div>

{{-- ─────────────────────────────────────────── --}}
{{-- SECTION: GENSET                            --}}
{{-- ─────────────────────────────────────────── --}}
<div class="dashboard-module-section" id="section-genset">
    <div class="dashboard-module-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="dashboard-module-icon"><i class="bi bi-device-ssd"></i></div>
            <div>
                <h3>Genset <span class="dashboard-module-badge">Summary dari POP</span></h3>
                <p>Ringkasan kelengkapan data genset di semua POP</p>
            </div>
        </div>
        <div x-data="{ open: false }" class="export-menu-wrapper">
            <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
            <div x-show="open" style="display:none;" class="export-dropdown">
                <button @click="printArea('section-genset'); open = false"><i class="bi bi-printer" style="margin-right:8px;"></i> Print Section</button>
                <div class="export-dropdown-divider"></div>
                <button @click="exportImage('section-genset', 'png', 'Genset_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                <button @click="exportImage('section-genset', 'jpeg', 'Genset_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                <button @click="exportPDF('section-genset', 'Genset_{{ $pop->kode_pop }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
            </div>
        </div>
    </div>

    @if ($gensets->isEmpty())
        <div class="dashboard-device-empty">Belum ada data Genset di POP ini.</div>
    @else
        <div class="dashboard-carousel-wrapper">
            <button type="button" class="dashboard-carousel-arrow arrow-left" id="gensetArrowLeft"
                onclick="scrollCarousel('gensetTrack', 'gensetDots', -1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="dashboard-carousel-track" id="gensetTrack">
                @foreach ($gensets as $index => $genset)
                    @php
                        $k = $genset->kelengkapan_form;
                        $persenLengkap = $genset->persen_kelengkapan;
                        $lengkapColor = match (true) {
                            $persenLengkap >= 80 => '#22c55e', $persenLengkap >= 50 => '#f59e0b', default => '#f87171',
                        };
                        $lengkapTrack = match (true) {
                            $persenLengkap >= 80 => 'rgba(34,197,94,0.15)', $persenLengkap >= 50 => 'rgba(245,158,11,0.15)', default => 'rgba(248,113,113,0.15)',
                        };
                        $statusPm = $genset->status_pm;
                        $cardId = 'genset-card-' . $genset->id;
                    @endphp

                    <div class="donut-device-card {{ $statusPm['class'] === 'pm-badge-danger' ? 'card-alert-blink' : '' }}"
                         id="{{ $cardId }}" x-data="{ showTable: false }">

                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                            <span class="uji-pill {{ $statusPm['class'] }}" style="border-radius:4px; padding:4px 8px; border:1px solid currentColor;">
                                {{ $statusPm['status'] }}
                            </span>
                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                <div x-show="open" style="display:none;" class="export-dropdown">
                                    
                                    <button @click="exportImage('{{ $cardId }}', 'png', 'Genset_{{ $genset->nomor_genset ?? $genset->id }}'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                    <button @click="exportImage('{{ $cardId }}', 'jpeg', 'Genset_{{ $genset->nomor_genset ?? $genset->id }}'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                    <button @click="exportPDF('{{ $cardId }}', 'Genset_{{ $genset->nomor_genset ?? $genset->id }}'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    <div class="export-dropdown-divider"></div>
                                    <button @click="showTable = !showTable; open = false"><i class="bi bi-table" style="margin-right:8px;"></i> <span x-text="showTable ? 'Sembunyikan Tabel' : 'Lihat Data Tabel'"></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="donut-row" style="align-items:flex-start; justify-content:space-between; gap:15px;">
                            <div class="donut-item-info-col" style="flex:1; align-items:flex-start;">
                                <div class="pm-info" style="margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                                    <i class="bi bi-calendar-event" style="font-size:1.4rem; color:#3b82f6; margin-top:-2px;"></i>
                                    <div>
                                        <span class="pm-label" style="display:block; font-size:0.75rem; color:#64748b; font-weight:500;">PM Terakhir</span>
                                        <strong style="font-size:0.95rem; color:#1e293b; white-space:nowrap;">{{ $genset->tanggal_pm?->translatedFormat('d F Y') ?? '-' }}</strong>
                                    </div>
                                </div>
                                <div class="pm-info" style="display:flex; align-items:flex-start; gap:10px;">
                                    <i class="bi bi-calendar-check" style="font-size:1.4rem; color:#3b82f6; margin-top:-2px;"></i>
                                    <div>
                                        <span class="pm-label" style="display:block; font-size:0.75rem; color:#64748b; font-weight:500;">PM Berikutnya</span>
                                        <strong style="display:block; font-size:0.95rem; color:#1e293b; white-space:nowrap;">{{ $genset->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</strong>
                                        @if($statusPm['text'] !== '-')
                                            <span style="display:block; font-size:0.75rem; color:#64748b; margin-top:2px;">{{ $statusPm['text'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="donut-item" style="flex-shrink:0; min-height:unset; margin-top:-10px;">
                                <div class="donut-chart" title="Belum terisi: {{ empty($k['belum_diisi']) ? '-' : implode(', ', $k['belum_diisi']) }}"
                                    style="--donut-percent: {{ $persenLengkap }}; --donut-color: {{ $lengkapColor }}; --donut-track: {{ $lengkapTrack }};">
                                    <span class="donut-value">{{ round($persenLengkap) }}%</span>
                                </div>
                                <div class="donut-caption">
                                    Form Belum Terisi
                                    <strong style="display:block; font-size:0.85rem; color:#1e293b; margin-top:2px;">{{ $k['terisi'] }}/{{ $k['total'] }} Unit</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Accordion Data Table --}}
                        <div x-show="showTable" style="display:none;" class="data-table-wrapper">
                            <span class="data-table-title"><i class="bi bi-table" style="margin-right:6px;"></i>Detail Data Genset #{{ $genset->nomor_genset ?? $genset->id }}</span>
                            <div class="data-table-grid">
                                <div class="dt-item"><span class="dt-label">Nomor Genset</span><span class="dt-value">{{ $genset->nomor_genset ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Merk Genset</span><span class="dt-value">{{ $genset->merk_genset ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Kapasitas KVA</span><span class="dt-value">{{ $genset->kapasitas_kva ?? '-' }} KVA</span></div>
                                <div class="dt-item"><span class="dt-label">Tipe Engine</span><span class="dt-value">{{ $genset->tipe_engine ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Tahun Pasang</span><span class="dt-value">{{ $genset->tahun_pasang ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">PM Terakhir</span><span class="dt-value">{{ $genset->tanggal_pm?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">PM Berikutnya</span><span class="dt-value">{{ $genset->pm_berikutnya?->translatedFormat('d F Y') ?? '-' }}</span></div>
                                <div class="dt-item"><span class="dt-label">Form Terisi</span><span class="dt-value">{{ $k['terisi'] }}/{{ $k['total'] }} field</span></div>
                                @if(!empty($k['belum_diisi']))
                                <div class="dt-item" style="grid-column:span 2;"><span class="dt-label">Field Belum Terisi</span><span class="dt-value" style="color:#ef4444;">{{ implode(', ', $k['belum_diisi']) }}</span></div>
                                @endif
                                <div class="dt-item"><span class="dt-label">Status PM</span><span class="dt-value">{{ $statusPm['status'] }}</span></div>
                            </div>
                        </div>

                        <a href="{{ route('gensets.show', [$pop->id, $genset->id]) }}" class="donut-detail-btn" style="margin-top:15px;">
                            Detail Form <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <button type="button" class="dashboard-carousel-arrow arrow-right" id="gensetArrowRight"
                onclick="scrollCarousel('gensetTrack', 'gensetDots', 1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="dashboard-carousel-dots" id="gensetDots"></div>
    @endif
</div>