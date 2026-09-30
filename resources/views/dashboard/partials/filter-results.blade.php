<div class="dashboard-filter-results-header">
    <h3>Hasil Filter</h3>
    <span class="filter-count-badge">{{ $pops->count() }} POP</span>
</div>

<div class="dashboard-filter-results-list">
    @forelse ($pops as $pop)
        @php
            $totalPerangkat = $pop->rectifiers->count() + $pop->kwhs->count();
        @endphp
        <button type="button" class="dashboard-filter-pop-item" data-pop-id="{{ $pop->id }}">
            <span class="dashboard-filter-pop-icon"><i class="bi bi-geo-alt-fill"></i></span>
            <span class="dashboard-filter-pop-info">
                <strong>{{ $pop->nama_pop }}</strong>
                <span>{{ $pop->kode_pop }} &middot; {{ $pop->kota_kabupaten }}</span>
            </span>
            <span class="dashboard-filter-pop-meta">{{ $totalPerangkat }} perangkat</span>
            <i class="bi bi-chevron-right"></i>
        </button>
    @empty
        <div class="dashboard-device-empty">
            <i class="bi bi-inbox"></i> Tidak ada POP yang cocok dengan filter ini.
        </div>
    @endforelse
</div>
