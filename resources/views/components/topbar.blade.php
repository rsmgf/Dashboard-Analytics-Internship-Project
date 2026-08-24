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

{{-- ======== GLOBAL TOAST NOTIFICATION ======== --}}
<div id="toast-container" style="
    position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;
    display: flex; flex-direction: column; gap: 10px; pointer-events: none; align-items: center;
"></div>

@if(session('success') || session('error') || session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast('success', @json(session('success')));
        @endif
        @if(session('error'))
            showToast('error', @json(session('error')));
        @endif
        @if(session('info'))
            showToast('info', @json(session('info')));
        @endif
    });
</script>
@endif

<style>
.toast-item {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem; font-weight: 500;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    pointer-events: all;
    min-width: 280px; max-width: 380px;
    animation: toastSlideIn 0.35s ease forwards;
    transition: opacity 0.4s, transform 0.4s;
}
.toast-item.success { background:#dcfce7; color:#15803d; border-left:4px solid #16a34a; }
.toast-item.error   { background:#fee2e2; color:#b91c1c; border-left:4px solid #dc2626; }
.toast-item.info    { background:#dbeafe; color:#1d4ed8; border-left:4px solid #2563eb; }
.toast-item.warning { background:#fff7ed; color:#c2410c; border-left:4px solid #f97316; }
.toast-item .toast-icon { font-size: 1.1rem; flex-shrink: 0; }
.toast-item .toast-msg  { flex: 1; }
.toast-item .toast-close { cursor:pointer; opacity:0.5; font-size:1rem; flex-shrink:0; }
.toast-item .toast-close:hover { opacity:1; }
@keyframes toastSlideIn {
    from { opacity:0; transform: translateY(-20px); }
    to   { opacity:1; transform: translateY(0); }
}
</style>

<script>
function showToast(type, message) {
    const icons = { success: 'bi-check-circle-fill', error: 'bi-exclamation-circle-fill', info: 'bi-info-circle-fill', warning: 'bi-trash3-fill' };
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-item ' + type;
    toast.innerHTML = `
        <i class="bi ${icons[type]} toast-icon"></i>
        <span class="toast-msg">${message}</span>
        <i class="bi bi-x toast-close" onclick="this.closest('.toast-item').remove()"></i>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 400);
    }, 7000);
}
</script>




