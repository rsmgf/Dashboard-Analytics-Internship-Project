<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-card.css'
    ])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="genset-content">
                <div class="genset-page-header">
                    <div class="genset-page-info" style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('pops.index') }}" class="genset-back-button" title="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="genset-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop_display . ': Genset'],
                            ]" />
                        </div>
                    </div>

                    @can('gensets.index.create')
                    <a href="{{ route('gensets.create', $pop->id) }}" class="genset-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Genset
                    </a>
                    @endcan
                </div>

                @if ($gensets->isEmpty())
                    <div class="genset-empty-state" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                        <i class="bi bi-cpu" style="font-size: 3rem; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 1rem; font-weight: 500;">Belum ada data Genset untuk POP ini.</p>
                    </div>
                @else
                    <div class="genset-grid">
                        @foreach ($gensets as $genset)
                            <div class="genset-card">
                                <div class="genset-card-header">
                                    <div class="genset-title-wrapper">
                                        <div class="genset-icon">
                                            <i class="bi bi-cpu-fill"></i>
                                        </div>
                                        <div class="card-title-text">
                                            <h3>Checklist Genset</h3>
                                            <span>Data Genset</span>
                                        </div>
                                    </div>
                                    <span class="genset-badge">
                                        {{ $genset->nomor_genset }}
                                    </span>
                                </div>

                                <div class="genset-information">
                                    <div class="genset-info-row">
                                        <span>Merk Genset</span>
                                        <strong>{{ $genset->merk_genset }}</strong>
                                    </div>
                                    <div class="genset-info-row">
                                        <span>Model</span>
                                        <strong>{{ $genset->model }}</strong>
                                    </div>
                                    <div class="genset-info-row">
                                        <span>Kapasitas (KVA)</span>
                                        <strong>{{ $genset->kapasitas_kva }} KVA</strong>
                                    </div>
                                    <div class="genset-info-row">
                                        <span>Status PM Genset</span>
                                        <strong>{{ $genset->status_genset ?? 'Belum PM' }}</strong>
                                    </div>
                                </div>

                                <div class="genset-meta">
                                    <div class="genset-meta-item">
                                        <i class="bi bi-calendar-fill"></i>
                                        <span>{{ $genset->tanggal_pm ? $genset->tanggal_pm->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="genset-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $pop->kota_kabupaten }}</span>
                                    </div>
                                </div>

                                @if ($genset->diupdateOleh)
                                    <div class="genset-last-updated">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $genset->diupdateOleh->name }} · {{ $genset->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif

                                <div class="genset-card-footer">
                                    @can('gensets.index.delete')
                                    <button class="genset-delete-button"
                                        onclick="confirmDelete('{{ route('gensets.destroy', [$pop->id, $genset->id]) }}', '{{ $genset->nomor_genset }}')">
                                        <i class="bi bi-trash3-fill"></i>
                                        Hapus
                                    </button>
                                    @endcan
                                    <a href="{{ route('gensets.show', [$pop->id, $genset->id]) }}" class="genset-detail-button">
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
        function confirmDelete(url, gensetName) {
            Swal.fire({
                title: 'Hapus Genset?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${gensetName}"</strong>?<br><small style="color: #64748b;">Data genset ini akan dihapus secara permanen dan tidak dapat dikembalikan.</small>`,
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