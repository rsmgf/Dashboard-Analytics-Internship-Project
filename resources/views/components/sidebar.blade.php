@props(['active' => ''])

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
    </div>

    <div class="sidebar-section">
        <div class="section-title">Dashboard</div>
        <a href="{{ route('dashboard') }}" class="sidebar-menu {{ $active === 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="section-title">General</div>
        <a href="{{ route('pops.index') }}" class="sidebar-menu {{ $active === 'pop' ? 'active' : '' }}">
            <i class="bi bi-shield-fill"></i>
            <span>POP</span>
        </a>
        <a href="{{ route('rma') }}" class="sidebar-menu {{ $active === 'rma' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Form RMA</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="section-title">Akun</div>
        <form method="POST" action="{{ route('logout') }}" style="margin: 4px 16px;">
            @csrf
            <button type="submit" class="sidebar-menu" style="width: 100%; margin: 0; background: none; border: none; text-align: left; cursor: pointer;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</aside>

<div id="sidebarOverlay" class="sidebar-overlay"></div>
