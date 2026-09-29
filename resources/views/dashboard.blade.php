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

    @vite([
        'resources/css/sidebar.css',
        'resources/css/dashboard.css',
        'resources/css/card.css',
        'resources/js/app.js'
    ])

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

<body>
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
                <div>
                    <h1>Selamat datang, {{ auth()->user()->name ?? 'Executive' }}! 👋</h1>
                    <p>Monitoring kondisi peralatan di seluruh POP Provinsi Jambi secara real-time</p>
                </div>

                <div class="dashboard-welcome-actions">
                    <div class="dashboard-search-wrapper">
                        <div class="dashboard-search-box">
                            <input
                                type="text"
                                id="searchPopInput"
                                placeholder="Cari POP (nama / kode)..."
                                autocomplete="off"
                            >
                            <i class="bi bi-search"></i>
                        </div>
                        <div id="searchSuggestions" class="dashboard-search-suggestions"></div>
                    </div>
                </div>
            </div>

            {{-- 4 KARTU KPI UTAMA --}}
            <div class="dashboard-kpi-grid">
                {{-- 1. TOTAL POP --}}
                <div class="dashboard-kpi-card">
                    <div class="kpi-icon kpi-blue">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                    <div class="kpi-info">
                        <span>Total POP</span>
                        <strong>{{ $totalPop ?? 25 }}</strong>
                        <small class="text-success"><i class="bi bi-check-circle-fill"></i> POP Aktif</small>
                    </div>
                </div>

                {{-- 2. TOTAL POP ALERT --}}
                <div class="dashboard-kpi-card">
                    <div class="kpi-icon kpi-red">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="kpi-info">
                        <span>Total POP Alert</span>
                        <strong class="text-danger">{{ $totalPopAlert ?? 2 }}</strong>
                        <small>Perlu Penanganan Cepat</small>
                    </div>
                </div>

                {{-- 3. TOTAL POP WARNING --}}
                <div class="dashboard-kpi-card">
                    <div class="kpi-icon kpi-yellow">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div class="kpi-info">
                        <span>Total POP Warning</span>
                        <strong class="text-warning">{{ $totalPopWarning ?? 5 }}</strong>
                        <small>Perlu Monitoring Rutin</small>
                    </div>
                </div>

                {{-- 4. PENDING APPROVAL ANGGOTA --}}
                <div class="dashboard-kpi-card">
                    <div class="kpi-icon kpi-purple">
                        <i class="bi bi-person-fill-exclamation"></i>
                    </div>
                    <div class="kpi-info">
                        <span>Belum Diapprove</span>
                        <strong class="text-purple">{{ $totalPendingApproval ?? 4 }}</strong>
                    </div>
                </div>
            </div>

            {{-- STATUS PERANGKAT POP --}}
            <div class="device-status-section">
                <div class="section-title-dashboard">
                    <div>
                        <h2>Status Perangkat POP</h2>
                        <p>Pilih status pada perangkat untuk melihat daftar POP berdasarkan kondisi operasionalnya</p>
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
                    <div class="analytics-card-header">
                        <div class="analytics-title">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                            <h3>TOP 10 - Healthy Index POP Provinsi Jambi</h3>
                        </div>
                    </div>
                    <div class="chart-container-stacked">
                        <canvas id="healthyIndexChart"></canvas>
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-title">
                            <i class="bi bi-hand-thumbs-down-fill text-danger"></i>
                            <h3>TOP 10 - Non Healthy Index POP Provinsi Jambi</h3>
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
                    <div class="analytics-card-header">
                        <div class="analytics-title">
                            <i class="bi bi-pie-chart-fill"></i>
                            <h3>Populasi POP</h3>
                        </div>
                    </div>
                    <div class="pie-chart-wrapper">
                        <canvas id="populasiChart"></canvas>
                    </div>
                </div>

                {{-- KARTU NOTIFIKASI TERBARU DENGAN INDIKATOR TANDAI DIBACA --}}
                <div class="analytics-card">
                    <div class="analytics-card-header notif-header-flex">
                        <div class="analytics-title">
                            <i class="bi bi-bell-fill"></i>
                            <h3>Notifikasi Terbaru</h3>
                        </div>
                        <a href="{{ url('/notifikasi') }}" class="link-selengkapnya" title="Lihat Semua Notifikasi">
                            Selengkapnya <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    <div class="notification-list">
                        {{-- Notifikasi 1 --}}
                        <div class="notification-item" id="notif-1" onclick="markNotifAsRead('notif-1', 1)">
                            <div class="notif-icon-wrapper">
                                <div class="notif-icon notif-red">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <span class="unread-dot"></span>
                            </div>
                            <div class="notif-content">
                                <strong>Rectifier - POP-1KR8011</strong>
                                <p>Tegangan output di bawah normal</p>
                            </div>
                            <div class="notif-time">
                                <span>17 Feb 2026 10:45</span>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        {{-- Notifikasi 2 --}}
                        <div class="notification-item" id="notif-2" onclick="markNotifAsRead('notif-2', 2)">
                            <div class="notif-icon-wrapper">
                                <div class="notif-icon notif-yellow">
                                    <i class="bi bi-battery-charging"></i>
                                </div>
                                <span class="unread-dot"></span>
                            </div>
                            <div class="notif-content">
                                <strong>Battery - POP-1TNA014</strong>
                                <p>Kapasitas baterai 82%</p>
                            </div>
                            <div class="notif-time">
                                <span>17 Feb 2026 10:45</span>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        {{-- Notifikasi 3 --}}
                        <div class="notification-item" id="notif-3" onclick="markNotifAsRead('notif-3', 3)">
                            <div class="notif-icon-wrapper">
                                <div class="notif-icon notif-blue">
                                    <i class="bi bi-snow"></i>
                                </div>
                                <span class="unread-dot"></span>
                            </div>
                            <div class="notif-content">
                                <strong>Air Conditioner - POP-1KR8011</strong>
                                <p>Temperatur ruangan di atas ambang batas</p>
                            </div>
                            <div class="notif-time">
                                <span>17 Feb 2026 10:45</span>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        {{-- Notifikasi 4 --}}
                        <div class="notification-item" id="notif-4" onclick="markNotifAsRead('notif-4', 4)">
                            <div class="notif-icon-wrapper">
                                <div class="notif-icon notif-purple">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <span class="unread-dot"></span>
                            </div>
                            <div class="notif-content">
                                <strong>Genset - POP-1KR8011</strong>
                                <p>Bahan bakar genset mendekati batas minimal</p>
                            </div>
                            <div class="notif-time">
                                <span>17 Feb 2026 10:45</span>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TARGET CONTAINER LOAD POP SUMMARY --}}
            <div id="popSummaryResult"></div>

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

                    <div class="map-filter">
                        <select id="mapDeviceFilter">
                            <option value="all">Semua Perangkat</option>
                            <option value="rectifier">Rectifier</option>
                            <option value="kwh">KWH</option>
                            <option value="battery">Battery</option>
                            <option value="ac">Air Conditioner</option>
                            <option value="genset">Genset</option>
                        </select>

                        <select id="mapStatusFilter">
                            <option value="all">Semua Status</option>
                            <option value="good">Healthy</option>
                            <option value="warning">UnHealthy</option>
                        </select>
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
    // Fungsi untuk menghilangkan titik merah saat notifikasi diklik
    function markNotifAsRead(elementId, notifId) {
        const item = document.getElementById(elementId);
        if (!item) return;

        const dot = item.querySelector('.unread-dot');
        if (dot) {
            dot.style.opacity = '0';
            dot.style.transform = 'scale(0)';
            setTimeout(() => {
                dot.remove();
            }, 300);
        }
    }

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
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dashboard-search-wrapper')) {
            suggestionsBox.classList.remove('show');
        }
    });

    const deviceStatusData = {
        rectifier: {
            title: 'Rectifier',
            subtitle: 'Perangkat Rectifier',
            icon: 'bi-hdd-stack-fill',
            hasDeviceNumber: true,
            statuses: {
                good: {
                    label: 'Good',
                    items: [
                        { id: 'POP_PAYOSELINCAH_01', popName: 'POP Payo Selincah', location: 'Kota Jambi' },
                        { id: 'POP_1MRB001_01', popName: 'POP Muara Bulian 01', location: 'Batanghari' },
                        { id: 'POP_1MRB001_02', popName: 'POP Muara Bulian 02', location: 'Batanghari' },
                        { id: 'POP_1JMB10007_01', popName: 'POP Telanaipura', location: 'Kota Jambi' },
                        { id: 'POP_1MRT10000_01', popName: 'POP Muara Tembesi', location: 'Batanghari' },
                        { id: 'POP_1MBN002_01', popName: 'POP Muara Bungo 02', location: 'Bungo' },
                        { id: 'POP_1SRL001_02', popName: 'POP Sarolangun 01', location: 'Sarolangun' },
                        { id: 'POP_1JMB002_01', popName: 'POP Sipin', location: 'Kota Jambi' }
                    ]
                },
                warning: {
                    label: 'Warning',
                    items: [
                        { id: 'POP_1MBN002_02', popName: 'POP Muara Bungo 02', location: 'Bungo' }
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
            icon: 'bi-lightning',
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
        ];

        // 1. Healthy Index
        const ctxHealthy = document.getElementById('healthyIndexChart')?.getContext('2d');
        if (ctxHealthy) {
            new Chart(ctxHealthy, {
                type: 'bar',
                data: {
                    labels: popLabels,
                    datasets: [
                        { label: 'Rectifier', data: [18, 17, 16, 15, 14, 13, 12, 11, 10, 9], backgroundColor: '#1e3a8a' },
                        { label: 'KWH', data: [19, 18, 17, 16, 15, 14, 13, 12, 11, 10], backgroundColor: '#2563eb' },
                        { label: 'Baterai', data: [20, 19, 18, 17, 16, 15, 14, 13, 12, 11], backgroundColor: '#3b82f6' },
                        { label: 'AC', data: [19, 18, 17, 16, 15, 14, 13, 12, 11, 10], backgroundColor: '#60a5fa' },
                        { label: 'Genset', data: [18, 17, 16, 15, 14, 13, 12, 11, 10, 9], backgroundColor: '#93c5fd' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, ticks: { font: { size: 9 }, color: '#475569', maxRotation: 45, minRotation: 45 } },
                        y: { stacked: true, max: 100, title: { display: true, text: 'NILAI PER POP', font: { size: 10, weight: 'bold' }, color: '#0f3356' }, ticks: { color: '#475569' } }
                    },
                    plugins: { 
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 }, color: '#334155' } },
                        datalabels: { display: false }
                    }
                }
            });
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
        // 2. Non-Healthy Index
        const ctxNonHealthy = document.getElementById('nonHealthyIndexChart')?.getContext('2d');
        if (ctxNonHealthy) {
            new Chart(ctxNonHealthy, {
                type: 'bar',
                data: {
                    labels: popLabels.slice().reverse(),
                    datasets: [
                        { label: 'Rectifier', data: [10, 12, 13, 14, 15, 16, 17, 18, 19, 20], backgroundColor: '#7f1d1d' },
                        { label: 'KWH', data: [10, 11, 12, 14, 15, 16, 17, 18, 19, 19], backgroundColor: '#991b1b' },
                        { label: 'Baterai', data: [10, 11, 12, 13, 15, 16, 17, 18, 19, 19], backgroundColor: '#dc2626' },
                        { label: 'AC', data: [9, 10, 11, 12, 14, 15, 16, 17, 18, 18], backgroundColor: '#ef4444' },
                        { label: 'Genset', data: [9, 10, 10, 11, 12, 14, 15, 16, 17, 18], backgroundColor: '#fca5a5' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, ticks: { font: { size: 9 }, color: '#475569', maxRotation: 45, minRotation: 45 } },
                        y: { stacked: true, max: 100, title: { display: true, text: 'NILAI PER POP', font: { size: 10, weight: 'bold' }, color: '#0f3356' }, ticks: { color: '#475569' } }
                    },
                    plugins: { 
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 }, color: '#334155' } },
                        datalabels: { display: false }
                    }
                }
            });
        }

        // 3. Populasi POP
        const ctxPopulasi = document.getElementById('populasiChart')?.getContext('2d');
        if (ctxPopulasi) {
            const rawData = [55, 20, 20, 5];
            const rawLabels = ['POP-B', 'POP-SB', 'POP-D', 'POP-A'];
            const total = rawData.reduce((acc, val) => acc + val, 0);

            new Chart(ctxPopulasi, {
                type: 'pie',
                data: {
                    labels: rawLabels,
                    datasets: [{
                        data: rawData,
                        backgroundColor: ['#38bdf8', '#6366f1', '#eab308', '#22c55e'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 16,
                                font: {
                                    size: 9,
                                    family: 'Poppins',
                                    weight: '600'
                                },
                                color: '#0284c7'
                            }
                        },
                        datalabels: {
                            color: '#ffffff',
                            font: {
                                family: 'Poppins',
                                weight: '800',
                                size: 9
                            },
                            formatter: (value) => {
                                const pct = Math.round((value / total) * 100);
                                return `${pct}%`;
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const pct = Math.round((value / total) * 100);
                                    return ` ${context.label}: ${value} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    let jambiMap = null;
    let markers = [];

    const mapPopData = [
        { pop: 'POP_PAYOSELINCAH', name: 'POP Payo Selincah', lat: -1.608, lng: 103.614, device: 'ac', status: 'good', kab: 'Kota Jambi' },
        { pop: 'POP_1MRB001', name: 'POP Muara Bulian', lat: -1.725, lng: 103.250, device: 'battery', status: 'warning', kab: 'Batanghari' },
        { pop: 'POP_1JMB10007', name: 'POP Telanaipura', lat: -1.625, lng: 103.600, device: 'rectifier', status: 'good', kab: 'Kota Jambi' },
        { pop: 'POP_1MRT10000', name: 'POP Muara Tembesi', lat: -1.720, lng: 103.120, device: 'kwh', status: 'good', kab: 'Batanghari' },
        { pop: 'POP_1MBN002', name: 'POP Muara Bungo', lat: -1.490, lng: 102.120, device: 'genset', status: 'warning', kab: 'Bungo' },
        { pop: 'POP_1SRL001', name: 'POP Sarolangun 01', lat: -2.300, lng: 102.650, device: 'rectifier', status: 'alert', kab: 'Sarolangun' },
        { pop: 'POP_1KRN001', name: 'POP Kerinci/Sungai Penuh', lat: -2.060, lng: 101.400, device: 'battery', status: 'good', kab: 'Kerinci' },
        { pop: 'POP_1KBL001', name: 'POP Kuala Tungkal', lat: -0.816, lng: 103.460, device: 'genset', status: 'good', kab: 'Tanjung Jabung Barat' },
        { pop: 'POP_1MSB001', name: 'POP Muara Sabak', lat: -1.130, lng: 103.850, device: 'ac', status: 'good', kab: 'Tanjung Jabung Timur' },
        { pop: 'POP_1BGK001', name: 'POP Bangko', lat: -2.070, lng: 102.260, device: 'rectifier', status: 'alert', kab: 'Merangin' }
    ];

    function initJambiMap() {
        const mapElement = document.getElementById('jambiMap');
        if (!mapElement) return;

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
            const markerColor = item.status === 'good' ? '#22c55e' : (item.status === 'warning' ? '#f59e0b' : '#ef4444');
            const customIcon = L.divIcon({
                className: 'custom-map-marker-wrapper',
                html: `<div class="custom-map-marker" style="--marker-color:${markerColor}"><i class="bi bi-geo-alt-fill"></i></div>`,
                iconSize: [32, 40],
                iconAnchor: [16, 40],
                popupAnchor: [0, -36]
            });

            const marker = L.marker([item.lat, item.lng], { icon: customIcon });
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

        document.getElementById('mapDeviceFilter').addEventListener('change', filterMarkers);
        document.getElementById('mapStatusFilter').addEventListener('change', filterMarkers);

        setTimeout(() => { jambiMap.invalidateSize(); }, 300);
    }

    function filterMarkers() {
        const dev = document.getElementById('mapDeviceFilter').value;
        const st = document.getElementById('mapStatusFilter').value;

        markers.forEach(m => {
            const matchDev = (dev === 'all' || m.device === dev);
            const matchSt = (st === 'all' || m.status === st);
            if (matchDev && matchSt) {
                if (!jambiMap.hasLayer(m)) jambiMap.addLayer(m);
            } else {
                if (jambiMap.hasLayer(m)) jambiMap.removeLayer(m);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderDeviceCards();
        initAnalyticsCharts();
        initJambiMap();
    });
</script>
</body>
</html>