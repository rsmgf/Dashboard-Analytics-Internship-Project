<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - PLN Icon Plus</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite([
        'resources/css/sidebar.css',
        'resources/css/dashboard.css',
        'resources/css/notification.css',
        'resources/js/app.js'
    ])
</head>

<body>
<div class="app-container">
    {{-- SIDEBAR --}}
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">
        {{-- TOPBAR --}}
        <x-topbar />

        <div class="dashboard-content notif-page-wrapper">

            {{-- HEADER BANNER --}}
            <div class="notification-header-group">
                <button type="button" class="btn-back-circle" onclick="window.history.back()" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="notif-pill-banner">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifikasi</span>
                </div>
            </div>

            {{-- PANEL UTAMA NOTIFIKASI --}}
            <div class="notification-panel-card">

                {{-- TAB FOLDER HEADER --}}
                <div class="notif-folder-header">
                    <div class="notif-tab-trapezoid">
                        <button type="button" class="tab-btn active" onclick="switchNotifTab(this, 'tabStatus')">
                            Status
                        </button>
                        <button type="button" class="tab-btn" onclick="switchNotifTab(this, 'tabAktivitas')">
                            Aktivitas
                        </button>
                    </div>
                </div>

                {{-- ISI NOTIFIKASI --}}
                <div class="notif-panel-body">

                    {{-- TAB 1: STATUS --}}
                    <div id="tabStatus" class="notif-tab-pane active">
                        <div class="notif-list">

                            {{-- Item 1: Warning --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-red has-dot">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Rectifier - POP-1KR8011</h4>
                                    <p class="notif-subtitle">Tegangan output di bawah normal</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="badge-status badge-warning">WARNING</span>
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                            {{-- Item 2: Alert --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-orange has-dot">
                                        <i class="bi bi-battery-charging"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Battery - POP-1TNA014</h4>
                                    <p class="notif-subtitle">Kapasitas baterai 20%</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="badge-status badge-alert">ALERT</span>
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                            {{-- Item 3: Belum Uji --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-blue">
                                        <i class="bi bi-snow"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Rectifier - POP-1KR8011</h4>
                                    <p class="notif-subtitle">Tegangan output di bawah normal</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="badge-status badge-belum-uji">BELUM UJI</span>
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                            {{-- Item 4: Jadwal Uji --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-purple">
                                        <i class="bi bi-lightning-fill"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Rectifier - POP-1KR8011</h4>
                                    <p class="notif-subtitle">Tegangan output jauh di bawah normal</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="badge-status badge-jadwal-uji">JADWAL UJI</span>
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- TAB 2: AKTIVITAS --}}
                    <div id="tabAktivitas" class="notif-tab-pane">
                        <div class="notif-list">

                            {{-- Item 1 --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-red has-dot">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Rectifier - POP-1KR8011</h4>
                                    <p class="notif-subtitle"><strong>Damara</strong> menambahkan rectifier pada POP-1K38011</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                            {{-- Item 2 --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-orange has-dot">
                                        <i class="bi bi-battery-charging"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Battery - POP-1TNA014</h4>
                                    <p class="notif-subtitle"><strong>Damara</strong> mengedit rectifier POP_1CKG004_RECT01 pada POP-1K38011</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                            {{-- Item 3 --}}
                            <div class="notif-row">
                                <div class="notif-icon-col">
                                    <div class="notif-icon-box bg-icon-blue">
                                        <i class="bi bi-snow"></i>
                                    </div>
                                </div>
                                <div class="notif-text-col">
                                    <h4 class="notif-title">Rectifier - POP-1KR8011</h4>
                                    <p class="notif-subtitle"><strong>Damara</strong> mengedit rectifier POP_1CKG004_RECT01 pada POP-1K38011</p>
                                </div>
                                <div class="notif-info-col">
                                    <span class="notif-time">17 Feb 2025 10:45</span>
                                    <i class="bi bi-chevron-right notif-arrow"></i>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>

<script>
    function switchNotifTab(btn, targetId) {
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.notif-tab-pane').forEach(el => el.classList.remove('active'));

        btn.classList.add('active');
        const target = document.getElementById(targetId);
        if (target) {
            target.classList.add('active');
        }
    }
</script>
</body>
</html>