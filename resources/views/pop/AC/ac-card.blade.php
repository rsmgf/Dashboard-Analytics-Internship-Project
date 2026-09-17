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
                    <div class="ac-page-left">
                        <button class="ac-back-button" onclick="window.history.back()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <div class="ac-header-text">
                            <div class="ac-breadcrumb">
                                <span>POP</span>
                                <i class="bi bi-chevron-right"></i>
                                <strong>AC</strong>
                            </div>
                            <span class="ac-page-subtitle">
                                Kode: <strong>{{ $pop->kode_pop }}</strong>
                                &nbsp;·&nbsp;
                                {{ $pop->kota_kabupaten }}
                                &nbsp;—&nbsp;
                                {{ $acs->count() }} AC
                            </span>
                        </div>
                    </div>

                    @can('acs.index.create')
                    <a href="{{ route('acs.create', $pop->id) }}" class="ac-add-button">
                        <i class="bi bi-plus-lg"></i>
                        Tambah AC
                    </a>
                    @endcan
                </div>

                @if (session('success'))
                    <div style="margin-bottom: 16px; padding: 12px 16px; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46; font-size: 0.875rem;">
                        <i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($acs->isEmpty())
                    <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                        <i class="bi bi-snow" style="font-size: 3rem; display: block; margin-bottom: 12px;"></i>
                        <p style="font-size: 1rem; font-weight: 500;">Belum ada data AC untuk POP ini.</p>
                        @can('acs.index.create')
                        <a href="{{ route('acs.create', $pop->id) }}" class="ac-add-button" style="display: inline-flex; margin-top: 16px;">
                            <i class="bi bi-plus-lg"></i> Tambah AC Pertama
                        </a>
                        @endcan
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
                                        onclick="confirmDelete({{ $ac->id }}, '{{ route('acs.destroy', [$pop->id, $ac->id]) }}')">
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

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:28px 32px; max-width:420px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:2.5rem; color:#ef4444; display:block; margin-bottom:12px;"></i>
            <h3 style="margin:0 0 8px; font-size:1.1rem; font-weight:600; color:#1e293b;">Hapus AC?</h3>
            <p style="margin:0 0 24px; color:#64748b; font-size:0.9rem;">Data AC ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
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
            document.getElementById('deleteModal').style.display = 'flex';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>

</html>