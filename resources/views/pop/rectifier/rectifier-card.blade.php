<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Card Rectifier - {{ $pop->nama_pop_display }} - PLN Icon Plus</title>

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
                        <a href="{{ route('pops.index') }}" class="back-button" title="Kembali ke List POP" aria-label="Kembali ke List POP">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="rectifier-header-text">
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop_display]
                            ]" />
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
                                    <p title="{{ $rectifier->nomor_recti ?? 'Data Rectifier' }}">{{ $rectifier->nomor_recti ?? 'Data Rectifier' }}</p>
                                </div>
                            </div>

                            @php
                                $utilVal = $rectifier->utilisasi;
                                if ($utilVal === null || $utilVal === '') {
                                    $uBadgeClass = 'status-unknown';
                                    $uDotClass   = 'dot-unknown';
                                    $uText       = '-';
                                } else {
                                    $numUtil = (float) $utilVal;
                                    if ($numUtil <= 50) {
                                        $uBadgeClass = 'status-safe';
                                        $uDotClass   = 'dot-safe';
                                        $uStatus     = 'SAFE';
                                    } elseif ($numUtil <= 70) {
                                        $uBadgeClass = 'status-warning';
                                        $uDotClass   = 'dot-warning';
                                        $uStatus     = 'WARNING';
                                    } else {
                                        $uBadgeClass = 'status-alert';
                                        $uDotClass   = 'dot-alert';
                                        $uStatus     = 'ALERT';
                                    }
                                    $uText = rtrim(rtrim(number_format($numUtil, 2, '.', ''), '0'), '.') . '% - ' . $uStatus;
                                }
                            @endphp

                            {{-- Information --}}
                            <div class="rectifier-information">
                                <div class="equipment-info">
                                    <span class="info-label">Merk</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $rectifier->merk ?? '-' }}</span>
                                </div>
                                <div class="equipment-info">
                                    <span class="info-label">Type</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $rectifier->type ?? '-' }}</span>
                                </div>
                                <div class="equipment-info">
                                    <span class="info-label">SN</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">{{ $rectifier->sn_rectifier ?? '-' }}</span>
                                </div>
                                <div class="equipment-info">
                                    <span class="info-label">Status Utilisasi</span>
                                    <span class="info-sep">:</span>
                                    <span class="data-value">
                                        <span class="status-badge {{ $uBadgeClass }}">
                                            {{ $uText }}
                                            <span class="status-dot {{ $uDotClass }}"></span>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            {{-- Meta: PIC & Tanggal Pemeriksaan --}}
                            <div class="rectifier-meta">
                                <div class="meta-item">
                                    <i class="bi bi-person-fill"></i>
                                    <span>{{ $rectifier->pic ?? '-' }}</span>
                                </div>

                                <div class="meta-item">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span>{{ $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            {{-- Last Updated --}}
                            <div class="rectifier-last-updated">
                                <i class="bi bi-clock-history"></i>
                                <span>
                                    @if($rectifier->diupdateOleh)
                                        {{ $rectifier->diupdateOleh->name }} &middot; {{ $rectifier->updated_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        Belum ada pembaruan
                                    @endif
                                </span>
                            </div>

                            {{-- Footer --}}
                            <div class="rectifier-card-footer">
                                @can('rectifiers.index.delete')
                                    <button type="button" class="btn-hapus"
                                        data-url="{{ route('rectifiers.destroy', [$pop->id, $rectifier->id]) }}"
                                        data-name="{{ $rectifier->nomor_recti ?? ($rectifier->merk . ' - ' . $rectifier->type) }}">
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
                        <div class="empty-rectifier">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada data Rectifier untuk POP ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Escape HTML agar nama dengan tanda kutip / karakter khusus aman ditampilkan
        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        // Event delegation: membaca data dari atribut data-* (aman untuk nama dengan tanda kutip)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-hapus');
            if (!btn) return;
            hapusRectifier(btn.dataset.url, btn.dataset.name);
        });

        function hapusRectifier(deleteUrl, rectifierName) {
            Swal.fire({
                title: 'Hapus Rectifier?',
                html: `Apakah Anda yakin ingin menghapus data <strong>"${escapeHtml(rectifierName)}"</strong>?<br><small style="color: #64748b;">Seluruh data modul dan output MCB di dalamnya akan ikut terhapus secara permanen.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                heightAuto: false,
                customClass: {
                    popup: 'swal-popup-custom',
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-custom',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading saat proses penghapusan
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        heightAuto: false,
                        customClass: {
                            popup: 'swal-popup-custom',
                            title: 'swal-title-custom',
                        },
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