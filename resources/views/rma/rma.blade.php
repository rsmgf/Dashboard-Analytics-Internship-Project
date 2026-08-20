<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form RMA - PLN Icon Plus</title>

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
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <h1>Form RMA</h1>
                            <p>Return Material Authorization</p>
                        </div>
                    </div>

                    <!-- BUTTON TAMBAH RMA -->
                    <a href="{{ route('rma.create') }}" class="btn-tambah">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah RMA</span>
                    </a>
                </div>

                <!-- ALERT BANNER -->
                <div class="alert-info-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>Hanya form yang Anda isi yang bisa dilihat pada halaman ini</span>
                </div>

                <!-- TABLE CARD CONTAINER -->
                <div class="table-card">

                    <!-- JUDUL RIWAYAT RMA -->
                    <div class="history-header">
                        <h2>Riwayat RMA</h2>
                    </div>

                    <!-- SEARCH & FILTER CONTROLS -->
                    <div class="table-controls">
                        <div class="search-wrapper">
                            <input type="text" placeholder="Cari No. RMA">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="filter-wrapper">
                            <input type="text" placeholder="Filter RMA">
                            <i class="bi bi-calendar"></i>
                        </div>
                    </div>

                    <!-- TABLE CONTENT -->
                    <div class="table-responsive">
                        <table class="rma-table">
                            @php
                            // Tentukan arah sort kebalikan untuk toggle tombol
                            $nextDirection = ($sort === 'tanggal' && $direction === 'asc') ? 'desc' : 'asc';
                            @endphp

                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}" 
                                        style="text-decoration: none; color: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                            No. RMA
                                            @if($sort === 'id')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <!-- Tombol Toggle ASC / DESC untuk Tanggal Pengisian -->
                                        <a href="{{ route('rma', array_merge(request()->query(), ['sort' => 'tanggal', 'direction' => $nextDirection])) }}" 
                                        style="text-decoration: none; color: inherit; display: inline-flex; align-items: center; gap: 4px;">
                                            Tanggal Pengisian
                                            @if($sort === 'tanggal')
                                                <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up" style="font-size: 11px; opacity: 0.5;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>ID POP</th>
                                    <th>Merk/Type</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rmas as $rma)
                                    <tr>
                                        <td><strong>{{ $rma->id }}</strong></td>
                                        <td>
                                            <strong>{{ $rma->created_at->format('d-M Y') }}</strong>
                                            <span class="text-sub">{{ $rma->created_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td>
                                            {{ $rma->pop->name ?? '-' }}
                                        </td>
                                        <td>
                                            <strong>{{ $rma->merk ?? '-' }}</strong>
                                            <span class="text-sub">{{ $rma->type ?? 'Rectifier' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('rma.pdf', $rma->id) }}" class="btn-lihat">Lihat</a>
                                            <a href="{{ route('rma.download', $rma->id) }}" class="btn-cetak"
                                                target="_blank">
                                                <i class="bi bi-printer-fill"></i> Cetak
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data RMA
                                            yang diisi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="pagination-footer">
                        <div>Menampilkan data RMA</div>
                        <div class="pagination-controls">
                            {{-- {{ $rma->links() }} --}}
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    @if(session('download_pdf_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger download otomatis lewat iframe tersembunyi
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ route('rma.download', session('download_pdf_id')) }}";
            document.body.appendChild(iframe);
        });
    </script>
    @endif

</body>

</html>
