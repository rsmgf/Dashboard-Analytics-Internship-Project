<header class="topbar">
    <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
        <i class="bi bi-list" id="sidebarToggleIcon"></i>
    </button>

    <div class="user-profile" style="margin-left: auto; display: flex; align-items: center; gap: 12px; cursor: pointer;">
        <span style="font-weight: 600; font-size: 14px; color: #333;">
            {{ Auth::check() ? Auth::user()->name : 'Nama User' }}
        </span>
        <div style="width: 38px; height: 38px; background-color: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-person-fill" style="font-size: 20px;"></i>
        </div>
    </div>
</header>

{{-- Script toggle sidebar — didefinisikan sekali di sini, tidak perlu ditulis ulang di setiap blade --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarIcon   = document.getElementById('sidebarToggleIcon');
        const overlay       = document.getElementById('sidebarOverlay');

        function isMobile() {
            return window.innerWidth <= 768;
        }

        // Tutup sidebar di mobile
        function closeMobileSidebar() {
            document.body.classList.remove('sidebar-open');
            if (sidebarIcon) {
                sidebarIcon.classList.replace('bi-x', 'bi-list');
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                if (isMobile()) {
                    // ── MOBILE: toggle sidebar-open + overlay ──
                    const isNowOpen = document.body.classList.toggle('sidebar-open');
                    if (sidebarIcon) {
                        if (isNowOpen) {
                            sidebarIcon.classList.replace('bi-list', 'bi-x');
                        } else {
                            sidebarIcon.classList.replace('bi-x', 'bi-list');
                        }
                    }
                } else {
                    // ── DESKTOP: toggle collapsed (icon-only mode) ──
                    document.body.classList.toggle('sidebar-collapsed');
                    if (sidebarIcon) {
                        if (document.body.classList.contains('sidebar-collapsed')) {
                            sidebarIcon.classList.replace('bi-list', 'bi-chevron-right');
                        } else {
                            sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
                        }
                    }
                }
            });
        }

        // Klik overlay → tutup sidebar
        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }

        // Resize window → bersihkan state yang tidak sesuai platform
        window.addEventListener('resize', function () {
            if (!isMobile()) {
                // Kembali ke desktop: hapus state mobile
                closeMobileSidebar();
            } else {
                // Kembali ke mobile: hapus collapsed state desktop
                document.body.classList.remove('sidebar-collapsed');
                if (sidebarIcon && sidebarIcon.classList.contains('bi-chevron-right')) {
                    sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
                }
            }
        });
    });
</script>
