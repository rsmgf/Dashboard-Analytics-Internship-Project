<div class="dashboard-filter-results-header">
    <h3>Hasil Filter — {{ $pops->count() }} POP ditemukan</h3>
</div>

<div class="dashboard-filter-results-list">
    @forelse ($pops as $pop)
        @php
            $totalPerangkat = $pop->rectifiers->count() + $pop->kwhs->count();
        @endphp
        <div class="dashboard-filter-pop-item" data-pop-id="{{ $pop->id }}">
            <div class="dashboard-filter-pop-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="dashboard-filter-pop-info">
                <strong>{{ $pop->nama_pop }}</strong>
                <span>{{ $pop->kode_pop }} &middot; {{ $pop->kota_kabupaten }} &middot; {{ $totalPerangkat }}
                    perangkat</span>
            </div>
            <i class="bi bi-chevron-right"></i>
        </div>
    @empty
        <div class="dashboard-device-empty">Tidak ada POP yang cocok dengan filter ini.</div>
    @endforelse
</div>
