<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css', 'resources/css/card.css'])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="dashboard-content">

                <div class="dashboard-welcome">
                    <div>
                        <h1>Selamat datang, {{ auth()->user()->name }}! 👋</h1>
                        <p>Pantau kelengkapan data utilitas di seluruh POP dengan mudah dan cepat</p>
                    </div>

                    <div class="dashboard-welcome-actions">
                        <div class="dashboard-search-wrapper">
                            <div class="dashboard-search-box">
                                <input type="text" id="searchPopInput" placeholder="Cari POP (nama atau kode)..."
                                    autocomplete="off">
                                <i class="bi bi-search"></i>
                            </div>
                            <div id="searchSuggestions" class="dashboard-search-suggestions"></div>
                        </div>

                        @if ($canFilter)
                            <button type="button" id="btnToggleFilter" class="dashboard-filter-btn" title="Filter POP">
                                <i class="bi bi-sliders"></i>
                            </button>
                        @endif
                    </div>
                </div>

                @if ($canFilter)
                    <div id="filterPanel" class="dashboard-filter-panel">
                        <div class="dashboard-filter-row">
                            <div class="dashboard-filter-field">
                                <label>Kota/Kabupaten</label>
                                <select id="filterKota">
                                    <option value="">Semua</option>
                                </select>
                            </div>

                            <div class="dashboard-filter-field">
                                <label>Status Utilisasi</label>
                                <select id="filterStatus">
                                    <option value="">Semua</option>
                                    <option value="Good">Good</option>
                                    <option value="Warning">Warning</option>
                                    <option value="Alert">Alert</option>
                                </select>
                            </div>

                            <div class="dashboard-filter-field">
                                <label>Kelengkapan Form</label>
                                <select id="filterKelengkapan">
                                    <option value="">Semua</option>
                                    <option value="lengkap">Lengkap</option>
                                    <option value="belum_lengkap">Belum Lengkap</option>
                                </select>
                            </div>

                            <button type="button" id="btnTerapkanFilter"
                                class="dashboard-filter-apply">Terapkan</button>
                        </div>
                    </div>
                @endif

                <div class="dashboard-summary-grid">
                    <div class="dashboard-summary-card">
                        <div class="dashboard-summary-icon icon-rectifier"><i class="bi bi-hdd-stack-fill"></i></div>
                        <div>
                            <span class="dashboard-summary-label">Total POP</span>
                            <span class="dashboard-summary-value">{{ $totalPop }} Lokasi</span>
                        </div>
                    </div>
                    <div class="dashboard-summary-card">
                        <div class="dashboard-summary-icon icon-kwh"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div>
                            <span class="dashboard-summary-label">Total POP</span>
                            <span class="dashboard-summary-value">{{ $totalPop }} Lokasi</span>
                        </div>
                    </div>
                    <div class="dashboard-summary-card">
                        <div class="dashboard-summary-icon icon-battery"><i class="bi bi-battery-full"></i></div>
                        <div>
                            <span class="dashboard-summary-label">Total POP</span>
                            <span class="dashboard-summary-value">{{ $totalPop }} Lokasi</span>
                        </div>
                    </div>
                    <div class="dashboard-summary-card">
                        <div class="dashboard-summary-icon icon-ac"><i class="bi bi-fan"></i></div>
                        <div>
                            <span class="dashboard-summary-label">Total POP</span>
                            <span class="dashboard-summary-value">{{ $totalPop }} Lokasi</span>
                        </div>
                    </div>
                </div>

                <div id="searchHint" class="dashboard-hint">
                    <i class="bi bi-info-circle-fill"></i> Gunakan pencarian untuk melihat detail kelengkapan data pada
                    POP tertentu
                </div>

                <div id="popSummaryResult"></div>

            </div>
        </main>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const searchPopUrl = "{{ route('dashboard.searchPop') }}";
        const popSummaryUrlTemplate = "{{ route('dashboard.popSummary', ['pop' => '__ID__']) }}";

        const searchInput = document.getElementById('searchPopInput');
        const suggestionsBox = document.getElementById('searchSuggestions');
        const resultBox = document.getElementById('popSummaryResult');
        const hintBox = document.getElementById('searchHint');

        let debounceTimer;

        @if ($canFilter)
            const filterOptionsUrl = "{{ route('dashboard.filterOptions') }}";
            const filterPopUrl = "{{ route('dashboard.filterPop') }}";
            const filterPanel = document.getElementById('filterPanel');
            const btnToggleFilter = document.getElementById('btnToggleFilter');

            btnToggleFilter.addEventListener('click', function() {
                filterPanel.classList.toggle('show');
            });

            fetch(filterOptionsUrl)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('filterKota');
                    data.kota.forEach(kota => {
                        const opt = document.createElement('option');
                        opt.value = kota;
                        opt.textContent = kota;
                        select.appendChild(opt);
                    });
                });

            document.getElementById('btnTerapkanFilter').addEventListener('click', function() {
                const params = new URLSearchParams({
                    kota_kabupaten: document.getElementById('filterKota').value,
                    status_utilisasi: document.getElementById('filterStatus').value,
                    kelengkapan: document.getElementById('filterKelengkapan').value,
                });

                resultBox.innerHTML =
                    `<div class="dashboard-loading"><i class="bi bi-arrow-repeat spin"></i> Memuat data...</div>`;
                hintBox.style.display = 'none';

                fetch(`${filterPopUrl}?${params.toString()}`)
                    .then(res => res.text())
                    .then(html => {
                        resultBox.innerHTML = html;
                        resultBox.querySelectorAll('.dashboard-filter-pop-item').forEach(item => {
                            item.addEventListener('click', function() {
                                loadPopSummary(this.dataset.popId);
                            });
                        });
                    });
            });
        @endif

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
            if (pops.length === 0) {
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
                    loadPopSummary(this.dataset.id);
                    suggestionsBox.classList.remove('show');
                    searchInput.value = this.querySelector('strong').textContent;
                });
            });
        }

        const carouselState = {};

        function loadPopSummary(popId) {
            resultBox.innerHTML =
                `<div class="dashboard-loading"><i class="bi bi-arrow-repeat spin"></i> Memuat data...</div>`;
            hintBox.style.display = 'none';

            fetch(popSummaryUrlTemplate.replace('__ID__', popId))
                .then(res => res.text())
                .then(html => {
                    resultBox.innerHTML = html;
                    initCarousel('rectifierTrack', 'rectifierDots');
                    initCarousel('kwhTrack', 'kwhDots');
                    initCarousel('batteryOuterTrack', 'batteryOuterDots', '.rectifier-group-slide', 1);
                    document.querySelectorAll('.battery-inner-track').forEach(function(el) {
                        initCarousel(el.id, null, '.battery-bank-card');
                    });
                })
                .catch(() => {
                    resultBox.innerHTML = `<div class="dashboard-hint">Gagal memuat data POP.</div>`;
                });
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dashboard-search-wrapper')) {
                suggestionsBox.classList.remove('show');
            }
        });

        function initCarousel(trackId, dotsId, cardSelector = '.donut-device-card', visibleCount = 2) {
            const track = document.getElementById(trackId);
            if (!track) return;

            const cards = track.querySelectorAll(cardSelector);
            if (cards.length === 0) return;

            const cardWidth = cards[0].offsetWidth + 20;
            const totalPositions = Math.max(1, cards.length - visibleCount + 1);

            carouselState[trackId] = {
                page: 0,
                totalPages: totalPositions,
                cardWidth,
                dotsId
            };

            const dotsContainer = dotsId ? document.getElementById(dotsId) : null;
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

            updateArrows(trackId);
        }

        function updateArrows(trackId) {
            const state = carouselState[trackId];
            if (!state) return;

            const prefix = trackId.replace('Track', '');
            const arrowLeft = document.getElementById(prefix + 'ArrowLeft');
            const arrowRight = document.getElementById(prefix + 'ArrowRight');

            if (arrowLeft) arrowLeft.classList.toggle('hidden', state.page === 0 || state.totalPages <= 1);
            if (arrowRight) arrowRight.classList.toggle('hidden', state.page >= state.totalPages - 1 || state
                .totalPages <=
                1);
        }

        function updateDots(trackId, dotsId) {
            const state = carouselState[trackId];
            const dotsContainer = document.getElementById(dotsId);
            if (!state || !dotsContainer) return;

            dotsContainer.querySelectorAll('.dashboard-carousel-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === state.page);
            });
        }

        function goToPage(trackId, dotsId, page) {
            const track = document.getElementById(trackId);
            const state = carouselState[trackId];
            if (!track || !state) return;

            page = Math.max(0, Math.min(page, state.totalPages - 1));
            state.page = page;

            track.scrollTo({
                left: page * state.cardWidth,
                behavior: 'smooth'
            }); // geser 1 kartu
            updateDots(trackId, dotsId);
            updateArrows(trackId);
        }

        function scrollCarousel(trackId, dotsId, direction) {
            const state = carouselState[trackId];
            if (!state) return;
            goToPage(trackId, dotsId, state.page + direction);
        }
    </script>
</body>

</html>
