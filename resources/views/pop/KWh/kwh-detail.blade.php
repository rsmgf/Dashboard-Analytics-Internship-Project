<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <x-breadcrumb :items="[
                                    ['label' => 'POP', 'route' => 'pops.index'],
                                    [
                                        'label' => $pop->nama_pop . ': kWh',
                                        'route' => 'kwh.card',
                                        'params' => ['pop' => $pop->id],
                                    ],
                                    ['label' => $kwh->building],
                                ]" />
                                <span class="device-badge">{{ $kwh->jumlah_phasa }} &bull;
                                    {{ $kwh->daya_ps_gi_formatted }}</span>
                            </div>
                            <span class="pop-sub-info">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot;
                                {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</span>
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
                        Terakhir diperbarui oleh: <strong>{{ $kwh->diupdateOleh->name ?? '-' }} &middot;
                            {{ $kwh->updated_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                    </span>
                </div>

                <section class="detail-card information-card">
                    <div class="detail-card-title">
                        <i class="bi bi-info-circle-fill"></i> General Information
                    </div>

                    <div class="general-grid">
                        <div class="general-item">
                            <span class="general-label">POP</span>
                            <span class="general-value">{{ $pop->nama_pop }} ({{ $pop->kode_pop }})</span>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Tanggal Pemeriksaan</span>
                            <span
                                class="general-value">{{ $kwh->tanggal_pemeriksaan->translatedFormat('d F Y') }}</span>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Building</span>
                            <span class="general-value">{{ $kwh->building }}</span>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Type POP</span>
                            <span class="general-value">{{ $kwh->type_pop }}</span>
                        </div>

                        <div class="general-item">
                            <span class="general-label">PIC / Petugas</span>
                            <span class="general-value">{{ $kwh->pic }}</span>
                        </div>

                        <div class="general-item">
                            <span class="general-label">ID Pelanggan Listrik</span>
                            <span class="general-value">{{ $kwh->id_customer_pln }}</span>
                        </div>
                    </div>
                </section>

                <section class="detail-card checklist-card">
                    <div class="section-heading">
                        <span><i class="bi bi-speedometer2"></i> Checklist & Pengukuran Phasa</span>
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
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>S - T</strong></td>
                                                    <td><code>{{ $kwh->teg_st }} Vac</code></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>R - T</strong></td>
                                                    <td><code>{{ $kwh->teg_rt }} Vac</code></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>N - G</strong></td>
                                                    <td><code>{{ $kwh->teg_ng }} Vac</code></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
                                                    <td><span style="color: #94a3b8;">&mdash;</span></td>
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
                            <div class="photo-item"
                                onclick="openKwhLightbox(this.querySelector('img').src, '{{ $photo->keterangan }}')">
                                <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->keterangan }}"
                                    onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text={{ urlencode($photo->keterangan) }}'">
                                <span>{{ $photo->keterangan }}</span>
                            </div>
                        @empty
                            <p style="color:#94a3b8; grid-column: 1/-1; text-align:center; padding: 20px;">Belum ada
                                foto dokumentasi.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div id="imageLightboxModal" class="kwh-lightbox-modal" onclick="closeKwhLightbox(event)">
        <div class="kwh-lightbox-wrapper">
            <button type="button" class="kwh-lightbox-close" onclick="closeKwhLightboxDirect()" title="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
            <img id="lightboxImg" class="kwh-lightbox-img" src="" alt="Preview Foto HD">
            <div id="lightboxCaption" class="kwh-lightbox-caption"></div>
        </div>
    </div>

    <script>
        function openKwhLightbox(src, caption) {
            const modal = document.getElementById('imageLightboxModal');
            const img = document.getElementById('lightboxImg');
            const cap = document.getElementById('lightboxCaption');
            if (modal && img) {
                img.src = src;
                if (cap) cap.innerText = caption || 'Bukti Foto kWh';
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeKwhLightbox(e) {
            if (e.target.id === 'imageLightboxModal') closeKwhLightboxDirect();
        }

        function closeKwhLightboxDirect() {
            const modal = document.getElementById('imageLightboxModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeKwhLightboxDirect();
        });
    </script>

</body>

</html>
