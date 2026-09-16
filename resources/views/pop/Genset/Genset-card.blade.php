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
                    <div class="genset-page-left">
                        <button class="genset-back-button" onclick="window.history.back()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="genset-header-text">
                            <div class="genset-breadcrumb">
                                <span>POP</span>
                                <i class="bi bi-chevron-right"></i>
                                <strong>Genset</strong>
                            </div>
                            <span class="genset-page-subtitle">
                                Kode: <strong>{{ $pop->kode_pop }}</strong>
                                &nbsp;·&nbsp;
                                {{ $pop->kota_kabupaten }}
                                &nbsp;—&nbsp;
                                {{ $gensets->count() }} Genset
                            </span>
                        </div>
                    </div>

                    @can('gensets.index.create')
                    <a href="{{ route('gensets.create', $pop->id) }}" class="genset-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Genset
                    </a>
                    @endcan
                </div>

                @if (session('success'))
                    <div class="alert alert-success" style="margin-bottom: 16px; padding: 12px 16px; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46; font-size: 0.875rem;">
                        <i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($gensets->isEmpty())
                    <div class="genset-empty-state" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                        <i class="bi bi-cpu" style="font-size: 3rem; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 1rem; font-weight: 500;">Belum ada data Genset untuk POP ini.</p>
                        @can('gensets.index.create')
                        <a href="{{ route('gensets.create', $pop->id) }}" class="genset-add-button" style="display: inline-flex; margin-top: 16px;">
                            <i class="bi bi-plus-lg"></i> Tambah Genset Pertama
                        </a>
                        @endcan
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
                                        onclick="confirmDelete({{ $genset->id }}, '{{ route('gensets.destroy', [$pop->id, $genset->id]) }}')">
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

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:28px 32px; max-width:420px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:2.5rem; color:#ef4444; display:block; margin-bottom:12px;"></i>
            <h3 style="margin:0 0 8px; font-size:1.1rem; font-weight:600; color:#1e293b;">Hapus Genset?</h3>
            <p style="margin:0 0 24px; color:#64748b; font-size:0.9rem;">Data genset ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
            <div style="display:flex; gap:12px; justify-content:center;">
                <button onclick="closeDeleteModal()" style="padding:10px 24px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; cursor:pointer; font-size:0.875rem; color:#64748b; font-weight:500;">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding:10px 24px; border:none; border-radius:8px; background:#ef4444; color:#fff; cursor:pointer; font-size:0.875rem; font-weight:600;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, url) {
            document.getElementById('deleteForm').action = url;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>

</html>