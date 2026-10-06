<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kartu kWh - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite(['resources/css/sidebar.css', 'resources/css/card.css'])
</head>

<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rectifier-content">
                <div class="rectifier-page-header">
                    <div class="rectifier-page-info">
                        <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP" aria-label="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="rectifier-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->kode_pop . ': kWh'],
                            ]" />
                        </div>
                    </div>

                    <a href="{{ route('kwh.create', $pop->id) }}" class="btn-tambah-rectifier">
                        <i class="bi bi-plus-lg"></i> Tambah kWh
                    </a>
                </div>

                <div class="rectifier-grid">
                    @forelse ($kwhs as $index => $kwh)
                        @php
                            $firstPhoto = $kwh->photos->first();
                            $lastUpdatedBy = $kwh->diupdateOleh->name ?? '-';
                            $kwhLabel = $kwh->nomor_kwh ?? $kwh->nama_alias;
                        @endphp
                        <div class="rectifier-card">
                            <div class="rectifier-card-header">
                                <div class="checklist-icon kwh">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div class="checklist-title">
                                    <h3>Checklist kWh</h3>
                                    <p title="{{ $kwhLabel }}">{{ $kwhLabel }}</p>
                                </div>
                            </div>

                            <div class="rectifier-information">
                                <div class="equipment-info">
                                    <span class="info-label">Tipe POP</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $kwh->pop->tipe_pop ?? '-' }}</span>
                                </div>
                                <div class="equipment-info">
                                    <span class="info-label">Phasa</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $kwh->jumlah_phasa ?? '-' }}</span>
                                </div>
                                <div class="equipment-info serial">
                                    <span class="info-label">Daya Listrik</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $kwh->daya_ps_gi_formatted }}</span>
                                </div>
                                <div class="equipment-info">
                                    <span class="info-label">Status Utilisasi</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">
                                        <span class="status-auto-badge {{ $kwh->status_badge_class }}">
                                            {{ $kwh->status_utilisasi }}
                                            ({{ $kwh->persentase_utilisasi_formatted }})
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="rectifier-meta">
                                <div class="meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>{{ $kwh->tanggal_pemeriksaan ? $kwh->tanggal_pemeriksaan->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $pop->kota_kabupaten }}</span>
                                </div>
                            </div>

                            <div class="rectifier-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>{{ $lastUpdatedBy }} &middot; {{ $kwh->updated_at->locale('id')->translatedFormat('d M Y, H.i') }} WIB</span>
                            </div>

                            <div class="rectifier-card-footer">
                                <button type="button" class="btn-hapus"
                                    data-id="{{ $kwh->id }}"
                                    data-name="{{ $kwhLabel }}">
                                    <i class="bi bi-trash3-fill"></i> Hapus
                                </button>
                                <a href="{{ route('kwh.detail', [$pop->id, $kwh->id]) }}" class="detail-button">
                                    <span>Detail</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-rectifier">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada data kWh untuk POP ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const popId = {{ $pop->id }};
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const kwhDestroyUrlTemplate = "{{ route('kwh.destroy', [$pop->id, '__ID__']) }}";

        // Escape HTML agar nama dengan tanda kutip / karakter khusus aman ditampilkan
        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        // Event delegation: membaca data dari atribut data-*
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-hapus');
            if (!btn) return;
            hapusKwh(btn.dataset.id, btn.dataset.name);
        });

        const swalClasses = {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            htmlContainer: 'swal-html-custom',
            confirmButton: 'swal-btn-confirm',
            cancelButton: 'swal-btn-cancel',
        };

        function hapusKwh(id, kwhName) {
            Swal.fire({
                title: 'Hapus kWh?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${escapeHtml(kwhName)}"</strong>?<br><small style="color: #64748b;">Data kWh dan foto dokumentasinya akan dihapus.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                heightAuto: false,
                customClass: swalClasses
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(kwhDestroyUrlTemplate.replace('__ID__', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            heightAuto: false,
                            customClass: swalClasses
                        }).then(() => window.location.reload());
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message,
                            confirmButtonColor: '#dc2626',
                            heightAuto: false,
                            customClass: swalClasses
                        });
                    });
            });
        }
    </script>
</body>

</html>