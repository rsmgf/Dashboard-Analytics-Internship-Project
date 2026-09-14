<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-card.css'
    ])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="genset-content">
                <div class="genset-page-header">
                    <div class="genset-page-left">
                        <button class="genset-back-button">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="genset-header-text">
                            <div class="genset-breadcrumb">
                                <span>POP</span>
                                <i class="bi bi-chevron-right"></i>
                                <strong>Genset</strong>
                            </div>
                            <span class="genset-page-subtitle">
                                Kode: <strong>POP_1MBN10004</strong>
                                &nbsp;·&nbsp;
                                Jambi
                                &nbsp;—&nbsp;
                                6 Genset
                            </span>
                        </div>
                    </div>

                    <button class="genset-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Genset
                    </button>
                </div>

                <div class="genset-grid">
                    @php
                        $gensetList = [
                            ['number' => 'Genset #1', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Sudah PM'],
                            ['number' => 'Genset #2', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Belum PM'],
                            ['number' => 'Genset #3', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Jadwal PM'],
                            ['number' => 'Genset #4', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Sudah PM'],
                            ['number' => 'Genset #5', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Sudah PM'],
                            ['number' => 'Genset #6', 'merk' => 'Potise', 'model' => 'DP20P5G', 'kapasitas' => '20 KVA', 'status' => 'Sudah PM'],
                        ];
                    @endphp

                    @foreach ($gensetList as $genset)
                        <div class="genset-card">
                            <div class="genset-card-header">
                                <div class="genset-title-wrapper">
                                    <div class="genset-icon">
                                        <i class="bi bi-cpu-fill"></i>
                                    </div>
                                    <div class="card-title-text">
                                        <h3>Checklist Genset</h3>
                                        <span>Data Genset</span>
                                    </div>
                                </div>
                                <span class="genset-badge">
                                    {{ $genset['number'] }}
                                </span>
                            </div>

                            <div class="genset-information">
                                <div class="genset-info-row">
                                    <span>Merk Genset</span>
                                    <strong>{{ $genset['merk'] }}</strong>
                                </div>
                                <div class="genset-info-row">
                                    <span>Model</span>
                                    <strong>{{ $genset['model'] }}</strong>
                                </div>
                                <div class="genset-info-row">
                                    <span>Kapasitas (KVA)</span>
                                    <strong>{{ $genset['kapasitas'] }}</strong>
                                </div>
                                <div class="genset-info-row">
                                    <span>Status PM Genset</span>
                                    <strong>{{ $genset['status'] }}</strong>
                                </div>
                            </div>

                            <div class="genset-meta">
                                <div class="genset-meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>07 Agu 2026</span>
                                </div>
                                <div class="genset-meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Jambi</span>
                                </div>
                            </div>

                            <div class="genset-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>Manager Unit ICONPLUS KP Jambi - Sandria Abhiseka · 04 Sep 2026, 10:31</span>
                            </div>

                            <div class="genset-card-footer">
                                <button class="genset-delete-button">
                                    <i class="bi bi-trash3-fill"></i>
                                    Hapus
                                </button>
                                <button class="genset-detail-button">
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