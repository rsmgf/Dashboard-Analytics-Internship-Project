<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Eksekutif - PLN Icon Plus</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    {{-- Chart.js CDN & DataLabels Plugin CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css', 'resources/css/dashboard-pop.css', 'resources/css/card.css', 'resources/js/app.js'])

    <style>
        /* Style Tambahan untuk Unread Dot pada Notifikasi */
        .notif-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .unread-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background-color: #ef4444;
            border: 2px solid #ffffff;
            border-radius: 50%;
            display: inline-block;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
</head>

<body class="{{ (session('active_role') ?? (auth()->user()?->hasRole('manajer') ? 'manajer' : 'super_admin')) === 'manajer' ? 'manajer-mode' : '' }}">
    @if(!$canEkspor)
    <style>.export-menu-wrapper { display: none !important; }</style>
    @endif
    <div class="app-container">
        {{-- SIDEBAR --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            {{-- TOPBAR --}}
            <x-topbar />

            <div class="dashboard-content">

                {{-- WELCOME & SEARCH --}}
                <div class="dashboard-welcome">
                    <div class="dashboard-welcome-text">
                        <h1>Selamat datang, {{ auth()->user()->name ?? 'Executive' }}</h1>
                        <p>Monitoring kondisi peralatan seluruh POP Provinsi Jambi secara real-time</p>
                    </div>

                    <div class="dashboard-welcome-actions">
                        <div class="dashboard-search-wrapper">
                            <div class="dashboard-search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="searchPopInput" placeholder="Cari POP (nama / kode)"
                                    autocomplete="off" aria-label="Cari POP">
                            </div>
                            <div id="searchSuggestions" class="dashboard-search-suggestions"></div>
                        </div>

                        @if ($canFilter)
                            <button type="button" id="btnToggleFilter" class="dashboard-filter-btn" title="Filter POP"
                                aria-expanded="false" aria-controls="filterPanel">
                                <i class="bi bi-sliders"></i>
                                <span class="filter-btn-label">Filter</span>
                            </button>
                        @endif
                    </div>
                </div>

                @if ($canFilter)
                    <div id="filterPanel" class="dashboard-filter-panel">
                        <div class="dashboard-filter-row">
                            <div class="dashboard-filter-field">
                                <label for="filterKota">Kota/Kabupaten</label>
                                <select id="filterKota">
                                    <option value="">Semua</option>
                                </select>
                            </div>

                            <div class="dashboard-filter-field">
                                <label for="filterStatus">Status Utilisasi</label>
                                <select id="filterStatus">
                                    <option value="">Semua</option>
                                    <option value="Good">Good</option>
                                    <option value="Warning">Warning</option>
                                    <option value="Alert">Alert</option>
                                </select>
                            </div>

                            <div class="dashboard-filter-field">
                                <label for="filterKelengkapan">Kelengkapan Form</label>
                                <select id="filterKelengkapan">
                                    <option value="">Semua</option>
                                    <option value="lengkap">Lengkap</option>
                                    <option value="belum_lengkap">Belum Lengkap</option>
                                </select>
                            </div>

                            <div class="dashboard-filter-buttons">
                                <button type="button" id="btnResetFilter" class="dashboard-filter-reset">Reset</button>
                                <button type="button" id="btnTerapkanFilter"
                                    class="dashboard-filter-apply">Terapkan</button>
                            </div>
                        </div>

                        {{-- hasil filter dirender di sini --}}
                        <div id="filterResult" class="dashboard-filter-result"></div>
                    </div>
                @endif

                <div id="popBackBar" class="pop-back-bar">
                    <button type="button" id="btnBackToOverview" class="pop-back-btn">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </button>
                </div>

                {{-- HASIL POP SUMMARY (dipindah ke sini, di luar overview) --}}
                <div id="popSummaryResult"></div>

                {{-- SEMUA BAGIAN DI BAWAH INI HILANG SAAT MODE FOKUS --}}
                <div id="dashboardOverview">

                    {{-- 4 KARTU KPI UTAMA --}}
                    <div class="dashboard-kpi-grid">
                        <div class="dashboard-kpi-card">
                            <div class="kpi-icon kpi-blue"><i class="bi bi-hdd-network-fill"></i></div>
                            <div class="kpi-info">
                                <span>Total POP</span>
                                <strong>{{ number_format($totalPop) }}</strong>
                                <small>Terdaftar</small>
                            </div>
                        </div>

                        <div class="dashboard-kpi-card">
                            <div class="kpi-icon kpi-red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div class="kpi-info">
                                <span>POP Alert</span>
                                <strong class="text-danger">{{ number_format($totalPopAlert) }}</strong>
                                <small>{{ $totalPop ? round(($totalPopAlert / $totalPop) * 100) : 0 }}% dari
                                    total</small>
                            </div>
                        </div>

                        <div class="dashboard-kpi-card">
                            <div class="kpi-icon kpi-yellow"><i class="bi bi-exclamation-circle-fill"></i></div>
                            <div class="kpi-info">
                                <span>POP Warning</span>
                                <strong class="text-warning">{{ number_format($totalPopWarning) }}</strong>
                                <small>{{ $totalPop ? round(($totalPopWarning / $totalPop) * 100) : 0 }}% dari
                                    total</small>
                            </div>
                        </div>

                        <div class="dashboard-kpi-card">
                            <div class="kpi-icon kpi-purple"><i class="bi bi-person-fill-exclamation"></i></div>
                            <div class="kpi-info">
                                <span>Menunggu Approval</span>
                                <strong class="text-purple">{{ number_format($totalPendingApproval) }}</strong>
                                <small>Pengguna</small>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS PERANGKAT POP --}}
                    <div class="device-status-section">
                        <div class="section-title-dashboard" style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                            <div>
                                <h2>Status Perangkat POP</h2>
                                <p>Pilih status pada perangkat untuk melihat daftar POP berdasarkan kondisi
                                    operasionalnya
                                </p>
                            </div>
                            <div x-data="{ open: false }" class="export-menu-wrapper">
                                <button @click="open = !open" @click.away="open = false" class="export-menu-btn" title="Export Status Perangkat"><i class="bi bi-list"></i></button>
                                <div x-show="open" style="display:none;" class="export-dropdown">
                                    <button @click="exportImage('deviceStatusGrid', 'png', 'Status_Perangkat_POP'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                    <button @click="exportImage('deviceStatusGrid', 'jpeg', 'Status_Perangkat_POP'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                    <button @click="exportPDF('deviceStatusGrid', 'Status_Perangkat_POP'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                </div>
                            </div>
                        </div>
                        <div id="deviceStatusGrid" class="device-status-split-grid"></div>
                    </div>

                    {{-- LIST CARD POP BERDASARKAN STATUS TERPILIH --}}
                    <div id="statusListContainer" class="status-list-container" style="display: none;">
                        <div class="status-list-header">
                            <div>
                                <h3 id="statusListTitle">Daftar POP</h3>
                                <p id="statusListSubtitle">Menampilkan seluruh POP dengan status terkait</p>
                            </div>
                            <button type="button" class="btn-close-list" onclick="closeStatusList()">
                                <i class="bi bi-x-lg"></i> Tutup
                            </button>
                        </div>
                        <div id="statusListCardsGrid" class="status-list-cards-grid"></div>
                    </div>

                    {{-- ANALYTICS ROW 1: HEALTHY & NON-HEALTHY INDEX --}}
                    <div class="dashboard-analytics-row">
                        <div class="analytics-card">
                            <div class="analytics-card-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="analytics-title">
                                    <i class="bi bi-hand-thumbs-up-fill"></i>
                                    <h3>TOP 10 - Healthy Index POP Provinsi Jambi</h3>
                                </div>
                                <div x-data="{ open: false }" class="export-menu-wrapper">
                                    <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                    <div x-show="open" style="display:none;" class="export-dropdown">
                                        <button @click="exportImage('healthyIndexChart', 'png', 'Top_10_Healthy_POP'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                        <button @click="exportImage('healthyIndexChart', 'jpeg', 'Top_10_Healthy_POP'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                        <button @click="exportPDF('healthyIndexChart', 'Top_10_Healthy_POP'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container-stacked">
                                <canvas id="healthyIndexChart"></canvas>
                            </div>
                        </div>

                        <div class="analytics-card">
                            <div class="analytics-card-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="analytics-title">
                                    <i class="bi bi-hand-thumbs-down-fill text-danger"></i>
                                    <h3>TOP 10 - Non Healthy Index POP Provinsi Jambi</h3>
                                </div>
                                <div x-data="{ open: false }" class="export-menu-wrapper">
                                    <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                    <div x-show="open" style="display:none;" class="export-dropdown">
                                        <button @click="exportImage('nonHealthyIndexChart', 'png', 'Top_10_NonHealthy_POP'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                        <button @click="exportImage('nonHealthyIndexChart', 'jpeg', 'Top_10_NonHealthy_POP'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                        <button @click="exportPDF('nonHealthyIndexChart', 'Top_10_NonHealthy_POP'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container-stacked">
                                <canvas id="nonHealthyIndexChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- ANALYTICS ROW 2: POPULASI POP & NOTIFIKASI TERBARU --}}
                    <div class="dashboard-analytics-row">
                        {{-- KARTU POPULASI POP --}}
                        <div class="analytics-card">
                            <div class="analytics-card-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="analytics-title">
                                    <i class="bi bi-pie-chart-fill"></i>
                                    <h3>Populasi POP Menurut Tipe POP</h3>
                                </div>
                                <div x-data="{ open: false }" class="export-menu-wrapper">
                                    <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                    <div x-show="open" style="display:none;" class="export-dropdown">
                                        <button @click="exportImage('populationBody', 'png', 'Populasi_POP'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                        <button @click="exportImage('populationBody', 'jpeg', 'Populasi_POP'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                        <button @click="exportPDF('populationBody', 'Populasi_POP'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    </div>
                                </div>
                            </div>

                            <div id="populationBody" class="population-body">
                                <div class="population-chart">
                                    <canvas id="populasiChart"></canvas>
                                </div>
                                <ul id="populasiLegend" class="population-legend"></ul>
                            </div>
                        </div>

                        {{-- KARTU NOTIFIKASI TERBARU (Hanya untuk Manajer) --}}
                        @if(auth()->user()->hasRole('manajer'))
                        <div class="analytics-card">
                            <div class="analytics-card-header notif-header-flex">
                                <div class="analytics-title">
                                    <i class="bi bi-bell-fill"></i>
                                    <h3>Notifikasi Terbaru</h3>
                                </div>
                                <a href="{{ url('/notifications') }}" class="link-selengkapnya"
                                    title="Lihat Semua Notifikasi">
                                    Selengkapnya <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                            <div class="notification-list">
                                @forelse($latestStatusNotifications as $notif)
                                <div class="notification-item {{ $notif->is_read ? 'read' : '' }}" id="notif-{{ $notif->id }}"
                                    onclick="markNotifAsRead('notif-{{ $notif->id }}', {{ $notif->id }}, '{{ $notif->category }}', '{{ $notif->pop_id }}', '{{ $notif->device_type }}', '{{ $notif->device_id }}')">
                                    <div class="notif-icon-wrapper">
                                        <div class="notif-icon {{ $notif->icon_bg_class }}">
                                            <i class="{{ $notif->icon_class }}"></i>
                                        </div>
                                        @if(!$notif->is_read)
                                        <span class="unread-dot"></span>
                                        @endif
                                    </div>
                                    <div class="notif-content">
                                        <strong>{{ $notif->title }}</strong>
                                        <p>{{ $notif->message }}</p>
                                    </div>
                                    <div class="notif-time">
                                        <span>{{ $notif->created_at->format('d M Y H:i') }}</span>
                                        <i class="bi bi-chevron-right"></i>
                                    </div>
                                </div>
                                @empty
                                <div style="text-align:center; padding: 20px; color: #64748b; font-size: 13px;">
                                    Belum ada notifikasi
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- PETA PROVINSI JAMBI --}}
                    <div class="dashboard-map-section">
                        <div class="dashboard-section-header">
                            <div class="dashboard-section-title">
                                <div class="dashboard-section-icon">
                                    <i class="bi bi-map-fill"></i>
                                </div>
                                <div>
                                    <h2>Peta Persebaran POP di Provinsi Jambi</h2>
                                    <p>Sebaran lokasi seluruh POP berdasarkan jenis perangkat dan status operasional</p>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap: 12px;">
                                <div class="map-filter">
                                    <select id="mapStatusFilter">
                                        <option value="all">Semua Status</option>
                                        <option value="good">Healthy</option>
                                        <option value="warning">UnHealthy</option>
                                    </select>
                                </div>
                                <div x-data="{ open: false }" class="export-menu-wrapper">
                                    <button @click="open = !open" @click.away="open = false" class="export-menu-btn"><i class="bi bi-list"></i></button>
                                    <div x-show="open" style="display:none;" class="export-dropdown">
                                        <button @click="exportImage('jambiMap', 'png', 'Peta_Persebaran_POP'); open = false"><i class="bi bi-image" style="margin-right:8px;"></i> Download PNG</button>
                                        <button @click="exportImage('jambiMap', 'jpeg', 'Peta_Persebaran_POP'); open = false"><i class="bi bi-image-fill" style="margin-right:8px;"></i> Download JPEG</button>
                                        <button @click="exportPDF('jambiMap', 'Peta_Persebaran_POP'); open = false"><i class="bi bi-file-pdf" style="margin-right:8px;"></i> Download PDF</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jambi-map-container">
                            <div id="jambiMap" class="jambi-map"></div>
                            <div class="map-legend">
                                <span><i class="legend-dot good"></i> Healthy</span>
                                <span><i class="legend-dot warning"></i> UnHealthy</span>
                            </div>
                        </div>
                    </div>

                </div>
        </main>
    </div>

    {{-- Leaflet JS CDN --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* =========================================================
                                                                                                                       0. STATE GLOBAL
                                                                                                                       ========================================================= */
        const carouselState = {};
        let jambiMap = null;
        let markers = [];

        /* MODE FOKUS: sembunyikan KPI s/d peta saat lihat detail POP */
        function enterPopFocus() {
            document.body.classList.add('pop-focus');
        }

        function exitPopFocus() {
            document.body.classList.remove('pop-focus');
            document.getElementById('popSummaryResult').innerHTML = '';
            const fr = document.getElementById('filterResult');
            if (fr) fr.innerHTML = '';
            searchInput.value = '';
            suggestionsBox.classList.remove('show');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // chart & peta perlu dihitung ulang ukurannya setelah tampil lagi
            requestAnimationFrame(() => {
                if (jambiMap) jambiMap.invalidateSize();
                window.dispatchEvent(new Event('resize'));
            });
        }

        document.getElementById('btnBackToOverview')
            .addEventListener('click', exitPopFocus);

        /* =========================================================
           1. NOTIFIKASI
           ========================================================= */
        function markNotifAsRead(elementId, notifId, category = '', popId = null, deviceType = null, deviceId = null) {
            const item = document.getElementById(elementId);
            
            const processNavigation = () => {
                if (popId) {
                    if (category === 'aktivitas' && deviceType !== 'pop' && deviceId) {
                        let detailPath = '';
                        if (deviceType === 'rectifier') detailPath = `rectifiers/${deviceId}`;
                        else if (deviceType === 'kwh') detailPath = `kwh/${deviceId}`;
                        else if (deviceType === 'battery') detailPath = `batteries/${deviceId}`;
                        else if (deviceType === 'genset') detailPath = `gensets/${deviceId}`;
                        else if (deviceType === 'ac') detailPath = `ac/${deviceId}`;
                        
                        if (detailPath) {
                            window.location.href = `{{ route('pops.index', [], false) }}/${popId}/${detailPath}`;
                            return;
                        }
                    }
                    viewPopDetail(popId, deviceType, deviceId);
                }
            };

            if (!item) {
                processNavigation();
                return;
            }

            const dot = item.querySelector('.unread-dot');
            if (dot) {
                // AJAX call to mark as read
                fetch(`/notifications/${notifId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        dot.style.opacity = '0';
                        dot.style.transform = 'scale(0)';
                        setTimeout(() => dot.remove(), 300);
                        item.classList.add('read');
                    }
                    processNavigation();
                }).catch(err => {
                    console.error(err);
                    processNavigation();
                });
            } else {
                processNavigation();
            }
        }

        /* =========================================================
           2. SEARCH POP
           ========================================================= */
        const searchPopUrl = "{{ route('dashboard.searchPop') }}";
        const popSummaryUrlTemplate = "{{ route('dashboard.popSummary', ['pop' => '__ID__']) }}";

        const searchInput = document.getElementById('searchPopInput');
        const suggestionsBox = document.getElementById('searchSuggestions');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const q = this.value.trim();

            if (q.length < 2) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.remove('show');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`${searchPopUrl}?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => renderSuggestions(data))
                    .catch(() => {});
            }, 300);
        });

        // tutup suggestion saat klik di luar (sebelumnya nyasar di dalam initCarousel)
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dashboard-search-wrapper')) {
                suggestionsBox.classList.remove('show');
            }
        });

        function renderSuggestions(pops) {
            if (!pops || pops.length === 0) {
                suggestionsBox.innerHTML = `<div class="dashboard-suggestion-empty">Tidak ada POP yang cocok</div>`;
                suggestionsBox.classList.add('show');
                return;
            }

            suggestionsBox.innerHTML = pops.map(pop => `
                <div class="dashboard-suggestion-item" data-id="${pop.id}">
                    <i class="bi bi-geo-alt-fill"></i>
                    <div>
                        <strong>${pop.nama_pop}</strong>
                        <span>${pop.kode_pop} &middot; ${pop.kota_kabupaten}</span>
                    </div>
                </div>
            `).join('');

            suggestionsBox.classList.add('show');

            suggestionsBox.querySelectorAll('.dashboard-suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    viewPopDetail(this.dataset.id);
                    suggestionsBox.classList.remove('show');
                    const titleNode = this.querySelector('strong');
                    if (titleNode) searchInput.value = titleNode.textContent;
                });
            });
        }

        /* =========================================================
           3. FILTER PANEL
           ========================================================= */
        const filterOptionsUrl = "{{ route('dashboard.filterOptions') }}";
        const filterPopUrl = "{{ route('dashboard.filterPop') }}";
        const btnToggleFilter = document.getElementById('btnToggleFilter');
        const filterPanel = document.getElementById('filterPanel');
        let kotaLoaded = false;

        function loadKotaOptions() {
            if (kotaLoaded) return;
            const sel = document.getElementById('filterKota');

            fetch(filterOptionsUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    (data.kota || []).forEach(k => sel.add(new Option(k, k)));
                    kotaLoaded = true;
                })
                .catch(err => {
                    console.error('Gagal memuat kota/kabupaten:', err);
                    sel.add(new Option('Gagal memuat data', ''));
                });
        }

        if (btnToggleFilter && filterPanel) {
            // dimuat sekali saat halaman siap, bukan menunggu panel dibuka
            loadKotaOptions();

            btnToggleFilter.addEventListener('click', () => {
                const open = filterPanel.classList.toggle('show');
                btnToggleFilter.setAttribute('aria-expanded', open);
            });

            document.getElementById('btnTerapkanFilter').addEventListener('click', () => {
                enterPopFocus();
                document.getElementById('popSummaryResult').innerHTML = '';

                const params = new URLSearchParams({
                    kota_kabupaten: document.getElementById('filterKota').value,
                    status_utilisasi: document.getElementById('filterStatus').value,
                    kelengkapan: document.getElementById('filterKelengkapan').value,
                });
                const box = document.getElementById('filterResult');
                box.innerHTML =
                    '<div class="dashboard-loading"><i class="bi bi-arrow-repeat spin"></i> Memuat...</div>';

                fetch(`${filterPopUrl}?${params}`)
                    .then(r => {
                        if (!r.ok) throw new Error(r.status);
                        return r.text();
                    })
                    .then(html => box.innerHTML = html)
                    .catch(() => box.innerHTML = '<div class="dashboard-hint">Gagal memuat hasil filter.</div>');
            });

            document.getElementById('btnResetFilter').addEventListener('click', () => {
                ['filterKota', 'filterStatus', 'filterKelengkapan'].forEach(id => document.getElementById(id)
                    .value = '');
                exitPopFocus();
            });

            document.getElementById('filterResult').addEventListener('click', e => {
                const item = e.target.closest('.dashboard-filter-pop-item');
                if (item) viewPopDetail(item.dataset.popId);
            });
        }

        /* =========================================================
           4. CAROUSEL (dipakai oleh partial pop-summary)
           ========================================================= */
        function getCardStep(track, cardSelector) {
            const first = track.querySelector(cardSelector);
            if (!first) return 0;
            const gap = parseFloat(getComputedStyle(track).columnGap) || 20;
            return first.offsetWidth + gap;
        }

        function initCarousel(trackId, dotsId, cardSelector = '.donut-device-card', visibleCount = 2) {
            const track = document.getElementById(trackId);
            if (!track) return;

            const cards = track.querySelectorAll(cardSelector);
            if (cards.length === 0) return;

            // 1 card per layar di tablet & mobile
            if (visibleCount === 2 && window.innerWidth <= 1024) visibleCount = 1;

            const totalPositions = Math.max(1, cards.length - visibleCount + 1);

            carouselState[trackId] = {
                page: 0,
                totalPages: totalPositions,
                cardWidth: getCardStep(track, cardSelector),
                cardSelector,
                dotsId,
                lockUntil: 0
            };

            // pakai container dots yang ada; kalau id diberikan tapi belum ada, dibuat otomatis
            let dotsContainer = dotsId ? document.getElementById(dotsId) : null;
            if (dotsId && !dotsContainer) {
                dotsContainer = document.createElement('div');
                dotsContainer.id = dotsId;
                dotsContainer.className = 'dashboard-carousel-dots';
                track.insertAdjacentElement('afterend', dotsContainer);
            }

            if (dotsContainer) {
                dotsContainer.innerHTML = '';
                if (totalPositions > 1) {
                    for (let i = 0; i < totalPositions; i++) {
                        const dot = document.createElement('div');
                        dot.className = 'dashboard-carousel-dot' + (i === 0 ? ' active' : '');
                        dot.addEventListener('click', () => goToPage(trackId, dotsId, i));
                        dotsContainer.appendChild(dot);
                    }
                }
            }

            // sinkronkan dots saat user swipe (abaikan event scroll dari animasi klik)
            if (!track.dataset.swipeBound) {
                track.dataset.swipeBound = '1';
                let ticking = false;
                track.addEventListener('scroll', function() {
                    if (ticking) return;
                    ticking = true;
                    requestAnimationFrame(() => {
                        const s = carouselState[trackId];
                        if (s && Date.now() > s.lockUntil && s.cardWidth) {
                            const p = Math.round(track.scrollLeft / s.cardWidth);
                            s.page = Math.max(0, Math.min(p, s.totalPages - 1));
                            updateDots(trackId, s.dotsId);
                            updateArrows(trackId);
                        }
                        ticking = false;
                    });
                }, {
                    passive: true
                });
            }

            updateArrows(trackId);
        }

        function goToPage(trackId, dotsId, page) {
            const track = document.getElementById(trackId);
            const state = carouselState[trackId];
            if (!track || !state) return;

            // hitung ulang lebar card (aman setelah resize / rotate layar)
            state.cardWidth = getCardStep(track, state.cardSelector) || state.cardWidth;

            page = Math.max(0, Math.min(page, state.totalPages - 1));
            state.page = page;
            state.lockUntil = Date.now() + 700; // biarkan animasi selesai tanpa ditimpa

            track.scrollTo({
                left: page * state.cardWidth,
                behavior: 'smooth'
            });
            updateDots(trackId, state.dotsId || dotsId);
            updateArrows(trackId);
        }

        function updateDots(trackId, dotsId) {
            const state = carouselState[trackId];
            if (!state) return;
            const container = document.getElementById(dotsId || state.dotsId);
            if (!container) return;
            container.querySelectorAll('.dashboard-carousel-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === state.page);
            });
        }

        function findArrows(trackId) {
            // 1) konvensi id: batteryOuterTrack -> batteryOuterArrowLeft / batteryOuterArrowRight
            const base = trackId.replace(/Track$/, '');
            let left = document.getElementById(base + 'ArrowLeft');
            let right = document.getElementById(base + 'ArrowRight');
            // 2) fallback: arrow yang jadi anak langsung dari wrapper track
            if (!left || !right) {
                const wrapper = document.getElementById(trackId)?.parentElement;
                if (wrapper) {
                    left = left || wrapper.querySelector(':scope > .dashboard-carousel-arrow.arrow-left');
                    right = right || wrapper.querySelector(':scope > .dashboard-carousel-arrow.arrow-right');
                }
            }
            return {
                left,
                right
            };
        }

        function updateArrows(trackId) {
            const state = carouselState[trackId];
            if (!state) return;
            const {
                left,
                right
            } = findArrows(trackId);
            if (left) left.classList.toggle('hidden', state.page <= 0);
            if (right) right.classList.toggle('hidden', state.page >= state.totalPages - 1);
        }

        // klik arrow (hanya untuk arrow yang belum punya onclick sendiri di partial)
        document.addEventListener('click', function(e) {
            const arrow = e.target.closest('.dashboard-carousel-arrow');
            if (!arrow || arrow.getAttribute('onclick')) return;

            const wrapper = arrow.parentElement;
            const track = wrapper.querySelector(
                ':scope > .dashboard-carousel-track, :scope > .battery-inner-track');
            if (!track || !carouselState[track.id]) return;

            const dir = arrow.classList.contains('arrow-left') ? -1 : 1;
            goToPage(track.id, null, carouselState[track.id].page + dir);
        });

        // hitung ulang saat layar di-resize / rotate
        window.addEventListener('resize', () => {
            Object.keys(carouselState).forEach(id => {
                const track = document.getElementById(id);
                const s = carouselState[id];
                if (!track || !s) return;
                s.cardWidth = getCardStep(track, s.cardSelector) || s.cardWidth;
            });
        });

        const deviceStatusUrl = "{{ route('dashboard.deviceStatus') }}";

        const defaultStatuses = [{
                key: 'good',
                label: 'Good'
            },
            {
                key: 'warning',
                label: 'Warning'
            },
            {
                key: 'alert',
                label: 'Alert'
            }
<<<<<<< HEAD
=======
        },
        kwh: {
            title: 'KWH',
            subtitle: 'Perangkat KWH',
            icon: 'bi-lightning-charge-fill',
            hasDeviceNumber: true,
            statuses: {
                good: {
                    label: 'Good',
                    items: [
                        { id: 'POP_PAYOSELINCAH_01', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_1MRB001_01', popName: 'POP Muara Bulian 01', location: 'Batanghari' },
                        { id: 'POP_1JMB10007_01', popName: 'POP Telanaipura', location: 'Kota Jambi' },
                        { id: 'POP_1MRT10000_01', popName: 'POP Muara Tembesi', location: 'Batanghari' },
                        { id: 'POP_1MBN002_01', popName: 'POP Muara Bungo 02', location: 'Bungo' },
                        { id: 'POP_1SRL001_01', popName: 'POP Sarolangun 01', location: 'Sarolangun' },
                        { id: 'POP_1SRL002_01', popName: 'POP Sarolangun 02', location: 'Sarolangun' },
                        { id: 'POP_1JMB002_01', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                warning: {
                    label: 'Warning',
                    items: [
                        { id: 'POP_1MRB10000_01', popName: 'POP Bangko', location: 'Merangin' },
                        { id: 'POP_1JMB002_02', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                alert: {
                    label: 'Alert',
                    items: [
                        { id: 'POP_1SRL002_01', popName: 'POP Sarolangun 02', location: 'Sarolangun' }
                    ]
                }
            }
        },
        battery: {
            title: 'Battery',
            subtitle: 'Perangkat Battery',
            icon: 'bi-battery-full',
            hasDeviceNumber: true,
            statuses: {
                good: {
                    label: 'Good',
                    items: [
                        { id: 'POP_PAYOSELINCAH_01', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_PAYOSELINCAH_02', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_1MRB001_01', popName: 'POP Muara Bulian', location: 'Batanghari' },
                        { id: 'POP_1JMB10007_01', popName: 'POP Telanaipura', location: 'Kota Jambi' },
                        { id: 'POP_1MRT10000_01', popName: 'POP Muara Tembesi', location: 'Batanghari' },
                        { id: 'POP_1MBN002_01', popName: 'POP Muara Bungo', location: 'Bungo' },
                        { id: 'POP_1SRL001_02', popName: 'POP Sarolangun', location: 'Sarolangun' },
                        { id: 'POP_1JMB002_01', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                warning: {
                    label: 'Warning',
                    items: [
                        { id: 'POP_1MBN002_02', popName: 'POP Muara Bungo 02', location: 'Bungo' },
                        { id: 'POP_1SRL002_02', popName: 'POP Sarolangun 02', location: 'Sarolangun' }
                    ]
                },
                alert: {
                    label: 'Alert',
                    items: [
                        { id: 'POP_1SRL001_01', popName: 'POP Sarolangun 01', location: 'Sarolangun' }
                    ]
                }
            }
        },
        ac: {
            title: 'Air Conditioner',
            subtitle: 'Status AC per POP',
            icon: 'bi-fan',
            hasDeviceNumber: false,
            statuses: {
                good: {
                    label: 'Good',
                    items: [
                        { id: 'POP_PAYOSELINCAH', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_1MRB001', popName: 'POP Muara Bulian', location: 'Batanghari' },
                        { id: 'POP_1JMB10007', popName: 'POP Telanaipura', location: 'Kota Jambi' },
                        { id: 'POP_1MRT10000', popName: 'POP Muara Tembesi', location: 'Batanghari' },
                        { id: 'POP_1MBN002', popName: 'POP Muara Bungo 02', location: 'Bungo' },
                        { id: 'POP_1SRL001', popName: 'POP Sarolangun 01', location: 'Sarolangun' },
                        { id: 'POP_1SRL002', popName: 'POP Sarolangun 02', location: 'Sarolangun' },
                        { id: 'POP_1JMB002', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                warning: {
                    label: 'Warning',
                    items: [
                        { id: 'POP_1MBN002', popName: 'POP Muara Bungo 02', location: 'Bungo' },
                        { id: 'POP_1MRT10000', popName: 'POP Muara Tembesi', location: 'Batanghari' }
                    ]
                },
                alert: {
                    label: 'Alert',
                    items: [
                        { id: 'POP_1SRL001', popName: 'POP Sarolangun 01', location: 'Sarolangun' }
                    ]
                }
            }
        },
        genset: {
            title: 'Genset',
            subtitle: 'Status Genset per POP',
            icon: 'bi-fuel-pump-fill',
            hasDeviceNumber: false,
            statuses: {
                good: {
                    label: 'Good',
                    items: [
                        { id: 'POP_PAYOSELINCAH', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_1MRB001', popName: 'POP Muara Bulian', location: 'Batanghari' },
                        { id: 'POP_1JMB10007', popName: 'POP Telanaipura', location: 'Kota Jambi' },
                        { id: 'POP_1MRT10000', popName: 'POP Muara Tembesi', location: 'Batanghari' },
                        { id: 'POP_1MBN002', popName: 'POP Muara Bungo', location: 'Bungo' },
                        { id: 'POP_1SRL001', popName: 'POP Sarolangun', location: 'Sarolangun' },
                        { id: 'POP_1SRL002', popName: 'POP Sarolangun 02', location: 'Sarolangun' },
                        { id: 'POP_1JMB002', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                warning: {
                    label: 'Warning',
                    items: [
                        { id: 'POP_1MBN002', popName: 'POP Muara Bungo', location: 'Bungo' },
                        { id: 'POP_1MRT10000', popName: 'POP Muara Tembesi', location: 'Batanghari' }
                    ]
                },
                alert: {
                    label: 'Alert',
                    items: [
                        { id: 'POP_1SRL001', popName: 'POP Sarolangun', location: 'Sarolangun' }
                    ]
                }
            }
        }
    };

    function renderDeviceCards() {
        const container = document.getElementById('deviceStatusGrid');
        if (!container) return;

        const topRowKeys = ['rectifier', 'kwh', 'battery'];
        const bottomRowKeys = ['ac', 'genset'];

        function createCardHTML(deviceKey) {
            const device = deviceStatusData[deviceKey];
            const goodCount = device.statuses.good?.items.length || 0;
            const warnCount = device.statuses.warning?.items.length || 0;
            const alertCount = device.statuses.alert?.items.length || 0;
            const totalCount = goodCount + warnCount + alertCount;

            const goodPct = totalCount ? (goodCount / totalCount) * 100 : 0;
            const warnPct = totalCount ? (warnCount / totalCount) * 100 : 0;
            const alertPct = totalCount ? (alertCount / totalCount) * 100 : 0;

            const unitLabel = device.hasDeviceNumber ? 'unit terpasang' : 'lokasi POP';

            return `
                <div class="device-status-card">
                    <div class="device-card-header">
                        <div class="device-title">
                            <div class="device-icon ${deviceKey}">
                                <i class="bi ${device.icon}"></i>
                            </div>
                            <div>
                                <h3>${device.title}</h3>
                                <span>${device.subtitle}</span>
                            </div>
                        </div>
                        <div class="device-total-box">
                            <span class="device-total-num">${totalCount}</span>
                            <small>${unitLabel}</small>
                        </div>
                    </div>

                    <div class="device-mini-summary">
                        <div class="mini-progress-bar" title="Good: ${goodCount}, Warning: ${warnCount}, Alert: ${alertCount}">
                            <div class="progress-segment good" style="width: ${goodPct}%"></div>
                            <div class="progress-segment warning" style="width: ${warnPct}%"></div>
                            <div class="progress-segment alert" style="width: ${alertPct}%"></div>
                        </div>
                        <div class="mini-progress-labels">
                            <span class="lbl-good"><i class="bi bi-circle-fill"></i> ${Math.round(goodPct)}% Good</span>
                            <span class="lbl-warn"><i class="bi bi-circle-fill"></i> ${Math.round(warnPct)}% Warning</span>
                            <span class="lbl-alert"><i class="bi bi-circle-fill"></i> ${Math.round(alertPct)}% Alert</span>
                        </div>
                    </div>

                    <div class="device-status-list">
                        ${Object.entries(device.statuses).map(([statusKey, status]) => `
                            <button type="button" class="status-dropdown-button" onclick="openStatusPage('${deviceKey}', '${statusKey}')">
                                <span class="status-left">
                                    <span class="status-dot ${statusKey}"></span>
                                    <span>${status.label}</span>
                                </span>
                                <div class="status-right">
                                    <span class="status-count">${status.items.length}</span>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        container.innerHTML = `
            <div class="device-status-row top-row">
                ${topRowKeys.map(key => createCardHTML(key)).join('')}
            </div>
            <div class="device-status-row bottom-row">
                ${bottomRowKeys.map(key => createCardHTML(key)).join('')}
            </div>
        `;
    }

    function openStatusPage(deviceKey, statusKey) {
        const device = deviceStatusData[deviceKey];
        const status = device.statuses[statusKey];
        const container = document.getElementById('statusListContainer');
        const grid = document.getElementById('statusListCardsGrid');

        document.getElementById('statusListTitle').textContent = `Daftar POP - ${device.title} (${status.label})`;
        document.getElementById('statusListSubtitle').textContent = `Menampilkan ${status.items.length} POP dengan status kondisi ${status.label}`;

        grid.innerHTML = status.items.map(item => `
            <div class="pop-status-box">
                <div class="pop-status-box-header">
                    <div>
                        <h4>${item.id}</h4>
                        <small>${item.popName} &bull; ${item.location}</small>
                    </div>
                    <span class="status-pill ${statusKey}">${status.label}</span>
                </div>
                <div class="pop-status-box-body">
                    <div class="pop-meta-row">
                        <span>Perangkat:</span>
                        <strong>${device.title}</strong>
                    </div>
                    <div class="pop-meta-row">
                        <span>Tipe Kode:</span>
                        <strong>${device.hasDeviceNumber ? 'Unit Terpasang' : 'POP Utama'}</strong>
                    </div>
                </div>
                <div class="pop-status-box-footer">
                    <button type="button" class="btn-detail-pop" onclick="viewPopDetail('${item.id}')">
                        <i class="bi bi-eye"></i> Detail
                    </button>
                </div>
            </div>
        `).join('');

        container.style.display = 'block';
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function closeStatusList() {
        document.getElementById('statusListContainer').style.display = 'none';
    }

    function viewPopDetail(popId) {
        const cleanPopId = popId.replace(/_\d+$/, '');
        const resultBox = document.getElementById('popSummaryResult');
        resultBox.innerHTML = `
            <div class="dashboard-loading">
                <i class="bi bi-arrow-repeat spin"></i> Memuat detail kelengkapan POP ${cleanPopId}...
            </div>
        `;
        resultBox.scrollIntoView({ behavior: 'smooth', block: 'start' });

        fetch(popSummaryUrlTemplate.replace('__ID__', encodeURIComponent(cleanPopId)))
            .then(res => res.text())
            .then(html => {
                resultBox.innerHTML = html;
            })
            .catch(() => {
                resultBox.innerHTML = `<div class="dashboard-hint">Gagal memuat data POP: ${cleanPopId}</div>`;
            });
    }

    function initAnalyticsCharts() {
        const popLabels = [
            'POP PAYO SELINCAH', 'POP TELANAIPURA', 'POP BULIAN 01', 'POP BUNGO 01',
            'POP SIPIN', 'POP TEMBESI', 'POP SAROLANGUN', 'POP BANGKO', 'POP TUNGKAL', 'POP KERINCI'
>>>>>>> 6e0e5c5457c7e0131e065c7f1c2e1ef379cf7015
        ];

        const deviceMeta = {
            rectifier: {
                title: 'Rectifier',
                icon: 'bi-hdd-stack-fill',
                unit: 'perangkat'
            },
            kwh: {
                title: 'kWh',
                icon: 'bi-lightning-charge-fill',
                unit: 'perangkat'
            },
            battery: {
                title: 'Battery',
                icon: 'bi-battery-full',
                unit: 'grup rectifier',
                statuses: [{
                        key: 'excellent',
                        label: 'Excellent'
                    },
                    {
                        key: 'enough',
                        label: 'Good Enough'
                    },
                    {
                        key: 'warn',
                        label: 'Warning'
                    },
                    {
                        key: 'alert',
                        label: 'Alert'
                    }
                ]
            },
            ac: {
                title: 'Air Conditioner',
                icon: 'bi-fan',
                unit: 'unit',
                statuses: [
                    { key: 'sudah_pm', label: 'Sudah PM' },
                    { key: 'jadwal_pm', label: 'Jadwal PM' },
                    { key: 'belum_pm', label: 'Belum PM' }
                ]
            },
            genset: {
                title: 'Genset',
                icon: 'bi-lightning',
                unit: 'unit',
                statuses: [
                    { key: 'sudah_pm', label: 'Sudah PM' },
                    { key: 'jadwal_pm', label: 'Jadwal PM' },
                    { key: 'belum_pm', label: 'Belum PM' }
                ]
            }
        };

        function statusLabel(deviceKey, statusKey) {
            const list = deviceMeta[deviceKey].statuses || defaultStatuses;
            return (list.find(s => s.key === statusKey) || {}).label || statusKey;
        }

        let deviceStatusData = {};
        let activeStatus = null; // { device, status }

        function escapeHtml(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
        }

        function loadDeviceStatus() {
            const container = document.getElementById('deviceStatusGrid');
            if (!container) return;

            container.innerHTML =
                '<div class="dashboard-loading"><i class="bi bi-arrow-repeat spin"></i> Memuat status perangkat...</div>';

            fetch(deviceStatusUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    deviceStatusData = data;
                    renderDeviceCards();
                })
                .catch(err => {
                    console.error('Gagal memuat status perangkat:', err);
                    container.innerHTML = '<div class="dashboard-hint">Gagal memuat status perangkat.</div>';
                });
        }

        /* =========================================================
           6. RENDER KARTU STATUS PERANGKAT
           ========================================================= */
        function renderDeviceCards() {
            const container = document.getElementById('deviceStatusGrid');
            if (!container) return;

            container.innerHTML = Object.keys(deviceMeta).map(key => {
                const meta = deviceMeta[key];
                const statuses = meta.statuses || defaultStatuses;
                const st = deviceStatusData[key] || {};

                const counts = {};
                statuses.forEach(s => counts[s.key] = (st[s.key] || []).length);
                const total = Object.values(counts).reduce((a, b) => a + b, 0);
                const pct = k => total ? (counts[k] / total) * 100 : 0;

                return `
        <div class="device-status-card">
            <div class="device-card-header">
                <div class="device-title">
                    <div class="device-icon ${key}"><i class="bi ${meta.icon}"></i></div>
                    <h3>${meta.title}</h3>
                </div>
                <div class="device-total-box">
                    <span class="device-total-num">${total}</span>
                    <small>${meta.unit}</small>
                </div>
            </div>

            <div class="device-mini-summary">
                <div class="mini-progress-bar">
                    ${statuses.map(s => `<div class="progress-segment ${s.key}" style="width:${pct(s.key)}%"></div>`).join('')}
                </div>
                <div class="mini-progress-labels">
                    ${statuses.map(s => `
                                                <span class="lbl-${s.key}" title="${s.label}"><i class="bi bi-circle-fill"></i>${Math.round(pct(s.key))}%</span>
                                            `).join('')}
                </div>
            </div>

            <div class="device-status-list">
                ${statuses.map(s => `
                                            <button type="button" class="status-dropdown-button"
                                                    data-device="${key}" data-status="${s.key}" ${counts[s.key] === 0 ? 'disabled' : ''}>
                                                <span class="status-left">
                                                    <span class="status-dot ${s.key}"></span>${s.label}
                                                </span>
                                                <span class="status-right">
                                                    <span class="status-count">${counts[s.key]}</span>
                                                    <i class="bi bi-chevron-right"></i>
                                                </span>
                                            </button>
                                        `).join('')}
            </div>
        </div>`;
            }).join('');

            if (activeStatus) markActiveStatus();
        }

        function markActiveStatus() {
            document.querySelectorAll('.status-dropdown-button').forEach(btn => {
                btn.classList.toggle('active', !!activeStatus &&
                    btn.dataset.device === activeStatus.device &&
                    btn.dataset.status === activeStatus.status);
            });
        }

        function openStatusPage(deviceKey, statusKey) {
            // klik status yang sama sekali lagi = tutup
            if (activeStatus && activeStatus.device === deviceKey && activeStatus.status === statusKey) {
                closeStatusList();
                return;
            }
            activeStatus = {
                device: deviceKey,
                status: statusKey
            };
            markActiveStatus();

            const meta = deviceMeta[deviceKey];
            const items = (deviceStatusData[deviceKey] || {})[statusKey] || [];
            const label = statusLabel(deviceKey, statusKey);

            document.getElementById('statusListTitle').textContent = `${meta.title} · ${label}`;
            document.getElementById('statusListSubtitle').textContent = `${items.length} unit perangkat berstatus ${label}`;

            document.getElementById('statusListCardsGrid').innerHTML = items.map(item => `
        <div class="pop-status-box">
            <div class="pop-status-box-header">
                <div>
                    <h4 title="${escapeHtml(item.unit)}">${escapeHtml(item.unit)}</h4>
                    <small>${escapeHtml(item.pop_name)} &bull; ${escapeHtml(item.location)}</small>
                </div>
                <span class="status-pill ${statusKey}">${label}</span>
            </div>
            <button type="button" class="btn-detail-pop" data-pop-id="${item.pop_id}" data-device-type="${deviceKey}" data-device-id="${item.device_id || ''}">
                <i class="bi bi-eye"></i> Detail
            </button>
        </div>
    `).join('');

            const box = document.getElementById('statusListContainer');
            box.style.display = 'block';
            box.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        function closeStatusList() {
            activeStatus = null;
            markActiveStatus();
            document.getElementById('statusListContainer').style.display = 'none';
        }

        // delegasi klik (tidak perlu onclick inline)
        document.getElementById('deviceStatusGrid').addEventListener('click', e => {
            const btn = e.target.closest('.status-dropdown-button');
            if (btn && !btn.disabled) openStatusPage(btn.dataset.device, btn.dataset.status);
        });

        document.getElementById('statusListCardsGrid').addEventListener('click', e => {
            const btn = e.target.closest('.btn-detail-pop');
            if (btn) viewPopDetail(btn.dataset.popId, btn.dataset.deviceType, btn.dataset.deviceId);
        });

        //7. Detail PoP

        function viewPopDetail(popId, deviceType = null, deviceId = null) {
            enterPopFocus();

            const cleanPopId = String(popId).replace(/_\d+$/, '');
            const resultBox = document.getElementById('popSummaryResult');
            resultBox.innerHTML = `
                <div class="dashboard-loading">
                    <i class="bi bi-arrow-repeat spin"></i> Memuat detail kelengkapan POP ${cleanPopId}...
                </div>
            `;
            resultBox.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            fetch(popSummaryUrlTemplate.replace('__ID__', encodeURIComponent(cleanPopId)))
                .then(res => res.text())
                .then(html => {
                    resultBox.innerHTML = html;

                    // <script> di dalam innerHTML TIDAK otomatis jalan -> jalankan manual
                    resultBox.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(a => newScript.setAttribute(a.name, a.value));
                        newScript.textContent = oldScript.textContent;
                        oldScript.replaceWith(newScript);
                    });

                    document.dispatchEvent(new CustomEvent('popSummaryLoaded', {
                        detail: {
                            popId: cleanPopId
                        }
                    }));
                    
                    if (deviceType && deviceId) {
                        setTimeout(() => {
                            let targetId = deviceType === 'battery' 
                                ? 'battery-group-' + (deviceId || '0') 
                                : deviceType + '-card-' + deviceId;
                                
                            const targetEl = document.getElementById(targetId);
                            if (targetEl) {
                                targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                targetEl.style.transition = 'box-shadow 0.5s';
                                targetEl.style.boxShadow = '0 0 15px rgba(2, 132, 199, 0.5)';
                                setTimeout(() => targetEl.style.boxShadow = '', 2000);
                            }
                        }, 500); // Give it time to render scripts (like carousel)
                    }
                })
                .catch(() => {
                    resultBox.innerHTML =
                        `<div class="dashboard-hint">Gagal memuat data POP: ${cleanPopId}</div>`;
                });
        }

        document.getElementById('btnTerapkanFilter').addEventListener('click', () => {
            enterPopFocus(); // <- tambahan
            document.getElementById('popSummaryResult').innerHTML = '';
            // ...fetch filterPop seperti sebelumnya
        });

        document.getElementById('btnResetFilter').addEventListener('click', () => {
            ['filterKota', 'filterStatus', 'filterKelengkapan'].forEach(id => document.getElementById(id).value =
                '');
            exitPopFocus(); // <- ganti dari sekadar mengosongkan hasil
        })

        /* =========================================================
           8. CHART ANALYTICS
           ========================================================= */
        function initAnalyticsCharts() {
            const popLabels = [
                'POP PAYO SELINCAH', 'POP TELANAIPURA', 'POP BULIAN 01', 'POP BUNGO 01',
                'POP SIPIN', 'POP TEMBESI', 'POP SAROLANGUN', 'POP BANGKO', 'POP TUNGKAL', 'POP KERINCI'
            ];

            const stackedOptions = () => ({
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 9
                            },
                            color: '#475569',
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        stacked: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'NILAI PER POP',
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            color: '#0f3356'
                        },
                        ticks: {
                            color: '#475569'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            },
                            color: '#334155'
                        }
                    },
                    datalabels: {
                        display: false
                    }
                }
            });

            // 1. Healthy Index
            const ctxHealthy = document.getElementById('healthyIndexChart')?.getContext('2d');
            if (ctxHealthy) {
                new Chart(ctxHealthy, {
                    type: 'bar',
                    data: {
                        labels: popLabels,
                        datasets: [{
                                label: 'Rectifier',
                                data: [18, 17, 16, 15, 14, 13, 12, 11, 10, 9],
                                backgroundColor: '#1e3a8a'
                            },
                            {
                                label: 'KWH',
                                data: [19, 18, 17, 16, 15, 14, 13, 12, 11, 10],
                                backgroundColor: '#2563eb'
                            },
                            {
                                label: 'Baterai',
                                data: [20, 19, 18, 17, 16, 15, 14, 13, 12, 11],
                                backgroundColor: '#3b82f6'
                            },
                            {
                                label: 'AC',
                                data: [19, 18, 17, 16, 15, 14, 13, 12, 11, 10],
                                backgroundColor: '#60a5fa'
                            },
                            {
                                label: 'Genset',
                                data: [18, 17, 16, 15, 14, 13, 12, 11, 10, 9],
                                backgroundColor: '#93c5fd'
                            }
                        ]
                    },
                    options: stackedOptions()
                });
            }

            // 2. Non-Healthy Index
            const ctxNonHealthy = document.getElementById('nonHealthyIndexChart')?.getContext('2d');
            if (ctxNonHealthy) {
                new Chart(ctxNonHealthy, {
                    type: 'bar',
                    data: {
                        labels: popLabels.slice().reverse(),
                        datasets: [{
                                label: 'Rectifier',
                                data: [10, 12, 13, 14, 15, 16, 17, 18, 19, 20],
                                backgroundColor: '#7f1d1d'
                            },
                            {
                                label: 'KWH',
                                data: [10, 11, 12, 14, 15, 16, 17, 18, 19, 19],
                                backgroundColor: '#991b1b'
                            },
                            {
                                label: 'Baterai',
                                data: [10, 11, 12, 13, 15, 16, 17, 18, 19, 19],
                                backgroundColor: '#dc2626'
                            },
                            {
                                label: 'AC',
                                data: [9, 10, 11, 12, 14, 15, 16, 17, 18, 18],
                                backgroundColor: '#ef4444'
                            },
                            {
                                label: 'Genset',
                                data: [9, 10, 10, 11, 12, 14, 15, 16, 17, 18],
                                backgroundColor: '#fca5a5'
                            }
                        ]
                    },
                    options: stackedOptions()
                });
            }

            // 3. Populasi POP
            const populasiData = @json($populasiPop);
            const populasiCanvas = document.getElementById('populasiChart');
            const legendEl = document.getElementById('populasiLegend');

            if (populasiCanvas && populasiData.data.length) {
                // warna dipertahankan per tipe; tipe lain memakai warna cadangan
                const colorByType = {
                    'POP-B': '#38bdf8',
                    'POP-SB': '#6366f1',
                    'POP-D': '#eab308',
                    'POP-A': '#22c55e'
                };
                const fallback = ['#ec4899', '#14b8a6', '#f97316', '#94a3b8'];
                let fb = 0;
                const colors = populasiData.labels.map(l => colorByType[l] || fallback[fb++ % fallback.length]);

                // total hanya dari irisan yang sedang tampil
                const visibleTotal = chart => chart.data.datasets[0].data
                    .reduce((sum, v, i) => sum + (chart.getDataVisibility(i) ? v : 0), 0);

                const popChart = new Chart(populasiCanvas.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: populasiData.labels,
                        datasets: [{
                            data: populasiData.data,
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 8
                        }]
                    },
                    plugins: [ChartDataLabels],
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: 10
                        },
                        animation: {
                            duration: 350
                        },
                        onHover: (evt, elements) => {
                            evt.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                            highlightLegend(elements.length ? elements[0].index : null);
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    family: 'Poppins',
                                    weight: '700',
                                    size: 11
                                },
                                // sembunyikan label kalau irisan terlalu kecil (<4%)
                                display: ctx => {
                                    const v = ctx.dataset.data[ctx.dataIndex];
                                    return v / visibleTotal(ctx.chart) >= 0.04;
                                },
                                formatter: (v, ctx) => Math.round(v / visibleTotal(ctx.chart) * 100) + '%'
                            },
                            tooltip: {
                                backgroundColor: '#0f3356',
                                padding: 8,
                                displayColors: false,
                                titleFont: {
                                    family: 'Poppins',
                                    size: 11
                                },
                                bodyFont: {
                                    family: 'Poppins',
                                    size: 11
                                },
                                callbacks: {
                                    label: ctx =>
                                        ` ${ctx.raw} POP (${Math.round(ctx.raw / visibleTotal(ctx.chart) * 100)}%)`
                                }
                            }
                        }
                    }
                });

                /* ---- LEGEND (chip persegi di bawah chart) ---- */
                legendEl.innerHTML = populasiData.labels.map((label, i) => `
        <li class="pop-legend-item" data-index="${i}" title="${populasiData.data[i]} POP">
            <span class="pop-legend-swatch" style="background:${colors[i]}"></span>
            <span class="pop-legend-label">${escapeHtml(label)}</span>
        </li>
    `).join('');

                const legendItems = legendEl.querySelectorAll('.pop-legend-item');

                function highlightLegend(activeIndex) {
                    legendItems.forEach((el, i) => {
                        el.classList.toggle('is-active', activeIndex === i);
                        el.classList.toggle('is-dimmed', activeIndex !== null && activeIndex !== i);
                    });
                }

                function highlightSlice(index) {
                    if (index === null) {
                        popChart.setActiveElements([]);
                        popChart.tooltip.setActiveElements([], {
                            x: 0,
                            y: 0
                        });
                    } else if (popChart.getDataVisibility(index)) {
                        const arc = popChart.getDatasetMeta(0).data[index];
                        const pos = arc.tooltipPosition();
                        popChart.setActiveElements([{
                            datasetIndex: 0,
                            index
                        }]);
                        popChart.tooltip.setActiveElements([{
                            datasetIndex: 0,
                            index
                        }], pos);
                    }
                    popChart.update();
                }

                legendItems.forEach((el, i) => {
                    el.addEventListener('mouseenter', () => {
                        highlightLegend(i);
                        highlightSlice(i);
                    });
                    el.addEventListener('mouseleave', () => {
                        highlightLegend(null);
                        highlightSlice(null);
                    });

                    // klik = sembunyikan / tampilkan irisan
                    el.addEventListener('click', () => {
                        popChart.toggleDataVisibility(i);
                        el.classList.toggle('is-hidden', !popChart.getDataVisibility(i));
                        popChart.update();
                    });
                });

                // keluar dari area chart = reset highlight legend
                populasiCanvas.addEventListener('mouseleave', () => highlightLegend(null));

            } else if (legendEl) {
                document.querySelector('.population-body').innerHTML =
                    '<div class="dashboard-device-empty">Belum ada data tipe POP.</div>';
            }
        }

        //peta provinsi jambi
        const mapPopData = [{
                pop: 'POP_PAYOSELINCAH',
                name: 'POP Payo Selincah',
                lat: -1.608,
                lng: 103.614,
                device: 'ac',
                status: 'good',
                kab: 'Kota Jambi'
            },
            {
                pop: 'POP_1MRB001',
                name: 'POP Muara Bulian',
                lat: -1.725,
                lng: 103.250,
                device: 'battery',
                status: 'warning',
                kab: 'Batanghari'
            },
            {
                pop: 'POP_1JMB10007',
                name: 'POP Telanaipura',
                lat: -1.625,
                lng: 103.600,
                device: 'rectifier',
                status: 'good',
                kab: 'Kota Jambi'
            },
            {
                pop: 'POP_1MRT10000',
                name: 'POP Muara Tembesi',
                lat: -1.720,
                lng: 103.120,
                device: 'kwh',
                status: 'good',
                kab: 'Batanghari'
            },
            {
                pop: 'POP_1MBN002',
                name: 'POP Muara Bungo',
                lat: -1.490,
                lng: 102.120,
                device: 'genset',
                status: 'warning',
                kab: 'Bungo'
            },
            {
                pop: 'POP_1SRL001',
                name: 'POP Sarolangun 01',
                lat: -2.300,
                lng: 102.650,
                device: 'rectifier',
                status: 'alert',
                kab: 'Sarolangun'
            },
            {
                pop: 'POP_1KRN001',
                name: 'POP Kerinci/Sungai Penuh',
                lat: -2.060,
                lng: 101.400,
                device: 'battery',
                status: 'good',
                kab: 'Kerinci'
            },
            {
                pop: 'POP_1KBL001',
                name: 'POP Kuala Tungkal',
                lat: -0.816,
                lng: 103.460,
                device: 'genset',
                status: 'good',
                kab: 'Tanjung Jabung Barat'
            },
            {
                pop: 'POP_1MSB001',
                name: 'POP Muara Sabak',
                lat: -1.130,
                lng: 103.850,
                device: 'ac',
                status: 'good',
                kab: 'Tanjung Jabung Timur'
            },
            {
                pop: 'POP_1BGK001',
                name: 'POP Bangko',
                lat: -2.070,
                lng: 102.260,
                device: 'rectifier',
                status: 'alert',
                kab: 'Merangin'
            }
        ];

        function initJambiMap() {
            const mapElement = document.getElementById('jambiMap');
            if (!mapElement || typeof L === 'undefined') return;

            jambiMap = L.map('jambiMap', {
                center: [-1.65, 102.8],
                zoom: 8,
                minZoom: 7,
                maxZoom: 14
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(jambiMap);

            mapPopData.forEach(item => {
                const markerColor = item.status === 'good' ? '#22c55e' :
                    (item.status === 'warning' ? '#f59e0b' : '#ef4444');
                const customIcon = L.divIcon({
                    className: 'custom-map-marker-wrapper',
                    html: `<div class="custom-map-marker" style="--marker-color:${markerColor}"><i class="bi bi-geo-alt-fill"></i></div>`,
                    iconSize: [32, 40],
                    iconAnchor: [16, 40],
                    popupAnchor: [0, -36]
                });

                const marker = L.marker([item.lat, item.lng], {
                    icon: customIcon
                });
                marker.bindPopup(`
                    <div class="map-popup">
                        <strong>${item.name}</strong>
                        <span>Kode: ${item.pop}</span>
                        <small>Wilayah: ${item.kab}</small>
                        <span class="popup-status ${item.status}">Status: ${item.status.toUpperCase()}</span>
                        <button type="button" onclick="viewPopDetail('${item.pop}')">Lihat Detail</button>
                    </div>
                `);

                marker.device = item.device;
                marker.status = item.status;
                marker.addTo(jambiMap);
                markers.push(marker);
            });

            document.getElementById('mapStatusFilter').addEventListener('change', filterMarkers);

            setTimeout(() => jambiMap.invalidateSize(), 300);
        }

        function filterMarkers() {
            const st = document.getElementById('mapStatusFilter').value;
            markers.forEach(m => {
                const matchSt = (st === 'all') ||
                    (st === 'good' && m.status === 'good') ||
                    (st === 'warning' && m.status !== 'good');
                if (matchSt) {
                    if (!jambiMap.hasLayer(m)) jambiMap.addLayer(m);
                } else if (jambiMap.hasLayer(m)) {
                    jambiMap.removeLayer(m);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadDeviceStatus();
            initAnalyticsCharts();
            initJambiMap();

            // Auto-open POP Detail if URL has parameters (from Notification page)
            const params = new URLSearchParams(window.location.search);
            const openPopId = params.get('pop_id');
            if (openPopId) {
                const openDeviceType = params.get('device_type');
                const openDeviceId = params.get('device_id');
                
                // Beri sedikit delay agar UI dashboard siap
                setTimeout(() => {
                    viewPopDetail(openPopId, openDeviceType, openDeviceId);
                }, 500);

                // Hapus parameter dari URL agar jika direfresh tidak buka otomatis lagi
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        function scrollCarousel(trackId, dotsId, dir) {
            const s = carouselState[trackId];
            if (!s) return;
            goToPage(trackId, dotsId, s.page + dir);
        }

        function initPopSummaryCarousels() {
            initCarousel('rectifierTrack', 'rectifierDots');
            initCarousel('kwhTrack', 'kwhDots');
            initCarousel('batteryOuterTrack', 'batteryOuterDots', '.rectifier-group-slide', 1);
            document.querySelectorAll('#popSummaryResult .battery-inner-track')
                .forEach(t => initCarousel(t.id, null, '.battery-bank-card', 2));
            initCarousel('acTrack', 'acDots');
            initCarousel('gensetTrack', 'gensetDots');
        }
        document.addEventListener('popSummaryLoaded', initPopSummaryCarousels);
    </script>
</body>

</html>
