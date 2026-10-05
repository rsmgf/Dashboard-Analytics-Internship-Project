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
    @vite(['resources/css/sidebar.css', 'resources/css/dashboard.css', 'resources/css/notification.css', 'resources/js/app.js'])
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
                                @forelse($notificationsStatus as $notif)
                                    <div class="notif-row {{ $notif->is_read ? 'read' : '' }}"
                                        id="notif-status-{{ $notif->id }}"
                                        onclick="markNotifAsRead('notif-status-{{ $notif->id }}', {{ $notif->id }}, '{{ $notif->category }}', '{{ $notif->pop_id }}', '{{ $notif->device_type }}', '{{ $notif->device_id }}')">
                                        <div class="notif-icon-col">
                                            <div
                                                class="notif-icon-box {{ $notif->icon_bg_class }} {{ !$notif->is_read ? 'has-dot' : '' }}">
                                                <i class="{{ $notif->icon_class }}"></i>
                                            </div>
                                        </div>
                                        <div class="notif-text-col">
                                            <h4 class="notif-title">{{ $notif->title }}</h4>
                                            <p class="notif-subtitle">{{ $notif->message }}</p>
                                        </div>
                                        <div class="notif-info-col">
                                            @if ($notif->badge_label)
                                                <span
                                                    class="badge-status {{ $notif->badge_class }}">{{ $notif->badge_label }}</span>
                                            @endif
                                            <span
                                                class="notif-time">{{ $notif->created_at->format('d M Y H:i') }}</span>
                                            <i class="bi bi-chevron-right notif-arrow"></i>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align:center; padding: 30px; color: #64748b;">
                                        Belum ada notifikasi status
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- TAB 2: AKTIVITAS --}}
                        <div id="tabAktivitas" class="notif-tab-pane">
                            <div class="notif-list">
                                @forelse($notificationsAktivitas as $notif)
                                    <div class="notif-row {{ $notif->is_read ? 'read' : '' }}"
                                        id="notif-aktivitas-{{ $notif->id }}"
                                        onclick="markNotifAsRead('notif-aktivitas-{{ $notif->id }}', {{ $notif->id }}, '{{ $notif->category }}', '{{ $notif->pop_id }}', '{{ $notif->device_type }}', '{{ $notif->device_id }}', '{{ $notif->action }}')">
                                        <div class="notif-icon-col">
                                            <div
                                                class="notif-icon-box {{ $notif->icon_bg_class }} {{ !$notif->is_read ? 'has-dot' : '' }}">
                                                <i class="{{ $notif->icon_class }}"></i>
                                            </div>
                                        </div>
                                        <div class="notif-text-col">
                                            <h4 class="notif-title">{{ $notif->title }}</h4>
                                            <p class="notif-subtitle">{!! $notif->message !!}</p>
                                        </div>
                                        <div class="notif-info-col">
                                            <span
                                                class="notif-time">{{ $notif->created_at->format('d M Y H:i') }}</span>
                                            <i class="bi bi-chevron-right notif-arrow"></i>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align:center; padding: 30px; color: #64748b;">
                                        Belum ada aktivitas
                                    </div>
                                @endforelse
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

        function markNotifAsRead(elementId, notifId, category = '', popId = null, deviceType = null, deviceId = null,
            action = null) {
            const item = document.getElementById(elementId);

            const processNavigation = () => {
                // Aktivitas menambah atau mengubah POP diarahkan ke halaman edit POP tersebut.
                if (
                    category === 'aktivitas' &&
                    deviceType === 'pop' &&
                    deviceId && ['create', 'update'].includes(action)
                ) {
                    window.location.href = `{{ route('pops.index', [], false) }}/${encodeURIComponent(deviceId)}/edit`;
                    return;
                }

                if (popId) {
                    // Aktivitas perangkat selain POP diarahkan ke detail perangkat.
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

                    // Untuk notifikasi lain, buka ringkasan POP di dashboard.
                    window.location.href =
                        `{{ route('dashboard', [], false) }}?pop_id=${popId}&device_type=${deviceType || ''}&device_id=${deviceId || ''}`;
                }
            };

            if (!item) {
                processNavigation();
                return;
            }

            const dot = item.querySelector('.unread-dot') || item.querySelector('.has-dot');

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
                    if (dot) {
                        if (dot.classList.contains('has-dot')) {
                            dot.classList.remove('has-dot');
                        } else {
                            dot.style.opacity = '0';
                            dot.style.transform = 'scale(0)';
                            setTimeout(() => dot.remove(), 300);
                        }
                    }
                    item.classList.add('read');
                }
                processNavigation();
            }).catch(err => {
                console.error(err);
                processNavigation();
            });
        }
    </script>
</body>

</html>
