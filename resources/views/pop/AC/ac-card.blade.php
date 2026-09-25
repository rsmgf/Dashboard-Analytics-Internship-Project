<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data AC - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-card.css'
    ])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="ac-content">
                <div class="ac-page-header">
                    <div class="ac-page-info" style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('pops.index') }}" class="ac-back-button" title="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="ac-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop_display . ': AC'],
                            ]" />
                        </div>
                    </div>

                    @can('acs.index.create')
                    <a href="{{ route('acs.create', $pop->id) }}" class="ac-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah AC
                    </a>
                    @endcan
                </div>

                @if ($acs->isEmpty())
                    <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                        <i class="bi bi-snow" style="font-size: 3rem; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 1rem; font-weight: 500;">Belum ada data AC untuk POP ini.</p>
                    </div>
                @else
                    <div class="ac-grid">
                        @foreach ($acs as $ac)
                            <div class="ac-card">
                                <div class="ac-card-header">
                                    <div class="ac-title-wrapper">
                                        <div class="ac-icon">
                                            <i class="bi bi-snow"></i>
                                        </div>
                                        <div class="card-title-text">
                                            <h3>Checklist AC</h3>
                                            <span>Data AC</span>
                                        </div>
                                    </div>
                                    <span class="ac-badge">{{ $ac->nomor_ac }}</span>
                                </div>

                                <div class="ac-information">
                                    <div class="ac-info-row">
                                        <span>Merk AC</span>
                                        <strong>{{ $ac->merk_ac }}</strong>
                                    </div>
                                    <div class="ac-info-row">
                                        <span>Tipe AC</span>
                                        <strong>{{ $ac->type_ac }}</strong>
                                    </div>
                                    <div class="ac-info-row">
                                        <span>PK</span>
                                        <strong>{{ $ac->pk }} PK</strong>
                                    </div>
                                    <div class="ac-info-row">
                                        <span>Status PM</span>
                                        <strong>{{ $ac->status_ac ?? 'Belum PM' }}</strong>
                                    </div>
                                </div>

                                <div class="ac-meta">
                                    <div class="ac-meta-item">
                                        <i class="bi bi-calendar-fill"></i>
                                        <span>{{ $ac->tanggal_terakhir_pm ? $ac->tanggal_terakhir_pm->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="ac-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $pop->kota_kabupaten }}</span>
                                    </div>
                                </div>

                                @if ($ac->diupdateOleh)
                                    <div class="ac-last-updated">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $ac->diupdateOleh->name }} · {{ $ac->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif

                                <div class="ac-card-footer">
                                    @can('acs.index.delete')
                                    <button class="ac-delete-button"
                                        onclick="confirmDelete('{{ route('acs.destroy', [$pop->id, $ac->id]) }}', '{{ $ac->nomor_ac }}')">
                                        <i class="bi bi-trash3-fill"></i>
                                        Hapus
                                    </button>
                                    @endcan
                                    <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="ac-detail-button">
                                        Detail
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(url, acName) {
            Swal.fire({
                title: 'Hapus AC?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${acName}"</strong>?<br><small style="color: #64748b;">Data AC dan riwayat PM akan dihapus secara permanen.</small>`,
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

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>