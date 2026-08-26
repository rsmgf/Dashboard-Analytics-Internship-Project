@props(['items' => []])

@php $last = count($items) - 1; @endphp

<nav class="breadcrumb-nav" aria-label="breadcrumb">
    <a href="{{ route('dashboard') }}" class="breadcrumb-link breadcrumb-home">
        <i class="bi bi-house-door-fill"></i>
    </a>

    @foreach ($items as $index => $item)
        <i class="bi bi-chevron-right breadcrumb-sep"></i>

        @if ($index === $last || empty($item['route']))
            <span class="breadcrumb-current">{{ $item['label'] }}</span>
        @else
            <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="breadcrumb-link">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</nav>