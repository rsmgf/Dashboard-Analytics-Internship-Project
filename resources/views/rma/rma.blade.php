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
                    <div class="alert-info-custom" style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border-color: #60a5fa;">
                        <i class="bi bi-shield-check" style="color: #2563eb;"></i>
                        <span style="color: #1e40af;">
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
                    <div class="history-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <h2 style="margin: 0;">Riwayat RMA</h2>
                            @if ($isSuperAdminOrManager)
                                <!-- Filter Toggle Semua / Milik Saya -->
                                <div style="display: inline-flex; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; font-size: 12px; font-weight: 600;">
                                    <a href="{{ route('rma', array_merge(request()->query(), ['tampil' => 'semua'])) }}"
                                        style="padding: 5px 13px; text-decoration: none; transition: 0.15s;
                                        {{ $tampil === 'semua' ? 'background: #2563eb; color: white;' : 'background: #f8fafc; color: #64748b;' }}">
                                        Semua
                                    </a>
                                    <a href="{{ route('rma', array_merge(request()->query(), ['tampil' => 'milik_saya'])) }}"
                                        style="padding: 5px 13px; text-decoration: none; transition: 0.15s; border-left: 1px solid #e2e8f0;
                                        {{ $tampil === 'milik_saya' ? 'background: #2563eb; color: white;' : 'background: #f8fafc; color: #64748b;' }}">
                                        Milik Saya
                                    </a>
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
                                    title="{{ $isTodayFilter ? 'Klik untuk tampilkan semua tanggal' : 'Filter hanya data RMA Hari Ini' }}"
                                    style="text-decoration: none;">
                                    <i class="bi bi-calendar2-check-fill icon-select-today"></i>
                                    <span>{{ $isTodayFilter ? 'Hari Ini (Aktif)' : 'Hari Ini' }}</span>
                                    @if ($isTodayFilter)
                                        <i class="bi bi-x-circle-fill" style="margin-left: 2px; font-size: 11px; color: #ef4444;" title="Hapus filter hari ini"></i>
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

                            @if ($filter === 'hari_ini' && $rmas->total() > 0)
                                <button type="button" id="btnDownloadAllToday" class="btn-batch-download-modern" style="background: linear-gradient(135deg, #059669, #10b981);" title="Download seluruh {{ $rmas->total() }} RMA hari ini dalam satu file ZIP">
                                    <i class="bi bi-file-earmark-zip-fill"></i>
                                    <span>Download Semua Hari Ini <span class="badge-count" style="background: rgba(255,255,255,0.3); color:#fff;">{{ $rmas->total() }} PDF</span></span>
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
                            <input type="text" name="search" placeholder="Cari No. RMA, Judul, Perangkat, Lokasi..." value="{{ request('search') }}">
                            <button type="submit" class="search-btn" title="Cari">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        {{-- Date Range Filter --}}
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-top:8px;">
                            <label style="font-size:0.8rem; color:#64748b; font-weight:600; white-space:nowrap;">
                                <i class="bi bi-calendar-range" style="color:#3b82f6;"></i> Rentang Tanggal:
                            </label>
                            <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                <input type="date" name="date_from" id="rmaDateFrom"
                                    value="{{ $dateFrom ?? '' }}"
                                    style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:0.82rem; color:#334155; background:#fff; cursor:pointer;"
                                    onchange="document.getElementById('rmaSearchForm').submit()"
                                    title="Dari Tanggal">
                                <span style="color:#94a3b8; font-size:0.8rem;">s/d</span>
                                <input type="date" name="date_to" id="rmaDateTo"
                                    value="{{ $dateTo ?? '' }}"
                                    style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:0.82rem; color:#334155; background:#fff; cursor:pointer;"
                                    onchange="document.getElementById('rmaSearchForm').submit()"
                                    title="Sampai Tanggal">
                                @if($dateFrom || $dateTo)
                                    <a href="{{ route('rma', array_merge(array_filter(request()->except(['date_from','date_to','page'])), [])) }}"
                                        style="display:inline-flex; align-items:center; gap:4px; font-size:0.78rem; color:#ef4444; text-decoration:none; padding:5px 10px; border:1px solid #fca5a5; border-radius:6px; background:#fff5f5; white-space:nowrap;"
                                        title="Hapus filter tanggal">
                                        <i class="bi bi-x-circle-fill"></i> Hapus Filter
                                    </a>
                                @endif
                            </div>
                            @if($dateFrom || $dateTo)
                                <span style="font-size:0.75rem; color:#3b82f6; background:#eff6ff; padding:3px 10px; border-radius:999px; white-space:nowrap;">
                                    <i class="bi bi-funnel-fill"></i>
                                    {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : '...' }}
                                    &mdash;
                                    {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : '...' }}
                                </span>
                            @endif
                        </div>
                    </form>

                    <!-- TABLE CONTENT -->
                    <div class="table-responsive">
                        <table class="rma-table">
                            @php
                                // Tentukan arah sort kebalikan untuk toggle tombol
                                $nextDirection = $sort === 'tanggal' && $direction === 'asc' ? 'desc' : 'asc';
                            @endphp

                            <thead>
                                <tr>
                                    <th style="width: 42px; text-align: center;">
                                        <input type="checkbox" id="checkAllHead" style="width: 16px; height: 16px; cursor: pointer;" title="Pilih Semua di Halaman Ini">
                                    </th>
                                    <th>
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'id', 'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc']))}}"
                                            style="text-decoration: none; color: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                            No. RMA
                                            @if ($sort === 'id')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Judul / Nama Dokumen</th>
                                    <th>
                                        <!-- Tombol Toggle ASC / DESC untuk Tanggal Pengisian -->
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'tanggal', 'direction' => $nextDirection]))}}"
                                            style="text-decoration: none; color: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                            Tanggal Pengisian
                                            @if ($sort === 'tanggal')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up"
                                                    style="font-size: 11px; opacity: 0.5;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>ID POP</th>
                                    <th>Merk/Type</th>
                                    @if ($isSuperAdminOrManager)
                                        <th>Dibuat Oleh</th>
                                    @endif
                                    <th class="text-center" style="width: 250px; min-width: 250px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rmas as $rma)
                                    @php
                                        $createdDate = $rma->created_at ? $rma->created_at->format('Y-m-d') : '';
                                        $tglDoc = $rma->tanggal ? $rma->tanggal->format('Y-m-d') : '';
                                        $judulRma = $rma->judul_rma ?? ('RMA #' . $rma->id);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="rma-check" value="{{ $rma->id }}" data-created="{{ $createdDate }}" data-tanggal="{{ $tglDoc }}" style="width: 16px; height: 16px; cursor: pointer;">
                                        </td>
                                        <td><strong>#{{ $rmas->firstItem() + $loop->index }}</strong></td>
                                        <td>
                                            <strong style="display: block; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $judulRma }}">{{ $judulRma }}</strong>
                                            <span class="text-sub">{{ $rma->so_po ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $rma->created_at->format('d-M Y') }}</strong>
                                            <span class="text-sub">{{ $rma->created_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td>
                                            {{ $rma->lokasi_asal ?? '-' }}
                                        </td>
                                        <td>
                                            <strong>{{ $rma->merk ?? '-' }}</strong>
                                            <span class="text-sub">{{ $rma->type ?? '-' }}</span>
                                        </td>
                                        @if ($isSuperAdminOrManager)
                                            <td>
                                                @if ($rma->user)
                                                    <span style="display: flex; align-items: center; gap: 5px;">
                                                        <span style="width: 26px; height: 26px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                            {{ strtoupper(substr($rma->user->name, 0, 1)) }}
                                                        </span>
                                                        <span style="font-size: 12px;">{{ $rma->user->name }}</span>
                                                    </span>
                                                @elseif ($rma->nama_pemohon)
                                                    <span style="font-size: 12px; color: #64748b;">{{ $rma->nama_pemohon }}</span>
                                                @else
                                                    <span class="text-sub">—</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('rma.pdf', $rma->id) }}" class="btn-lihat" target="_blank"
                                                    title="Lihat Dokumen RMA" aria-label="Lihat PDF RMA {{ $rma->so_po }}">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                                <a href="{{ route('rma.download', $rma->id) }}" class="btn-download"
                                                    title="Download Dokumen RMA" aria-label="Download PDF RMA {{ $rma->so_po }}">
                                                    <i class="bi bi-download"></i> Download
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
                                                    @php
                                                        $canDelete = $isSuperAdminOrManager
                                                            || ($rma->user_id && $rma->user_id === auth()->id())
                                                            || (!$rma->user_id && $rma->nama_pemohon === auth()->user()->name);
                                                    @endphp
                                                    @if ($canDelete)
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
                                    <tr>
                                        <td colspan="{{ $isSuperAdminOrManager ? 8 : 7 }}" class="text-center py-4 text-gray-500">Belum ada data RMA.</td>
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
                                <span style="color: #94a3b8;">{{ $tampil === 'milik_saya' ? '(milik Anda)' : '(semua pengguna)' }}</span>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAllHead = document.getElementById('checkAllHead');
            const rmaChecks = document.querySelectorAll('.rma-check');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnDeselectAll = document.getElementById('btnDeselectAll');
            const btnBatchDownload = document.getElementById('btnBatchDownload');
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

                if (count > 0) {
                    if (btnBatchDownload) btnBatchDownload.style.display = 'inline-flex';
                    if (btnDeselectAll) btnDeselectAll.style.display = 'inline-flex';
                } else {
                    if (btnBatchDownload) btnBatchDownload.style.display = 'none';
                    if (btnDeselectAll) btnDeselectAll.style.display = 'none';
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


</body>

</html>
