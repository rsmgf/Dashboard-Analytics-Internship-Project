<aside class="sidebar" id="sidebar">

    {{-- LOGO --}}
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
    </div>

    {{-- MENU UTAMA --}}
    <div class="sidebar-section">
        <p class="section-title">Menu</p>

        @foreach ($menus ?? [] as $menu)
            <a href="{{ $menu->route ? route($menu->route) : '#' }}"
                class="sidebar-menu {{ $menu->route && request()->routeIs($menu->route) ? 'active' : '' }}">
                <i class="{{ $menu->icon }}"></i>
                <span>{{ $menu->name }}</span>
            </a>

            @if ($menu->children->isNotEmpty())
                @foreach ($menu->children as $child)
                    <a href="{{ route($child->route) }}"
                        class="sidebar-menu {{ request()->routeIs($child->route) ? 'active' : '' }}"
                        style="padding-left: 48px;">
                        <i class="{{ $child->icon ?? 'bi bi-dot' }}"></i>
                        <span>{{ $child->name }}</span>
                    </a>
                @endforeach
            @endif
        @endforeach
    </div>

    {{-- AKUN / LOGOUT --}}
    @auth
        <div class="sidebar-section">
            <p class="section-title">Akun</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-menu"
                    style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    @endauth

</aside>
