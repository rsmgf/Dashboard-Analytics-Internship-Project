@php
    $perms = $menu->permissions->keyBy('name'); // key: "route.action"
    $actions = ['create', 'read', 'update', 'delete'];
@endphp

<tr class="access-menu-row {{ $isChild ? 'is-child' : '' }}" data-menu-name="{{ $menu->name }}">
    <td>
        <div class="menu-name">
            <input type="checkbox" class="select-all-check">
            <span>{{ $menu->name }}</span>
        </div>
    </td>
    <td>
        <div class="access-actions">
            @foreach ($actions as $action)
                @php $perm = $perms->get("{$menu->route}.{$action}"); @endphp
                @if ($perm)
                    <label class="access-action-toggle">
                        <span class="tgl">
                            <input type="checkbox" class="action-toggle" name="permissions[]" value="{{ $perm->id }}"
                                {{ in_array($perm->id, $currentPermissionIds) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </span>
                        {{ $action }}
                    </label>
                @endif
            @endforeach
        </div>
    </td>
</tr>
