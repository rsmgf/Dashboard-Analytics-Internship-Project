<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $isSuperAdminOrManager ? 'Log RMA - PLN Icon Plus' : 'Form RMA - PLN Icon Plus' }}</title>

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    @vite(['resources/css/sidebar.css', 'resources/css/rma-awal.css'])

    <!-- FLATPICKR (Date Range Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-calendar {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid #e2e8f0 !important;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #ffffff !important;
        }
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }
        .flatpickr-day.inRange {
            background: #dbeafe !important;
            border-color: #dbeafe !important;
            color: #1e40af !important;
            box-shadow: -5px 0 0 #dbeafe, 5px 0 0 #dbeafe !important;
        }
        .flatpickr-day:hover {
            background: #eff6ff !important;
        }
    </style>
</head>

<body>

    <div class="app-container">

        <!-- SIDEBAR COMPONENT -->
        <x-sidebar />

        <main class="main-content">

            <!-- TOPBAR COMPONENT -->
            <x-topbar />

            <!-- CONTENT -->
            <div class="rma-page">
                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div class="page-title">
                        <div class="title-icon">
                            <i class="bi bi-{{ $isSuperAdminOrManager ? 'clipboard2-data-fill' : 'file-earmark-text-fill' }}"></i>
                        </div>
                        <div>
                            <h1>{{ $isSuperAdminOrManager ? 'Log RMA' : 'Form RMA' }}</h1>
                            <p>{{ $isSuperAdminOrManager ? 'Kelola seluruh Return Material Authorization' : 'Return Material Authorization' }}</p>
                        </div>
                    </div>

                    @can('rma.create')
                        <a href="{{ route('rma.create') }}" class="btn-tambah">
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah RMA</span>
                        </a>
                    @endcan
                </div>

                <!-- ALERT BANNER -->
                @if ($isSuperAdminOrManager)
                    <div class="alert-info-custom alert-admin">
                        <i class="bi bi-shield-check"></i>
                        <span>
                            <strong>Mode Admin:</strong>
                            @if ($tampil === 'milik_saya')
                                Menampilkan RMA milik Anda sendiri.
                            @else
                                Anda melihat seluruh RMA dari semua pengguna.
                            @endif
                        </span>
                    </div>
                @else
                    <div class="alert-info-custom">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Hanya form yang Anda isi yang bisa dilihat pada halaman ini</span>
                    </div>
                @endif

                <!-- TABLE CARD CONTAINER -->
                <div class="table-card">

                    <!-- JUDUL RIWAYAT RMA & BATCH ACTION -->
                    <div class="history-header">
                        <div class="history-title-group">
                            <h2>Riwayat RMA</h2>
                            @if ($isSuperAdminOrManager)
                                <!-- Filter Toggle Semua / Milik Saya -->
                                <div class="view-toggle" role="group" aria-label="Tampilkan data">
                                    <a href="{{ route('rma', array_merge(request()->query(), ['tampil' => 'semua'])) }}"
                                        class="{{ $tampil === 'semua' ? 'active' : '' }}">Semua</a>
                                    <a href="{{ route('rma', array_merge(request()->query(), ['tampil' => 'milik_saya'])) }}"
                                        class="{{ $tampil === 'milik_saya' ? 'active' : '' }}">Milik Saya</a>
                                </div>
                            @endif
                        </div>

                        <div id="batchActionArea" class="batch-action-toolbar">
                            <div class="batch-pill-group">
                                <button type="button" id="btnSelectAll" class="btn-pill-item" title="Pilih semua baris di halaman ini">
                                    <i class="bi bi-check2-square icon-select-all"></i>
                                    <span>Pilih Semua</span>
                                </button>
                                @php
                                    $isTodayFilter = ($filter === 'hari_ini');
                                    $todayUrl = route('rma', array_merge(request()->query(), [
                                        'filter' => $isTodayFilter ? null : 'hari_ini',
                                        'page' => null,
                                    ]));
                                @endphp
                                <a href="{{ $todayUrl }}" id="btnFilterToday" class="btn-pill-item {{ $isTodayFilter ? 'active-today' : '' }}"
                                    title="{{ $isTodayFilter ? 'Klik untuk tampilkan semua tanggal' : 'Filter hanya data RMA Hari Ini' }}">
                                    <i class="bi bi-calendar2-check-fill icon-select-today"></i>
                                    <span>{{ $isTodayFilter ? 'Hari Ini (Aktif)' : 'Hari Ini' }}</span>
                                    @if ($isTodayFilter)
                                        <i class="bi bi-x-circle-fill icon-clear-today" title="Hapus filter hari ini"></i>
                                    @endif
                                </a>
                                <button type="button" id="btnDeselectAll" class="btn-pill-item btn-deselect" style="display: none;" title="Batalkan semua pilihan">
                                    <i class="bi bi-x-circle-fill icon-deselect"></i>
                                    <span>Batal</span>
                                </button>
                            </div>

                            <button type="button" id="btnBatchDownload" class="btn-batch-download-modern" style="display: none;">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                <span>Download Terpilih <span class="badge-count"><span id="batchCount">0</span> PDF</span></span>
                            </button>

                            @can('rma.delete')
                                <button type="button" id="btnBatchDelete" class="btn-batch-delete" style="display:none;">
                                    <i class="bi bi-trash3-fill"></i>
                                    <span>Hapus Terpilih <span class="badge-count"><span id="batchDeleteCount">0</span> RMA</span></span>
                                </button>
                            @endcan

                            @if ($filter === 'hari_ini' && $rmas->total() > 0)
                                <button type="button" id="btnDownloadAllToday" class="btn-batch-download-modern btn-batch-today" title="Download seluruh {{ $rmas->total() }} RMA hari ini dalam satu file ZIP">
                                    <i class="bi bi-file-earmark-zip-fill"></i>
                                    <span>Download Semua Hari Ini <span class="badge-count">{{ $rmas->total() }} PDF</span></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- SEARCH CONTROL -->
                    <form method="GET" action="{{ route('rma') }}" class="table-controls" id="rmaSearchForm">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        @if(request('tampil'))
                            <input type="hidden" name="tampil" value="{{ request('tampil') }}">
                        @endif
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <div class="search-wrapper">
                            <input type="search" name="search" placeholder="Cari No. RMA, Judul, Perangkat, Lokasi..." value="{{ request('search') }}" aria-label="Cari RMA">
                            <button type="submit" class="search-btn" title="Cari" aria-label="Cari">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>

                        {{-- Date Range Filter (Flatpickr) --}}
                        <div class="date-filter-group">
                            <label class="date-filter-label" for="rmaDateRangePicker">
                                <i class="bi bi-calendar-range"></i> Rentang Tanggal:
                            </label>

                            <input type="hidden" name="date_from" id="hiddenDateFrom" value="{{ $dateFrom ?? '' }}">
                            <input type="hidden" name="date_to" id="hiddenDateTo" value="{{ $dateTo ?? '' }}">

                            <div class="date-input-wrap">
                                <i class="bi bi-calendar3 date-input-icon"></i>
                                <input type="text" id="rmaDateRangePicker" class="date-input" placeholder="Pilih rentang tanggal..." readonly>
                                <button type="button" id="btnClearDateRange" class="date-clear-btn" title="Hapus rentang tanggal" aria-label="Hapus rentang tanggal"
                                    style="display:{{ ($dateFrom || $dateTo) ? 'inline-flex' : 'none' }};">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>

                            <div class="date-filter-actions">
                                <button type="submit" id="btnApplyDateFilter" class="btn-apply-date" title="Terapkan Filter Tanggal">
                                    <i class="bi bi-funnel-fill"></i>
                                    <span>Terapkan</span>
                                </button>

                                @if($dateFrom || $dateTo)
                                    <a href="{{ route('rma', array_merge(array_filter(request()->except(['date_from','date_to','page'])), [])) }}"
                                        class="btn-reset-date" title="Reset filter tanggal">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>Reset</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- TABLE CONTENT -->
                    @php
                        // Arah sort kebalikan untuk toggle (dipakai header tabel & sort bar mobile)
                        $idNextDirection = $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc';
                        $nextDirection   = $sort === 'tanggal' && $direction === 'asc' ? 'desc' : 'asc';
                    @endphp

                    <!-- SORT BAR (tampil hanya di layar sempit, saat header tabel disembunyikan) -->
                    <div class="mobile-sort">
                        <span class="mobile-sort-label"><i class="bi bi-arrow-down-up"></i> Urutkan</span>
                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'id', 'direction' => $idNextDirection])) }}"
                            class="sort-chip {{ $sort === 'id' ? 'active' : '' }}">
                            No.
                            @if ($sort === 'id')
                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'tanggal', 'direction' => $nextDirection])) }}"
                            class="sort-chip {{ $sort === 'tanggal' ? 'active' : '' }}">
                            Tanggal
                            @if ($sort === 'tanggal')
                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="rma-table">
                            <thead>
                                <tr>
                                    <th class="th-check">
                                        <input type="checkbox" id="checkAllHead" title="Pilih Semua di Halaman Ini" aria-label="Pilih semua di halaman ini">
                                    </th>
                                    <th>
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'id', 'direction' => $idNextDirection])) }}">
                                            No.
                                            @if ($sort === 'id')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Judul / Nama Dokumen</th>
                                    <th>
                                        <!-- Tombol Toggle ASC / DESC untuk Tanggal Pengisian -->
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'tanggal', 'direction' => $nextDirection])) }}">
                                            Tanggal Pengisian
                                            @if ($sort === 'tanggal')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up sort-idle"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>ID POP</th>
                                    <th>Merk/Type</th>
                                    @if ($isSuperAdminOrManager)
                                        <th>Dibuat Oleh</th>
                                    @endif
                                    <th class="th-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rmas as $rma)
                                    @php
                                        $createdDate = $rma->created_at ? $rma->created_at->format('Y-m-d') : '';
                                        $tglDoc = $rma->tanggal ? $rma->tanggal->format('Y-m-d') : '';
                                        $judulRma = $rma->judul_rma ?: ($rma->so_po ?: ('RMA #' . $rma->id));
                                        $canDeleteRma = $isSuperAdminOrManager
                                            || ($rma->user_id && $rma->user_id === auth()->id())
                                            || (!$rma->user_id && $rma->nama_pemohon === auth()->user()->name);
                                    @endphp
                                    <tr>
                                        <td class="td-check">
                                            <input type="checkbox" class="rma-check" value="{{ $rma->id }}" data-created="{{ $createdDate }}" data-tanggal="{{ $tglDoc }}" data-can-delete="{{ $canDeleteRma ? '1' : '0' }}" aria-label="Pilih {{ $judulRma }}">
                                        </td>
                                        <td class="td-no" data-label="No.">{{ $rmas->firstItem() + $loop->index }}</td>
                                        <td class="td-judul">
                                            <span class="cell-primary cell-title" title="{{ $judulRma }}">{{ $judulRma }}</span>
                                            <span class="text-sub">{{ $rma->so_po ?? '-' }}</span>
                                        </td>
                                        <td class="td-tgl" data-label="Tanggal Pengisian">
                                            <span class="cell-primary">{{ $rma->created_at->format('d-M Y') }}</span>
                                            <span class="text-sub">{{ $rma->created_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td class="td-pop" data-label="ID POP">
                                            {{ $rma->lokasi_asal ?? '-' }}
                                        </td>
                                        <td class="td-merk" data-label="Merk/Type">
                                            @php
                                                $rmaTypes = $rma->types;
                                                $brands = $rmaTypes->pluck('merk')->unique()->values();
                                                $typePreview = $rmaTypes->first()?->type;
                                                $remainingTypes = max(0, $rmaTypes->count() - 1);
                                            @endphp
                                            <span class="cell-primary">{{ $brands->isNotEmpty() ? $brands->implode(', ') : ($rma->merk ?? '-') }}</span>
                                            <span class="text-sub">{{ $typePreview ?: ($rma->type ?? '-') }}{{ $remainingTypes ? ' +' . $remainingTypes : '' }}</span>
                                        </td>
                                        @if ($isSuperAdminOrManager)
                                            <td class="td-user" data-label="Dibuat Oleh">
                                                @if ($rma->user)
                                                    <span class="creator">
                                                        <span class="creator-avatar">{{ strtoupper(substr($rma->user->name, 0, 1)) }}</span>
                                                        <span class="creator-name">{{ $rma->user->name }}</span>
                                                    </span>
                                                @elseif ($rma->nama_pemohon)
                                                    <span class="creator-name muted">{{ $rma->nama_pemohon }}</span>
                                                @else
                                                    <span class="text-sub">—</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="td-aksi text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('rma.pdf', $rma->id) }}" class="btn-lihat" target="_blank"
                                                    title="Lihat Dokumen RMA" aria-label="Lihat PDF RMA {{ $rma->so_po }}">
                                                    <i class="bi bi-eye"></i><span class="btn-label">Lihat</span>
                                                </a>
                                                <a href="{{ route('rma.download', $rma->id) }}" class="btn-download"
                                                    title="Download Dokumen RMA" aria-label="Download PDF RMA {{ $rma->so_po }}">
                                                    <i class="bi bi-download"></i><span class="btn-label">Download</span>
                                                </a>
                                                @can('rma.update')
                                                    @php
                                                        $canEdit = $isSuperAdminOrManager
                                                            || ($rma->user_id && $rma->user_id === auth()->id())
                                                            || (!$rma->user_id && $rma->nama_pemohon === auth()->user()->name);
                                                    @endphp
                                                    @if ($canEdit)
                                                        <a href="{{ route('rma.edit', $rma->id) }}" class="btn-edit"
                                                            title="Edit RMA" aria-label="Edit RMA {{ $rma->judul_rma }}">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                                @can('rma.delete')
                                                    @if ($canDeleteRma)
                                                        <button type="button" class="btn-hapus"
                                                            onclick="confirmDeleteRma('{{ route('rma.destroy', $rma->id) }}', '{{ addslashes($rma->judul_rma ?? 'RMA #' . $rma->id) }}')"
                                                            title="Hapus RMA" aria-label="Hapus RMA {{ $rma->judul_rma }}">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="row-empty">
                                        <td colspan="{{ $isSuperAdminOrManager ? 8 : 7 }}" class="td-empty">
                                            <i class="bi bi-inbox"></i>
                                            <p>Belum ada data RMA.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="pagination-footer">
                        <div class="pagination-info">
                            Menampilkan <strong>{{ $rmas->firstItem() ?? 0 }}</strong> - <strong>{{ $rmas->lastItem() ?? 0 }}</strong> dari <strong>{{ $rmas->total() }}</strong> data RMA
                            @if ($filter === 'hari_ini')
                                <span class="badge-filter-today"><i class="bi bi-calendar2-check"></i> Hari Ini</span>
                            @endif
                            @if ($isSuperAdminOrManager)
                                <span class="muted">{{ $tampil === 'milik_saya' ? '(milik Anda)' : '(semua pengguna)' }}</span>
                            @endif
                        </div>
                        <div class="pagination-controls">
                            {{ $rmas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    <!-- Form Batch Download Tersembunyi -->
    <form id="batchDownloadForm" action="{{ route('rma.batch-download') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="ids" id="batchIdsInput">
        <input type="hidden" name="mode" id="batchModeInput" value="">
        <input type="hidden" name="tampil" value="{{ request('tampil', 'semua') }}">
    </form>

    @can('rma.delete')
        <form id="batchDeleteForm" action="{{ route('rma.bulk-destroy') }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
            <div id="batchDeleteIds"></div>
        </form>
    @endcan

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAllHead = document.getElementById('checkAllHead');
            const rmaChecks = document.querySelectorAll('.rma-check');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnDeselectAll = document.getElementById('btnDeselectAll');
            const btnBatchDownload = document.getElementById('btnBatchDownload');
            const btnBatchDelete = document.getElementById('btnBatchDelete');
            const batchDeleteCount = document.getElementById('batchDeleteCount');
            const batchDeleteForm = document.getElementById('batchDeleteForm');
            const batchDeleteIds = document.getElementById('batchDeleteIds');
            const btnDownloadAllToday = document.getElementById('btnDownloadAllToday');
            const batchCount = document.getElementById('batchCount');
            const batchDownloadForm = document.getElementById('batchDownloadForm');
            const batchIdsInput = document.getElementById('batchIdsInput');
            const batchModeInput = document.getElementById('batchModeInput');

            const todayStr = '{{ date("Y-m-d") }}';

            function updateBatchState() {
                const checked = Array.from(rmaChecks).filter(c => c.checked);
                const count = checked.length;
                if (batchCount) batchCount.textContent = count;
                if (batchDeleteCount) batchDeleteCount.textContent = count;

                if (count > 0) {
                    if (btnBatchDownload) btnBatchDownload.style.display = 'inline-flex';
                    if (btnDeselectAll) btnDeselectAll.style.display = 'inline-flex';
                } else {
                    if (btnBatchDownload) btnBatchDownload.style.display = 'none';
                    if (btnDeselectAll) btnDeselectAll.style.display = 'none';
                }

                if (btnBatchDelete) {
                    btnBatchDelete.style.display = count > 0 ? 'inline-flex' : 'none';
                    const canDeleteAll = checked.every(c => c.dataset.canDelete === '1');
                    btnBatchDelete.disabled = !canDeleteAll;
                    btnBatchDelete.title = canDeleteAll ? 'Hapus RMA yang dipilih' : 'Pilihan mencakup RMA yang bukan milik Anda';
                }

                if (checkAllHead) {
                    if (rmaChecks.length > 0 && count === rmaChecks.length) {
                        checkAllHead.checked = true;
                        checkAllHead.indeterminate = false;
                    } else if (count > 0) {
                        checkAllHead.checked = false;
                        checkAllHead.indeterminate = true;
                    } else {
                        checkAllHead.checked = false;
                        checkAllHead.indeterminate = false;
                    }
                }
            }

            if (checkAllHead) {
                checkAllHead.addEventListener('change', function() {
                    rmaChecks.forEach(c => { c.checked = checkAllHead.checked; });
                    updateBatchState();
                });
            }

            rmaChecks.forEach(c => {
                c.addEventListener('change', updateBatchState);
            });

            if (btnSelectAll) {
                btnSelectAll.addEventListener('click', function() {
                    rmaChecks.forEach(c => { c.checked = true; });
                    updateBatchState();
                });
            }

            if (btnDeselectAll) {
                btnDeselectAll.addEventListener('click', function() {
                    rmaChecks.forEach(c => { c.checked = false; });
                    updateBatchState();
                });
            }

            if (btnBatchDownload) {
                btnBatchDownload.addEventListener('click', function() {
                    const selectedIds = Array.from(rmaChecks).filter(c => c.checked).map(c => c.value);
                    if (selectedIds.length === 0) return;
                    if (batchModeInput) batchModeInput.value = '';
                    batchIdsInput.value = selectedIds.join(',');
                    batchDownloadForm.submit();
                });
            }

            if (btnBatchDelete) {
                btnBatchDelete.addEventListener('click', async function() {
                    const selected = Array.from(rmaChecks).filter(c => c.checked);
                    if (!selected.length || selected.some(c => c.dataset.canDelete !== '1')) return;

                    const result = await Swal.fire({
                        title: `Hapus ${selected.length} RMA?`,
                        html: 'Semua data dan foto material dari RMA yang dipilih akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, hapus semua',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    });

                    if (!result.isConfirmed || !batchDeleteForm || !batchDeleteIds) return;
                    batchDeleteIds.replaceChildren();
                    selected.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = checkbox.value;
                        batchDeleteIds.appendChild(input);
                    });
                    batchDeleteForm.submit();
                });
            }

            if (btnDownloadAllToday) {
                btnDownloadAllToday.addEventListener('click', function() {
                    if (batchModeInput) batchModeInput.value = 'hari_ini';
                    if (batchIdsInput) batchIdsInput.value = '';
                    batchDownloadForm.submit();
                });
            }

            // Notifikasi konsisten via showToast bawaan topbar
            @if ($filter === 'hari_ini')
                if (typeof showToast === 'function') {
                    @if ($rmas->total() > 0)
                        showToast('success', 'Menampilkan {{ $rmas->total() }} RMA hari ini ✨');
                    @else
                        showToast('info', 'Belum ada data RMA untuk hari ini');
                    @endif
                }

                if (rmaChecks.length > 0) {
                    rmaChecks.forEach(c => { c.checked = true; });
                    updateBatchState();
                }
            @endif
        });
    </script>

    <script>
        function confirmDeleteRma(url, rmaTitle) {
            Swal.fire({
                title: 'Hapus RMA?',
                html: `Apakah Anda yakin ingin menghapus <strong>"${rmaTitle}"</strong>?<br><small style="color:#64748b;">Semua foto material akan dihapus permanen.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <!-- FLATPICKR JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hiddenDateFrom = document.getElementById('hiddenDateFrom');
            const hiddenDateTo   = document.getElementById('hiddenDateTo');
            const clearBtn       = document.getElementById('btnClearDateRange');
            const rmaSearchForm  = document.getElementById('rmaSearchForm');

            // Nilai dari server berformat Y-m-d, sedangkan dateFormat picker "d M Y".
            // Jadi harus di-parse eksplisit (jangan kirim string mentah ke defaultDate).
            const defaultDates = [];
            @if(!empty($dateFrom))
                defaultDates.push(flatpickr.parseDate("{{ $dateFrom }}", "Y-m-d"));
            @endif
            @if(!empty($dateTo) && $dateTo !== $dateFrom)
                defaultDates.push(flatpickr.parseDate("{{ $dateTo }}", "Y-m-d"));
            @endif

            const fp = flatpickr("#rmaDateRangePicker", {
                mode: "range",
                dateFormat: "d M Y",
                locale: typeof flatpickr.l10ns.id !== 'undefined'
                    ? Object.assign({}, flatpickr.l10ns.id, { rangeSeparator: " s/d " })
                    : { rangeSeparator: " s/d " },
                defaultDate: defaultDates,
                showMonths: window.matchMedia('(min-width: 769px)').matches ? 2 : 1,
                position: window.matchMedia('(max-width: 768px)').matches ? 'auto center' : 'auto left',
                disableMobile: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        hiddenDateFrom.value = instance.formatDate(selectedDates[0], "Y-m-d");
                        hiddenDateTo.value   = instance.formatDate(selectedDates[1], "Y-m-d");
                        if (clearBtn) clearBtn.style.display = 'inline-flex';
                    } else if (selectedDates.length === 1) {
                        hiddenDateFrom.value = instance.formatDate(selectedDates[0], "Y-m-d");
                        hiddenDateTo.value   = instance.formatDate(selectedDates[0], "Y-m-d");
                        if (clearBtn) clearBtn.style.display = 'inline-flex';
                    } else {
                        hiddenDateFrom.value = "";
                        hiddenDateTo.value   = "";
                        if (clearBtn) clearBtn.style.display = 'none';
                    }
                },
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 1) {
                        hiddenDateFrom.value = instance.formatDate(selectedDates[0], "Y-m-d");
                        hiddenDateTo.value   = instance.formatDate(selectedDates[0], "Y-m-d");
                    }
                }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fp.clear();
                    hiddenDateFrom.value = "";
                    hiddenDateTo.value   = "";
                    clearBtn.style.display = 'none';
                });
            }

            if (rmaSearchForm) {
                rmaSearchForm.addEventListener('submit', function() {
                    // Jangan kirim param kosong di query string bila tidak ada tanggal yang dipilih
                    if (!hiddenDateFrom.value) hiddenDateFrom.disabled = true;
                    if (!hiddenDateTo.value) hiddenDateTo.disabled = true;
                });
            }
        });
    </script>

</body>

</html>
