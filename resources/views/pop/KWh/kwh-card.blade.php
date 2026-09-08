<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Card KWH - PLN Icon Plus</title>

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
                        <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="rectifier-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop . ': kWh'],
                            ]" />
                            <span class="rectifier-pop-sub">Kode: <strong>{{ $pop->kode_pop }}</strong> &middot;
                                {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }} &mdash; {{ $kwhs->count() }}
                                kWh</span>
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
                        @endphp
                        <div class="rectifier-card">
                            <div class="rectifier-card-header">
                                <div class="checklist-icon">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <div class="checklist-title">
                                    <h3>Checklist kWh</h3>
                                    <p>{{ $kwh->building }}</p>
                                </div>
                                <span class="rectifier-number">kWh #{{ $index + 1 }}</span>
                            </div>

                            <div class="rectifier-information">
                                <div class="equipment-info"><span class="info-label">Type POP</span>
                                    {{ $kwh->type_pop }}</div>
                                <div class="equipment-info"><span class="info-label">Phasa</span>
                                    {{ $kwh->jumlah_phasa }}</div>
                                <div class="equipment-info serial"><span class="info-label">Daya Listrik</span>
                                    {{ $kwh->daya_ps_gi_formatted }}</div>
                                <div class="equipment-info">
                                    <span class="info-label">Status Utilisasi</span>
                                    <span class="status-auto-badge {{ $kwh->status_badge_class }}">
                                        </i> {{ $kwh->status_utilisasi }}
                                        ({{ $kwh->persentase_utilisasi_formatted }})
                                    </span>
                                </div>
                            </div>

                            <div class="rectifier-meta">
                                <div class="meta-item">
                                    <i class="bi bi-person-fill"></i>
                                    <span>{{ $kwh->pic }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>Terakhir diperiksa pada
                                        {{ $kwh->tanggal_pemeriksaan->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>

                            <div class="rectifier-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>Data terakhir diupdate oleh {{ $lastUpdatedBy }} &middot;
                                    {{ $kwh->updated_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>

                            <div class="rectifier-card-footer">
                                <button type="button" class="btn-hapus"
                                    onclick="hapusKwh({{ $kwh->id }}, '{{ $kwh->building }}')">
                                    <i class="bi bi-trash3-fill"></i> Hapus
                                </button>
                                <a href="{{ route('kwh.detail', [$pop->id, $kwh->id]) }}" class="detail-button">
                                    <span>Detail</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align:center; padding: 60px 20px; color:#94a3b8;">
                            <i class="bi bi-inbox" style="font-size: 2.5rem;"></i>
                            <p style="margin-top: 12px;">Belum ada data kWh untuk POP ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Data kWH sudah berhasil kamu tambahkan!',
                text: @json(session('success')),
                confirmButtonColor: '#2563eb',
                timer: 2500,
                timerProgressBar: true
            });
        @endif

        const popId = {{ $pop->id }};
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const kwhDestroyUrlTemplate = "{{ route('kwh.destroy', [$pop->id, '__ID__']) }}";

        function hapusKwh(id, kwhName) {
            Swal.fire({
                title: 'Hapus kWh?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${kwhName}"</strong>?<br><small style="color: #64748b;">Data kWh dan foto dokumentasinya akan dihapus.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
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
                            timer: 1500
                        }).then(() => window.location.reload());
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message,
                            confirmButtonColor: '#dc2626'
                        });
                    });
            });
        }
    </script>
</body>

</html>
