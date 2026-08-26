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
</header><div id="toast-container"></div><script>
function showToast(type, message) {
    const icons = {
        success: 'bi-check-circle-fill',
        error:   'bi-exclamation-circle-fill',
        info:    'bi-info-circle-fill',
        warning: 'bi-exclamation-triangle-fill'
    };
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-item ' + type;
    toast.innerHTML = `<i class="bi ${icons[type]} toast-icon"></i><span class="toast-msg">${message}</span><button class="toast-close bi bi-x" onclick="this.closest('.toast-item').remove()"></button>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-16px)';
        setTimeout(() => toast.remove(), 400);
    }, 7000);
}

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
    @if(session('warning'))
        showToast('warning', @json(session('warning')));
    @endif

    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarIcon   = document.getElementById('sidebarToggleIcon');
    const overlay       = document.getElementById('sidebarOverlay');
    function isMobile() { return window.innerWidth <= 768; }
    function closeMobileSidebar() {
        document.body.classList.remove('sidebar-open');
        if (sidebarIcon) sidebarIcon.classList.replace('bi-x', 'bi-list');
    }
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (isMobile()) {
                const isNowOpen = document.body.classList.toggle('sidebar-open');
                if (sidebarIcon) sidebarIcon.classList.replace(isNowOpen ? 'bi-list' : 'bi-x', isNowOpen ? 'bi-x' : 'bi-list');
            } else {
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
    if (overlay) overlay.addEventListener('click', closeMobileSidebar);
    window.addEventListener('resize', function () {
        if (!isMobile()) { closeMobileSidebar(); }
        else {
            document.body.classList.remove('sidebar-collapsed');
            if (sidebarIcon && sidebarIcon.classList.contains('bi-chevron-right'))
                sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
        }
    });
});
</script>