@php
    // Ambil semua permission milik menu ini dari DB (bukan hanya 4 action standar)
    $perms = $menu->permissions->keyBy('name');
    $hasAnyAction = $menu->route && $perms->isNotEmpty();

    // Pisahkan: base actions (create/read/update/delete) dan extra actions (filter/ekspor, dll.)
    $baseActions  = ['create', 'read', 'update', 'delete'];
    $extraActions = $perms->keys()
        ->map(fn($name) => str($name)->after("{$menu->route}.")->toString())
        ->diff($baseActions)
        ->values();
@endphp

<tr class="access-menu-row {{ $isChild ? 'is-child' : '' }}" data-menu-name="{{ $menu->name }}">
    <td>
        <div class="menu-name">
            @if ($hasAnyAction)
                <input type="checkbox" class="select-all-check">
            @endif
            <span>{{ $menu->name }}</span>
        </div>
    </td>
    <td>
        @if ($hasAnyAction)
            <div class="access-actions">
                {{-- Base CRUD actions --}}
                @foreach ($baseActions as $action)
                    @php $perm = $perms->get("{$menu->route}.{$action}"); @endphp
                    @if ($perm)
                        <label class="access-action-toggle">
                            <span class="tgl">
                                <input type="checkbox" class="action-toggle" name="permissions[]"
                                    value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $currentPermissionIds) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </span>
                            {{ $action }}
                        </label>
                    @endif
                @endforeach

                {{-- Extra actions (filter, ekspor, dll.) — dipisahkan dengan divider --}}
                @if ($extraActions->isNotEmpty())
                    <span class="access-action-divider" title="Fitur Tambahan">|</span>
                    @foreach ($extraActions as $action)
                        @php $perm = $perms->get("{$menu->route}.{$action}"); @endphp
                        @if ($perm)
                            <label class="access-action-toggle extra-action">
                                <span class="tgl">
                                    <input type="checkbox" class="action-toggle" name="permissions[]"
                                        value="{{ $perm->id }}"
                                        {{ in_array($perm->id, $currentPermissionIds) ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </span>
                                {{ $action }}
                            </label>
                        @endif
                    @endforeach
                @endif
            </div>
        @else
            <span style="color:#94a3b8; font-size:0.8rem; font-style:italic;">
                <i class="bi bi-dash-circle"></i> Belum tersedia
            </span>
        @endif
    </td>
</tr>
