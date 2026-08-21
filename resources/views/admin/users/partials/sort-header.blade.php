@php
    $newDir = $sort === $column && $dir === 'asc' ? 'desc' : 'asc';
    $icon = 'bi-arrow-down-up';
    $activeClass = '';
    if ($sort === $column) {
        $icon = $dir === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down';
        $activeClass = $dir === 'asc' ? 'active-asc' : 'active-desc';
    }
    $queryParams = array_merge(request()->except(['sort', 'dir', 'page']), ['sort' => $column, 'dir' => $newDir]);
@endphp
<a href="{{ request()->url() }}?{{ http_build_query($queryParams) }}" class="sortable-header {{ $activeClass }}"
    style="text-decoration:none; color:inherit;">
    {{ $label }} <i class="bi {{ $icon }}"></i>
</a>
