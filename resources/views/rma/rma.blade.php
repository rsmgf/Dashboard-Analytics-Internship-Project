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
                                <button type="button" id="btnSelectToday" class="btn-pill-item" title="Pilih RMA yang dibuat hari ini">
                                    <i class="bi bi-calendar2-check-fill icon-select-today"></i>
                                    <span>Pilih Hari Ini</span>
                                </button>
                                <button type="button" id="btnDeselectAll" class="btn-pill-item btn-deselect" style="display: none;" title="Batalkan semua pilihan">
                                    <i class="bi bi-x-circle-fill icon-deselect"></i>
                                    <span>Batal</span>
                                </button>
                            </div>

                            <button type="button" id="btnBatchDownload" class="btn-batch-download-modern" style="display: none;">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                <span>Download Terpilih <span class="badge-count"><span id="batchCount">0</span> PDF</span></span>
                            </button>
                        </div>
                    </div>

                    
                    <!-- SEARCH CONTROL -->
                    <form method="GET" action="{{ route('rma') }}" class="table-controls">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        @if(request('tampil'))
                            <input type="hidden" name="tampil" value="{{ request('tampil') }}">
                        @endif
                        <div class="search-wrapper">
                            <input type="text" name="search" placeholder="Cari No. RMA, Judul, Perangkat, Lokasi..." value="{{ request('search') }}">
                            <button type="submit" class="search-btn" title="Cari">
                                <i class="bi bi-search"></i>
                            </button>
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
                                        <td><strong>#{{ $rma->id }}</strong></td>
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
                        <div>Menampilkan {{ $rmas->firstItem() ?? 0 }} - {{ $rmas->lastItem() ?? 0 }} dari <strong>{{ $rmas->total() }}</strong> data RMA
                            @if ($isSuperAdminOrManager)
                                {{ $tampil === 'milik_saya' ? '(milik Anda)' : '(semua pengguna)' }}
                            @endif
                        </div>
                        <div class="pagination-controls">
                            {{ $rmas->links('vendor.pagination.custom') }}
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
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAllHead = document.getElementById('checkAllHead');
            const rmaChecks = document.querySelectorAll('.rma-check');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnSelectToday = document.getElementById('btnSelectToday');
            const btnDeselectAll = document.getElementById('btnDeselectAll');
            const btnBatchDownload = document.getElementById('btnBatchDownload');
            const batchCount = document.getElementById('batchCount');
            const batchDownloadForm = document.getElementById('batchDownloadForm');
            const batchIdsInput = document.getElementById('batchIdsInput');

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

            if (btnSelectToday) {
                btnSelectToday.addEventListener('click', function() {
                    let foundCount = 0;
                    rmaChecks.forEach(c => {
                        const isToday = (c.dataset.created === todayStr || c.dataset.tanggal === todayStr);
                        if (isToday) {
                            c.checked = true;
                            foundCount++;
                        } else {
                            c.checked = false;
                        }
                    });
                    updateBatchState();
                    if (foundCount === 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Tidak ada RMA tanggal hari ini di halaman ini',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `${foundCount} RMA hari ini berhasil dipilih ✨`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
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
                    batchIdsInput.value = selectedIds.join(',');
                    batchDownloadForm.submit();
                });
            }
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
