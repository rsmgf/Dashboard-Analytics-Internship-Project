@php
    $perms = $menu->permissions->keyBy('name');
    $actions = ['create', 'read', 'update', 'delete'];
    $hasAnyAction = $menu->route && $perms->isNotEmpty();
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
                @foreach ($actions as $action)
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
            </div>
        @else
            <span style="color:#94a3b8; font-size:0.8rem; font-style:italic;">
                <i class="bi bi-dash-circle"></i> Belum tersedia
            </span>
        @endif
    </td>
</tr>
