<header class="topbar">
    {{-- Sidebar toggle — disembunyikan saat mode manajer aktif --}}
    @php
        $user = Auth::user();
        $activeRole = session('active_role') ?? ($user?->hasRole('manajer') ? 'manajer' : 'super_admin');
        $isManajerMode = $activeRole === 'manajer';
        // Hanya yang punya role manajer yang bisa switch (otomatis dia punya akses admin)
        $hasBothRoles = $user && $user->hasRole('manajer');
    @endphp

    @if (!$isManajerMode)
        <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
            <i class="bi bi-list" id="sidebarToggleIcon"></i>
        </button>
    @else
        {{-- Logo mini di kiri saat manajer mode (sidebar hilang) --}}
        <div class="topbar-brand">
            <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
        </div>
    @endif

    {{-- Spacer --}}
    <div style="flex:1;"></div>

    {{-- Switch Role Button — hanya tampil jika user punya kedua role --}}
    @if ($hasBothRoles)
        <form method="POST" action="{{ route('role.switch') }}" class="topbar-switch-form">
            @csrf
            @if ($isManajerMode)
                <input type="hidden" name="role" value="super_admin">
                <button type="submit" class="btn-switch-role" title="Beralih ke mode Adminstrator">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Mode Administrator</span>
                </button>
            @else
                <input type="hidden" name="role" value="manajer">
                <button type="submit" class="btn-switch-role manajer" title="Beralih ke mode Executive">
                    <i class="bi bi-person-circle"></i>
                    <span>Mode Executive</span>
                </button>
            @endif
        </form>
    @endif

    <div class="user-profile">
        @if ($isManajerMode)
            <div class="topbar-role-badge">
                <i class="bi bi-person-badge-fill"></i>
                <span>Executive</span>
            </div>
        @endif
        <span class="user-display-name">
            {{ Auth::check() ? Auth::user()->name : 'Nama User' }}
        </span>

        {{-- Logout hanya di topbar saat mode manajer --}}
        @if ($isManajerMode)
            <form method="POST" action="{{ route('logout') }}" class="topbar-logout-form">
                @csrf
                <button type="submit" class="btn-topbar-logout" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        @endif
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
    }, 4000);
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
    @if(session('error_upload'))
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar!',
            text: @json(session('error_upload')),
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Mengerti',
        });
    @endif

    // ---- Universal Client-Side File Size Guard (max 2 MB) ----
    // Intercept semua input file di halaman manapun sebelum form disubmit
    const MAX_FILE_BYTES = 2 * 1024 * 1024; // 2 MB
    document.addEventListener('change', function(e) {
        const input = e.target;
        if (input.type !== 'file') return;
        const file = input.files && input.files[0];
        if (!file) return;

        // Validasi format
        const allowed = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowed.includes(file.type)) {
            Swal.fire({
                icon: 'warning',
                title: 'Format File Tidak Valid',
                html: `File <strong>${file.name}</strong> bukan format yang didukung.<br>
                       Gunakan format: <strong>JPG, JPEG, atau PNG</strong>.`,
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti',
            });
            input.value = '';
            return;
        }

        // Validasi ukuran
        if (file.size > MAX_FILE_BYTES) {
            const sizeMb = (file.size / 1024 / 1024).toFixed(2);
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar!',
                html: `Ukuran file <strong>${file.name}</strong> adalah <strong>${sizeMb} MB</strong>.<br>
                       Maksimal yang diperbolehkan adalah <strong>2 MB</strong>.<br><br>
                       Silakan kompres foto terlebih dahulu lalu coba lagi.`,
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti',
            });
            input.value = '';
        }
    }, true); // capture: true agar intercept sebelum handler lain


    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarIcon   = document.getElementById('sidebarToggleIcon');
    let overlay         = document.getElementById('sidebarOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'sidebarOverlay';
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }
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
    const closeBtn = document.getElementById('sidebarCloseBtn');
    if (closeBtn) closeBtn.addEventListener('click', closeMobileSidebar);
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