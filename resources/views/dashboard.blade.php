<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css'])
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

                    <div class="dashboard-search-wrapper">
                        <div class="dashboard-search-box">
                            <input type="text" id="searchPopInput" placeholder="Cari POP (nama atau kode)..."
                                autocomplete="off">
                            <i class="bi bi-search"></i>
                        </div>
                        <div id="searchSuggestions" class="dashboard-search-suggestions"></div>
                    </div>
                </div>

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

        function loadPopSummary(popId) {
            resultBox.innerHTML =
                `<div class="dashboard-loading"><i class="bi bi-arrow-repeat spin"></i> Memuat data...</div>`;
            hintBox.style.display = 'none';

            fetch(popSummaryUrlTemplate.replace('__ID__', popId))
                .then(res => res.text())
                .then(html => {
                    resultBox.innerHTML = html;
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
    </script>
</body>

</html>
