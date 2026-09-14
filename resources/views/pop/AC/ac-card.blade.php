<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data AC - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-card.css'
    ])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="ac-content">
                <div class="ac-page-header">
                    <div class="ac-page-left">
                        <button class="ac-back-button">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="ac-header-text">
                            <div class="ac-breadcrumb">
                                <span>POP</span>
                                <i class="bi bi-chevron-right"></i>
                                <strong>AC</strong>
                            </div>
                            <span class="ac-page-subtitle">
                                Kode: <strong>POP_1MBN10004</strong>
                                &nbsp;·&nbsp;
                                Jambi
                                &nbsp;—&nbsp;
                                6 AC
                            </span>
                        </div>
                    </div>

                    <button class="ac-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah AC
                    </button>
                </div>

                <div class="ac-grid">
                    @php
                        $acList = [
                            ['number' => 'AC #1', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                            ['number' => 'AC #2', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                            ['number' => 'AC #3', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                            ['number' => 'AC #4', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                            ['number' => 'AC #5', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                            ['number' => 'AC #6', 'serpo' => 'Payo Selincah', 'suhu' => '21°C', 'tipe' => 'Non Inverter'],
                        ];
                    @endphp

                    @foreach ($acList as $ac)
                        <div class="ac-card">
                            <div class="ac-card-header">
                                <div class="ac-title-wrapper">
                                    <div class="ac-icon">
                                        <i class="bi bi-snow"></i>
                                    </div>
                                    <div class="card-title-text">
                                        <h3>Checklist AC</h3>
                                        <span>Data AC</span>
                                    </div>
                                </div>
                                <span class="ac-badge">
                                    {{ $ac['number'] }}
                                </span>
                            </div>

                            <div class="ac-information">
                                <div class="ac-info-row">
                                    <span>Serpo</span>
                                    <strong>{{ $ac['serpo'] }}</strong>
                                </div>
                                <div class="ac-info-row">
                                    <span>Suhu POP</span>
                                    <strong>{{ $ac['suhu'] }}</strong>
                                </div>
                                <div class="ac-info-row">
                                    <span>Tipe AC</span>
                                    <strong>{{ $ac['tipe'] }}</strong>
                                </div>
                            </div>

                            <div class="ac-meta">
                                <div class="ac-meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>07 Agu 2026</span>
                                </div>
                                <div class="ac-meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Jambi</span>
                                </div>
                            </div>

                            <div class="ac-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>Manager Unit ICONPLUS KP Jambi - Sandria Abhiseka · 04 Sep 2026, 10:31</span>
                            </div>

                            <div class="ac-card-footer">
                                <button class="ac-delete-button">
                                    <i class="bi bi-trash3-fill"></i>
                                    Hapus
                                </button>
                                <button class="ac-detail-button">
                                    Detail
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</body>

</html>