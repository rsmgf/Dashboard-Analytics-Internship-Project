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

<body class="{{ (session('active_role') ?? (auth()->user()?->hasRole('manajer') ? 'manajer' : 'super_admin')) === 'manajer' ? 'manajer-mode' : '' }}">
    <div class="app-container">
        {{-- SIDEBAR --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            {{-- TOPBAR --}}
            <x-topbar />

            <div class="notif-page-wrapper">

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
                            <div id="activityViewport" class="activity-viewport" aria-label="Daftar aktivitas notifikasi">
                                <div id="activityVirtualList" class="activity-virtual-list"
                                    data-has-more="{{ $activityHasMore ? 'true' : 'false' }}"
                                    data-next-page="2">
                                    <div id="activityTopSpacer" aria-hidden="true"></div>
                                    <div id="activityRows"></div>
                                    <div id="activityBottomSpacer" aria-hidden="true"></div>
                                </div>
                                <div id="activityEmpty" class="activity-state" @if (count($activityItems)) hidden @endif>Belum ada aktivitas</div>
                                <div id="activityLoading" class="activity-state" hidden>Memuat aktivitas…</div>
                                <button id="activityRetry" class="activity-retry" type="button" hidden>Coba muat lagi</button>
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
                if (targetId === 'tabAktivitas') {
                    initializeActivityList();
                }
            }
        }

        const activityList = document.getElementById('activityVirtualList');
        const activityViewport = document.getElementById('activityViewport');
        const activityRows = document.getElementById('activityRows');
        const activityTopSpacer = document.getElementById('activityTopSpacer');
        const activityBottomSpacer = document.getElementById('activityBottomSpacer');
        const activityLoading = document.getElementById('activityLoading');
        const activityRetry = document.getElementById('activityRetry');
        const activityData = @json($activityItems);
        let activityHasMore = activityList?.dataset.hasMore === 'true';
        let activityNextPage = Number(activityList?.dataset.nextPage || 2);
        let activityLoadingMore = false;
        let activityInitialized = false;
        let activityRenderFrame = null;

        function activityStride() {
            const value = getComputedStyle(activityViewport).getPropertyValue('--activity-row-stride').trim();
            return Number.parseFloat(value) || 84;
        }

        function createActivityRow(item) {
            const row = document.createElement('div');
            row.className = `notif-row activity-virtual-row${item.is_read ? ' read' : ''}`;
            row.id = `notif-aktivitas-${item.id}`;
            row.dataset.notificationRow = 'true';
            row.dataset.id = item.id;
            row.dataset.category = item.category || '';
            row.dataset.popId = item.pop_id || '';
            row.dataset.deviceType = item.device_type || '';
            row.dataset.deviceId = item.device_id || '';
            row.dataset.action = item.action || '';

            const iconColumn = document.createElement('div');
            iconColumn.className = 'notif-icon-col';
            const iconBox = document.createElement('div');
            iconBox.className = `notif-icon-box ${item.icon_bg_class || ''}${item.is_read ? '' : ' has-dot'}`;
            const icon = document.createElement('i');
            icon.className = item.icon_class || 'bi bi-bell-fill';
            iconBox.append(icon);
            iconColumn.append(iconBox);

            const textColumn = document.createElement('div');
            textColumn.className = 'notif-text-col';
            const title = document.createElement('h4');
            title.className = 'notif-title';
            title.textContent = item.title || '';
            const message = document.createElement('p');
            message.className = 'notif-subtitle';
            // Backend escapes message content and restores only plain <strong> tags.
            message.innerHTML = item.message_html || '';
            textColumn.append(title, message);

            const infoColumn = document.createElement('div');
            infoColumn.className = 'notif-info-col';
            const time = document.createElement('span');
            time.className = 'notif-time';
            time.textContent = item.created_at || '';
            const arrow = document.createElement('i');
            arrow.className = 'bi bi-chevron-right notif-arrow';
            infoColumn.append(time, arrow);

            row.append(iconColumn, textColumn, infoColumn);
            return row;
        }

        function renderActivityWindow() {
            if (!activityViewport || !activityList) return;
            const stride = activityStride();
            const overscan = 5;
            const firstVisible = Math.floor(activityViewport.scrollTop / stride);
            const visibleCount = Math.ceil(activityViewport.clientHeight / stride);
            const start = Math.max(0, firstVisible - overscan);
            const end = Math.min(activityData.length, firstVisible + visibleCount + overscan);

            activityTopSpacer.style.height = `${start * stride}px`;
            activityBottomSpacer.style.height = `${Math.max(0, activityData.length - end) * stride}px`;

            const fragment = document.createDocumentFragment();
            for (let index = start; index < end; index++) {
                fragment.append(createActivityRow(activityData[index]));
            }
            activityRows.replaceChildren(fragment);

            const remainingVisibleRows = activityData.length - (firstVisible + visibleCount);
            const prefetchThreshold = Math.max(4, Math.ceil(visibleCount * 0.75));
            if (activityHasMore && !activityLoadingMore && remainingVisibleRows <= prefetchThreshold) {
                loadMoreActivities();
            }
        }

        function scheduleActivityRender() {
            if (activityRenderFrame !== null) return;
            activityRenderFrame = requestAnimationFrame(() => {
                activityRenderFrame = null;
                renderActivityWindow();
            });
        }

        async function loadMoreActivities() {
            if (!activityHasMore || activityLoadingMore) return;
            activityLoadingMore = true;
            activityLoading.hidden = false;
            activityRetry.hidden = true;

            try {
                const url = new URL(`{{ route('notifications.activities') }}`, window.location.origin);
                url.searchParams.set('page', activityNextPage);
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error(`Request gagal (${response.status})`);
                const result = await response.json();
                activityData.push(...(result.items || []));
                activityHasMore = Boolean(result.has_more);
                activityNextPage = result.next_page || activityNextPage + 1;
                activityList.dataset.hasMore = activityHasMore ? 'true' : 'false';
                scheduleActivityRender();
            } catch (error) {
                console.error('Gagal memuat aktivitas:', error);
                activityRetry.hidden = false;
            } finally {
                activityLoadingMore = false;
                activityLoading.hidden = true;
            }
        }

        function initializeActivityList() {
            if (activityInitialized) return;
            activityInitialized = true;
            renderActivityWindow();
        }

        activityViewport?.addEventListener('scroll', scheduleActivityRender, { passive: true });
        activityRetry?.addEventListener('click', loadMoreActivities);
        activityRows?.addEventListener('click', event => {
            const row = event.target.closest('[data-notification-row]');
            if (!row) return;
            markNotifAsRead(row.id, row.dataset.id, row.dataset.category, row.dataset.popId || null,
                row.dataset.deviceType || null, row.dataset.deviceId || null, row.dataset.action || null);
        });
        window.addEventListener('resize', scheduleActivityRender);
        if (document.getElementById('tabAktivitas')?.classList.contains('active')) initializeActivityList();

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
