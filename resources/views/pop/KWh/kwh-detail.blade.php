<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail kWh - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/sidebar.css', 'resources/css/kwh-detail.css'])
</head>

<body>

    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="kwh-detail-content">
                <div class="detail-header-bar">
                    <div class="header-left-group">
                        <a href="{{ route('kwh.card', $pop->id) }}" class="detail-back" title="Kembali ke Daftar kWh">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-title-wrapper">
                            <div class="title-with-badge">
                                <x-breadcrumb :items="[
                                    ['label' => 'POP', 'route' => 'pops.index'],
                                    [
                                        'label' => $pop->nama_pop_display . ': kWh',
                                        'route' => 'kwh.card',
                                        'params' => ['pop' => $pop->id],
                                    ],
                                    ['label' => $kwh->nomor_kwh ?? $kwh->nama_alias],
                                ]" />
                                <span class="device-badge">{{ $kwh->jumlah_phasa }} &bull;
                                    {{ $kwh->daya_ps_gi_formatted }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('kwh.edit', [$pop->id, $kwh->id]) }}" class="btn-edit">
                        <i class="bi bi-pencil-fill"></i>
                        Edit Form
                    </a>
                </div>

                <div class="alert-info-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>
                        Terakhir diperbarui:
                        <strong>
                            @if($kwh->diupdateOleh)
                                {{ $kwh->diupdateOleh->name }} &middot; {{ $kwh->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                {{ $kwh->updated_at ? $kwh->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum ada data pembaruan' }}
                            @endif
                        </strong>
                    </span>
                </div>

                <section class="detail-card information-card">
                    <div class="detail-card-title">
                        <i class="bi bi-info-circle-fill"></i> General Information
                    </div>

                    <div class="info-box-grid">
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">Nomor kWh</span>
                                <span class="info-box-value">{{ $kwh->nomor_kwh ?? $kwh->nama_alias }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">POP</span>
                                <span class="info-box-value">{{ $pop->nama_pop_display }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-calendar-check-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">Tanggal Pemeriksaan</span>
                                <span class="info-box-value">{{ $kwh->tanggal_pemeriksaan->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-building-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">Building / Jenis Bangunan</span>
                                <span class="info-box-value">{{ $pop->jenis_bangunan ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-diagram-3-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">Type POP</span>
                                <span class="info-box-value">{{ $pop->tipe_pop ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-person-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">PIC / Petugas</span>
                                <span class="info-box-value">{{ $kwh->pic }}</span>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-icon"><i class="bi bi-person-vcard-fill"></i></div>
                            <div class="info-box-text">
                                <span class="info-box-label">ID Pelanggan Listrik</span>
                                <span class="info-box-value">{{ $kwh->id_customer_pln }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="detail-card checklist-card">
                    <div class="section-heading">
                        <span><i class="bi bi-clipboard-check-fill"></i> Checklist & Pengukuran Phasa</span>
                        <small>Main AC Power Information (Panel kWh Meter)</small>
                    </div>

                    <div class="table-wrapper">
                        <table class="kwh-table">
                            <tbody>
                                <tr>
                                    <th class="label-column">Daya (PS GI)</th>
                                    <td class="value-column" colspan="2">
                                        <strong>{{ $kwh->daya_ps_gi_formatted }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">MCB Utama</th>
                                    <td class="value-column" colspan="2"><strong>{{ $kwh->mcb_utama }} A</strong></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Jumlah Phasa</th>
                                    <td class="value-column" colspan="2"><span
                                            class="badge-phasa">{{ $kwh->jumlah_phasa }}</span></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Pengukuran Phasa</th>
                                    <td class="value-column nested-cell" colspan="2">
                                        <table class="sub-measurement-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%;">Tegangan (Vac)</th>
                                                    <th style="width: 25%;">Nilai</th>
                                                    <th style="width: 25%;">Arus Beban (A)</th>
                                                    <th style="width: 25%;">Nilai</th>
                                                </tr>
                                            </thead>
                                             <tbody>
                                                <tr>
                                                    <td><strong>R - N</strong></td>
                                                    <td><code>{{ $kwh->teg_rn }} Vac</code></td>
                                                    <td><strong>R</strong></td>
                                                    <td><code>{{ $kwh->arus_r }} A</code></td>
                                                </tr>
                                                @if ($kwh->jumlah_phasa === '3 Phasa')
                                                <tr>
                                                    <td><strong>S - N</strong></td>
                                                    <td><code>{{ $kwh->teg_sn }} Vac</code></td>
                                                    <td><strong>S</strong></td>
                                                    <td><code>{{ $kwh->arus_s }} A</code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>T - N</strong></td>
                                                    <td><code>{{ $kwh->teg_tn }} Vac</code></td>
                                                    <td><strong>T</strong></td>
                                                    <td><code>{{ $kwh->arus_t }} A</code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>R - S</strong></td>
                                                    <td><code>{{ $kwh->teg_rs }} Vac</code></td>
                                                    <td class="cell-empty">&mdash;</td>
                                                    <td class="cell-empty">&mdash;</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>S - T</strong></td>
                                                    <td><code>{{ $kwh->teg_st }} Vac</code></td>
                                                    <td class="cell-empty">&mdash;</td>
                                                    <td class="cell-empty">&mdash;</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>R - T</strong></td>
                                                    <td><code>{{ $kwh->teg_rt }} Vac</code></td>
                                                    <td class="cell-empty">&mdash;</td>
                                                    <td class="cell-empty">&mdash;</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>N - G</strong></td>
                                                    <td><code>{{ $kwh->teg_ng }} Vac</code></td>
                                                    <td class="cell-empty">&mdash;</td>
                                                    <td class="cell-empty">&mdash;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Arrester</th>
                                    <td class="value-column" colspan="2">
                                        <div class="arrester-row">
                                            <div>Status: <span
                                                    class="badge-status-ok">{{ $kwh->keberadaan_arrester }}</span>
                                            </div>
                                            <div>Merk / Type: <strong>{{ $kwh->merk_type_arrester ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Kabel Output kWh</th>
                                    <td class="value-column nested-cell" colspan="2">
                                        <div class="cable-grid-container">
                                            <div class="cable-block">
                                                <div class="cable-block-title">Warna Kabel</div>
                                                <div class="cable-row"><span class="cable-code">R</span>
                                                    {{ $kwh->warna_r }}</div>
                                                <div class="cable-row"><span class="cable-code">S</span>
                                                    {{ $kwh->warna_s }}</div>
                                                <div class="cable-row"><span class="cable-code">T</span>
                                                    {{ $kwh->warna_t }}</div>
                                                <div class="cable-row"><span class="cable-code">N</span>
                                                    {{ $kwh->warna_n }}</div>
                                                <div class="cable-row"><span class="cable-code">G</span>
                                                    {{ $kwh->warna_g }}</div>
                                            </div>
                                            <div class="cable-block">
                                                <div class="cable-block-title">Ukuran Kabel</div>
                                                <div class="cable-row"><span class="cable-code">R</span>
                                                    {{ $kwh->ukuran_r }}</div>
                                                <div class="cable-row"><span class="cable-code">S</span>
                                                    {{ $kwh->ukuran_s }}</div>
                                                <div class="cable-row"><span class="cable-code">T</span>
                                                    {{ $kwh->ukuran_t }}</div>
                                                <div class="cable-row"><span class="cable-code">N</span>
                                                    {{ $kwh->ukuran_n }}</div>
                                                <div class="cable-row"><span class="cable-code">G</span>
                                                    {{ $kwh->ukuran_g }}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Total Daya Terpakai</th>
                                    <td class="value-column" colspan="2">
                                        <strong>{{ $kwh->total_daya_terpakai_formatted }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Total Beban</th>
                                    <td class="value-column" colspan="2">
                                        <strong>{{ $kwh->total_beban_formatted }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Persentase Utilisasi</th>
                                    <td class="value-column" colspan="2">
                                        <strong>{{ $kwh->persentase_utilisasi_formatted }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Status Utilisasi</th>
                                    <td class="value-column" colspan="2">
                                        <span class="status-auto-badge {{ $kwh->status_badge_class }}">
                                            <i class="bi {{ $kwh->status_icon }}"></i> {{ $kwh->status_utilisasi }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="detail-card photo-card">
                    <div class="section-heading">
                        <span><i class="bi bi-camera-fill"></i> Photo Dokumentasi kWh</span>
                        <small>{{ $kwh->photos->count() }} Foto Terlampir</small>
                    </div>

                    <div class="photo-grid">
                        @forelse ($kwh->photos as $photo)
                            <div class="photo-item" role="button" tabindex="0"
                                onclick="openKwhLightbox({{ $loop->index }})"
                                onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openKwhLightbox({{ $loop->index }});}">
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->keterangan }}"
                                    onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text={{ urlencode($photo->keterangan) }}'">
                                <span>{{ $photo->keterangan }}</span>
                            </div>
                        @empty
                            <p class="photo-empty-msg">Belum ada foto dokumentasi.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Lightbox Gallery Modal -->
    <div id="kwhLightboxModal" class="kwh-lightbox-modal" onclick="handleLightboxBackdropClick(event)">
        <div class="kwh-lightbox-wrapper">
            <button type="button" class="kwh-lightbox-close" onclick="closeKwhLightbox()" title="Tutup (Esc)">
                <i class="bi bi-x-lg"></i>
            </button>

            @if($kwh->photos->count() > 1)
                <button type="button" class="kwh-lightbox-nav kwh-lightbox-prev" onclick="prevLightboxPhoto(event)" title="Sebelumnya (Panah Kiri)">
                    <i class="bi bi-chevron-left"></i>
                </button>
            @endif

            <img id="kwhLightboxImg" class="kwh-lightbox-img" src="" alt="Foto Dokumentasi kWh">

            @if($kwh->photos->count() > 1)
                <button type="button" class="kwh-lightbox-nav kwh-lightbox-next" onclick="nextLightboxPhoto(event)" title="Selanjutnya (Panah Kanan)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif

            <div id="kwhLightboxCaption" class="kwh-lightbox-caption"></div>
        </div>
    </div>

    <script>
        const kwhPhotos = @json($kwh->photos->map(function($p) {
            return [
                'src' => asset('storage/' . $p->path),
                'caption' => $p->keterangan
            ];
        }));

        let currentPhotoIndex = 0;
        const lightboxModal = document.getElementById('kwhLightboxModal');
        const lightboxImg = document.getElementById('kwhLightboxImg');
        const lightboxCaption = document.getElementById('kwhLightboxCaption');

        function openKwhLightbox(indexOrSrc, fallbackCaption) {
            if (!kwhPhotos || kwhPhotos.length === 0) return;

            if (typeof indexOrSrc === 'number') {
                currentPhotoIndex = (indexOrSrc + kwhPhotos.length) % kwhPhotos.length;
            } else {
                const foundIndex = kwhPhotos.findIndex(p => p.src === indexOrSrc);
                currentPhotoIndex = foundIndex !== -1 ? foundIndex : 0;
            }

            updateLightboxContent();
            lightboxModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeKwhLightbox() {
            lightboxModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function updateLightboxContent() {
            const photo = kwhPhotos[currentPhotoIndex];
            if (!photo) return;
            lightboxImg.src = photo.src;
            lightboxImg.alt = photo.caption || 'Foto kWh';
            const counterText = kwhPhotos.length > 1 ? ` (${currentPhotoIndex + 1} / ${kwhPhotos.length})` : '';
            lightboxCaption.textContent = (photo.caption || 'Foto Dokumentasi kWh') + counterText;
        }

        function nextLightboxPhoto(e) {
            if (e) e.stopPropagation();
            if (kwhPhotos.length <= 1) return;
            currentPhotoIndex = (currentPhotoIndex + 1) % kwhPhotos.length;
            updateLightboxContent();
        }

        function prevLightboxPhoto(e) {
            if (e) e.stopPropagation();
            if (kwhPhotos.length <= 1) return;
            currentPhotoIndex = (currentPhotoIndex - 1 + kwhPhotos.length) % kwhPhotos.length;
            updateLightboxContent();
        }

        function handleLightboxBackdropClick(e) {
            if (e.target === lightboxModal || e.target.classList.contains('kwh-lightbox-wrapper')) {
                closeKwhLightbox();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (!lightboxModal || !lightboxModal.classList.contains('active')) return;
            if (e.key === 'Escape') {
                closeKwhLightbox();
            } else if (e.key === 'ArrowRight') {
                nextLightboxPhoto();
            } else if (e.key === 'ArrowLeft') {
                prevLightboxPhoto();
            }
        });
    </script>
</body>

</html>