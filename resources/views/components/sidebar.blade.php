<aside class="sidebar" id="sidebar">

    {{-- LOGO --}}
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
    </div>

    {{-- MENU UTAMA --}}
    <div class="sidebar-section">
        <p class="section-title">Menu</p>

        @foreach ($menus ?? [] as $menu)
            @if ($menu->children->isNotEmpty())
                @php
                    $isChildActive = $menu->children->contains(fn($c) => $c->route && request()->routeIs($c->route));
                @endphp

                {{-- PARENT DENGAN CHILD → toggle dropdown, bukan link --}}
                <div class="sidebar-menu sidebar-dropdown {{ $isChildActive ? 'active' : '' }}"
                    data-dropdown-toggle="menu-{{ $menu->id }}">
                    <i class="{{ $menu->icon }}"></i>
                    <span>{{ $menu->name }}</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>

                <div class="sidebar-submenu {{ $isChildActive ? 'show' : '' }}" id="menu-{{ $menu->id }}">
                    @foreach ($menu->children as $child)
                        <a href="{{ route($child->route) }}"
                            class="sidebar-submenu-item {{ request()->routeIs($child->route) ? 'active' : '' }}">
                            <i class="{{ $child->icon ?? 'bi bi-dot' }}"></i>
                            <span>{{ $child->name }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- MENU BIASA (TANPA CHILD) --}}
                <a href="{{ $menu->route ? route($menu->route) : '#' }}"
                    class="sidebar-menu {{ $menu->route && request()->routeIs($menu->route) ? 'active' : '' }}">
                    <i class="{{ $menu->icon }}"></i>
                    <span>{{ $menu->name }}</span>
                </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sidebar-dropdown').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const targetId = this.dataset.dropdownToggle;
                const submenu = document.getElementById(targetId);

                const isOpen = submenu.classList.toggle('show');
                this.classList.toggle('active', isOpen);
            });
        });
    });
</script>