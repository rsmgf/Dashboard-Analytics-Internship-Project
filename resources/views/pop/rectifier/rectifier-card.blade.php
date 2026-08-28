<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Rectifier - {{ $pop->nama_pop }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite(['resources/css/sidebar.css', 'resources/css/card.css'])
</head>

<body>
    <div class="app-container">

        {{-- SIDEBAR COMPONENT --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            {{-- TOPBAR COMPONENT --}}
            <x-topbar />

            <div class="rectifier-content">
                {{-- Header: Back Button + Breadcrumb As Title + Tombol Tambah --}}
                <div class="rectifier-page-header">
                    <div class="rectifier-page-info">
                        <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="rectifier-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop]
                            ]" />
                            <span class="rectifier-pop-sub">Kode: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi }} &mdash;
                                {{ $rectifiers->count() }} Rectifier</span>
                        </div>
                    </div>

                    @can('rectifiers.index.create')
                        <a href="{{ route('rectifiers.create', $pop->id) }}" class="btn-tambah-rectifier">
                            <i class="bi bi-plus-lg"></i> Tambah Rectifier
                        </a>
                    @endcan
                </div>

                {{-- Rectifier Grid --}}
                <div class="rectifier-grid">
                    @forelse ($rectifiers as $rectifier)
                        <div class="rectifier-card">
                            {{-- Header --}}
                            <div class="rectifier-card-header">
                                <div class="checklist-icon">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </div>

                                <div class="checklist-title">
                                    <h3>Checklist Rectifier</h3>
                                    <p>{{ $rectifier->nama_alias ?? 'Data Rectifier' }}</p>
                                </div>

                                <span class="rectifier-number">
                                    Rectifier #{{ $loop->iteration }}
                                </span>
                            </div>

                            {{-- Information --}}
                            <div class="rectifier-information">
                                <div class="equipment-info">{{ $rectifier->merk ?? '-' }}</div>
                                <div class="equipment-info">{{ $rectifier->type ?? '-' }}</div>
                                <div class="equipment-info serial">{{ $rectifier->sn_rectifier ?? '-' }}</div>
                            </div>

                            {{-- Meta: PIC & Tanggal Pemeriksaan --}}
                            <div class="rectifier-meta">
                                <div class="meta-item">
                                    <i class="bi bi-person-fill"></i>
                                    <span>{{ $rectifier->pic ?? '-' }}</span>
                                </div>

                                <div class="meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>{{ $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->format('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            {{-- Last Updated --}}
                            <div class="rectifier-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>
                                    @if($rectifier->diupdateOleh)
                                        {{ $rectifier->diupdateOleh->name }} &middot; {{ $rectifier->updated_at->format('d M Y, H:i') }}
                                    @else
                                        Belum ada pembaruan
                                    @endif
                                </span>
                            </div>

                            {{-- Footer --}}
                            <div class="rectifier-card-footer">
                                @can('rectifiers.index.delete')
                                    <button type="button" class="btn-hapus"
                                        onclick="hapusRectifier('{{ route('rectifiers.destroy', [$pop->id, $rectifier->id]) }}', '{{ addslashes($rectifier->nama_alias ?? ($rectifier->merk . ' - ' . $rectifier->type)) }}')">
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </button>
                                @endcan
                                <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}" class="detail-button">
                                    <span>Detail</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column:1/-1; text-align:center; padding:40px; color:#64748b;">
                            <i class="bi bi-inbox" style="font-size:2rem; display:block; margin-bottom:12px;"></i>
                            Belum ada data Rectifier untuk POP ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif

        function hapusRectifier(deleteUrl, rectifierName) {
            Swal.fire({
                title: 'Hapus Rectifier?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${rectifierName}"</strong>?<br><small style="color: #64748b;">Seluruh data modul dan output MCB di dalamnya akan ikut terhapus secara permanen.</small>`,
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
                    // Tampilkan loading saat proses penghapusan
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Buat dan submit form DELETE secara dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>

