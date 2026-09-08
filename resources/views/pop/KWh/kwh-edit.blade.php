<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit kWh - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/sidebar.css', 'resources/css/rectifier-form.css', 'resources/css/kwh-create.css'])
</head>

<body>

    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rform-content">

                <div class="rform-header-bar">
                    <a href="{{ route('kwh.detail', [$pop->id, $kwh->id]) }}" class="rform-back"
                        title="Kembali ke Detail kWh">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="rform-header-text">
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            [
                                'label' => $pop->nama_pop . ': kWh',
                                'route' => 'kwh.card',
                                'params' => ['pop' => $pop->id],
                            ],
                            ['label' => $kwh->building, 'route' => 'kwh.detail', 'params' => [$pop->id, $kwh->id]],
                            ['label' => 'Edit kWh'],
                        ]" />
                        <p class="rform-page-sub">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot;
                            {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3"
                        style="background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:16px;">
                        <strong>Periksa kembali isian form:</strong>
                        <ul style="margin: 6px 0 0 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="kwhEditForm" action="{{ route('kwh.update', [$pop->id, $kwh->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">1</span>
                            General Information
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-3">
                                <div class="rform-group">
                                    <label class="rform-label">POP</label>
                                    <input type="text" class="rform-input"
                                        value="{{ $pop->kode_pop }} - {{ $pop->nama_pop }}" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Building <span class="rform-required">*</span></label>
                                    <input type="text" name="building" class="rform-input"
                                        value="{{ old('building', $kwh->building) }}" required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">PIC / Petugas <span
                                            class="rform-required">*</span></label>
                                    <input type="text" name="pic" class="rform-input"
                                        value="{{ old('pic', $kwh->pic) }}" required>
                                </div>
                            </div>

                            <div class="rform-row rform-row-3">
                                <div class="rform-group">
                                    <label class="rform-label">Type POP <span class="rform-required">*</span></label>
                                    <input type="text" name="type_pop" class="rform-input"
                                        value="{{ old('type_pop', $kwh->type_pop) }}" required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">ID Customer (PLN) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" name="id_customer_pln" class="rform-input"
                                        value="{{ old('id_customer_pln', $kwh->id_customer_pln) }}" required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Tanggal Pemeriksaan <span
                                            class="rform-required">*</span></label>
                                    <input type="date" name="tanggal_pemeriksaan" class="rform-input"
                                        value="{{ old('tanggal_pemeriksaan', $kwh->tanggal_pemeriksaan->format('Y-m-d')) }}"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">2</span>
                            Spesifikasi Panel kWh Meter
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-4">
                                <div class="rform-group">
                                    <label class="rform-label">Daya (PS GI) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" name="daya_ps_gi" class="rform-input"
                                        value="{{ old('daya_ps_gi', $kwh->daya_ps_gi) }}" readonly required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">MCB Utama <span class="rform-required">*</span></label>
                                    <input type="text" name="mcb_utama" class="rform-input"
                                        value="{{ old('mcb_utama', $kwh->mcb_utama) }}" required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Jumlah Phasa <span
                                            class="rform-required">*</span></label>
                                    <select name="jumlah_phasa" class="rform-select" required>
                                        <option value="3 Phasa" @selected(old('jumlah_phasa', $kwh->jumlah_phasa) == '3 Phasa')>3 Phasa</option>
                                        <option value="1 Phasa" @selected(old('jumlah_phasa', $kwh->jumlah_phasa) == '1 Phasa')>1 Phasa</option>
                                    </select>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Keberadaan Arrester <span
                                            class="rform-required">*</span></label>
                                    <select name="keberadaan_arrester" class="rform-select" required>
                                        <option value="ADA" @selected(old('keberadaan_arrester', $kwh->keberadaan_arrester) == 'ADA')>ADA (Terpasang)</option>
                                        <option value="TIDAK ADA" @selected(old('keberadaan_arrester', $kwh->keberadaan_arrester) == 'TIDAK ADA')>TIDAK ADA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Merk / Type Arrester</label>
                                    <input type="text" name="merk_type_arrester" class="rform-input"
                                        value="{{ old('merk_type_arrester', $kwh->merk_type_arrester) }}">
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Status Kelistrikan <span
                                            class="rform-required">*</span></label>
                                    <select name="status_kelistrikan" class="rform-select" required>
                                        <option value="OK / Memadai" @selected(old('status_kelistrikan', $kwh->status_kelistrikan) == 'OK / Memadai')>OK / Memadai</option>
                                        <option value="Perlu Perbaikan" @selected(old('status_kelistrikan', $kwh->status_kelistrikan) == 'Perlu Perbaikan')>Perlu Perbaikan
                                        </option>
                                        <option value="Kritis" @selected(old('status_kelistrikan', $kwh->status_kelistrikan) == 'Kritis')>Kritis / Tidak Standar
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">3</span>
                            Pengukuran Tegangan & Arus Phasa
                        </div>
                        <div class="rform-section-body">
                            <div class="kwh-form-table-wrapper">
                                <table class="kwh-form-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">Tegangan (Vac)</th>
                                            <th style="width: 25%;">Nilai Pengukuran (Vac)</th>
                                            <th style="width: 25%;">Arus Beban (A)</th>
                                            <th style="width: 25%;">Nilai Pengukuran (A)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>R - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rn"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_rn', $kwh->teg_rn) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>R</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusR"
                                                        name="arus_r" class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('arus_r', $kwh->arus_r) ?? '') }}"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>S - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_sn"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_sn', $kwh->teg_sn) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="arus_s"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('arus_s', $kwh->arus_s) ?? '') }}"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>T - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_tn"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_tn', $kwh->teg_tn) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="arus_t"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('arus_t', $kwh->arus_t) ?? '') }}"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>R - S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rs"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_rs', $kwh->teg_rs) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr>
                                            <td><strong>S - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_st"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_st', $kwh->teg_st) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr>
                                            <td><strong>R - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rt"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_rt', $kwh->teg_rt) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr>
                                            <td><strong>N - G</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_ng"
                                                        class="rform-input decimal-input"
                                                        value="{{ str_replace('.', ',', old('teg_ng', $kwh->teg_ng) ?? '') }}"
                                                        required><span class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="rform-row rform-row-2 kwh-totals-row">
                                <div class="rform-group">
                                    <label class="rform-label">Total Daya Terpakai (VA) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" name="total_daya_terpakai"
                                        class="rform-input decimal-input"
                                        value="{{ str_replace('.', ',', old('total_daya_terpakai', $kwh->total_daya_terpakai) ?? '') }}"
                                        readonly required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Total Beban (A) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" name="total_beban" class="rform-input decimal-input"
                                        value="{{ str_replace('.', ',', old('total_beban', $kwh->total_beban) ?? '') }}"
                                        readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">4</span>
                            Spesifikasi Kabel Output kWh
                        </div>
                        <div class="rform-section-body">
                            <div class="cable-form-grid">
                                <div class="cable-form-block">
                                    <div class="cable-block-heading"><i class="bi bi-palette-fill"></i> Warna Kabel
                                        Output</div>
                                    <div class="cable-input-row"><span class="cable-badge">R</span><input
                                            type="text" name="warna_r" class="rform-input"
                                            value="{{ old('warna_r', $kwh->warna_r) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">S</span><input
                                            type="text" name="warna_s" class="rform-input"
                                            value="{{ old('warna_s', $kwh->warna_s) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">T</span><input
                                            type="text" name="warna_t" class="rform-input"
                                            value="{{ old('warna_t', $kwh->warna_t) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">N</span><input
                                            type="text" name="warna_n" class="rform-input"
                                            value="{{ old('warna_n', $kwh->warna_n) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">G</span><input
                                            type="text" name="warna_g" class="rform-input"
                                            value="{{ old('warna_g', $kwh->warna_g) }}" required></div>
                                </div>

                                <div class="cable-form-block">
                                    <div class="cable-block-heading"><i class="bi bi-rulers"></i> Ukuran Kabel
                                        (mm&sup2;)</div>
                                    <div class="cable-input-row"><span class="cable-badge">R</span><input
                                            type="text" name="ukuran_r" class="rform-input"
                                            value="{{ old('ukuran_r', $kwh->ukuran_r) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">S</span><input
                                            type="text" name="ukuran_s" class="rform-input"
                                            value="{{ old('ukuran_s', $kwh->ukuran_s) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">T</span><input
                                            type="text" name="ukuran_t" class="rform-input"
                                            value="{{ old('ukuran_t', $kwh->ukuran_t) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">N</span><input
                                            type="text" name="ukuran_n" class="rform-input"
                                            value="{{ old('ukuran_n', $kwh->ukuran_n) }}" required></div>
                                    <div class="cable-input-row"><span class="cable-badge">G</span><input
                                            type="text" name="ukuran_g" class="rform-input"
                                            value="{{ old('ukuran_g', $kwh->ukuran_g) }}" required></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rform-section">
                        <div class="rform-section-header" style="justify-content: space-between;">
                            <div><span class="rform-section-step">5</span> Dokumentasi Foto kWh</div>
                            <span id="kwhEditPhotoCountBadge" class="photo-count-badge">
                                <i class="bi bi-images"></i> Total: {{ $kwh->photos->count() }} Foto
                            </span>
                        </div>
                        <div class="rform-section-body">
                            <div id="kwhEditPhotoCardsGrid" class="kwh-photo-cards-grid">
                                @foreach ($kwh->photos as $i => $photo)
                                    <div class="kwh-photo-card" id="photoCardEdit-{{ $i }}">
                                        <div class="kwh-photo-card-header">
                                            <span class="kwh-photo-card-title">Foto {{ $i + 1 }}</span>
                                            <button type="button" class="btn-photo-remove"
                                                onclick="removePhotoCardEdit({{ $i }})"
                                                title="Hapus foto ini">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </div>
                                        <div class="kwh-photo-card-body">
                                            <div class="kwh-photo-preview-box">
                                                <img id="photoPreviewEdit-{{ $i }}"
                                                    src="{{ asset('storage/' . $photo->path) }}"
                                                    class="kwh-photo-img"
                                                    onerror="this.src='https://placehold.co/400x300/f1f5f9/94a3b8?text=Foto'"
                                                    alt="Preview Foto {{ $i + 1 }}"
                                                    onclick="openKwhLightbox(this.src, this.closest('.kwh-photo-card').querySelector('input[type=text]')?.value || 'Foto {{ $i + 1 }}')">
                                            </div>
                                            <div class="kwh-photo-upload-action">
                                                <label for="photoInputEdit-{{ $i }}"
                                                    class="btn-kwh-browse">
                                                    <i class="bi bi-arrow-repeat"></i> Ganti Foto
                                                </label>
                                                <input type="file" name="photos[{{ $i }}]"
                                                    id="photoInputEdit-{{ $i }}"
                                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                                    onchange="previewDynamicPhotoEdit(this, {{ $i }})"
                                                    hidden>
                                            </div>
                                            <div class="rform-group" style="margin-top: 6px;">
                                                <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto
                                                    <span class="rform-required">*</span></label>
                                                <input type="text" name="captions[{{ $i }}]"
                                                    class="rform-input" value="{{ $photo->keterangan }}" required>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top: 18px;">
                                <button type="button" class="rform-btn-add-module" onclick="addPhotoCardEdit()">
                                    <i class="bi bi-plus-lg"></i> Tambah Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="kwh-sop-note">
                        <div class="sop-note-title">
                            <i class="bi bi-info-circle-fill"></i> Standar Operasional Prosedur (SOP) & Catatan
                            Pemeriksaan
                        </div>
                        <ol class="sop-note-list">
                            <li>Pastikan tegangan setiap fasa berkisar antara <strong>210 &ndash; 225 Vac</strong>. Jika
                                di luar rentang tersebut, segera laporkan ke tim NOC / Internal ICON Plus.</li>
                            <li>Pastikan semua terminasi kabel terpasang dengan kuat dan kencang pada baut MCB.</li>
                            <li>Periksa indikator surge arrester: harus dalam kondisi <strong>Hijau (Baik)</strong>.
                                Jika berwarna <strong>Merah</strong>, segera lakukan penggantian.</li>
                            <li>Pastikan boks panel kWh bebas karat. Lakukan pengecatan anti-karat bila ditemukan
                                korosi.</li>
                        </ol>
                    </div>

                    <div class="rform-actions">
                        <button type="button" class="rform-btn-reset" onclick="resetKwhEditForm()">Reset</button>
                        <button type="button" id="btnSubmitEdit" class="rform-btn-simpan">
                            <i class="bi bi-check-lg"></i> Perbarui
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        let photoEditCounter = {{ $kwh->photos->count() }};

        function previewDynamicPhotoEdit(input, idx) {
            const file = input.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran foto maksimal adalah 2MB.',
                    confirmButtonColor: '#2563eb'
                });
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('photoPreviewEdit-' + idx);
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }

        function addPhotoCardEdit() {
            const grid = document.getElementById('kwhEditPhotoCardsGrid');
            const idx = photoEditCounter++;
            const currentCount = grid.querySelectorAll('.kwh-photo-card').length + 1;

            const card = document.createElement('div');
            card.className = 'kwh-photo-card';
            card.id = 'photoCardEdit-' + idx;
            card.innerHTML = `
            <div class="kwh-photo-card-header">
                <span class="kwh-photo-card-title">Foto ${currentCount}</span>
                <button type="button" class="btn-photo-remove" onclick="removePhotoCardEdit(${idx})" title="Hapus foto ini">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
            <div class="kwh-photo-card-body">
                <div class="kwh-photo-preview-box">
                    <div class="kwh-photo-empty" id="photoEmptyEdit-${idx}">
                        <i class="bi bi-image"></i>
                        <small>Belum ada foto</small>
                    </div>
                    <img id="photoPreviewEdit-${idx}" class="kwh-photo-img" style="display: none;" alt="Preview Foto" onclick="openKwhLightbox(this.src, this.closest('.kwh-photo-card').querySelector('input[type=text]')?.value || 'Foto ${currentCount}')">
                </div>
                <div class="kwh-photo-upload-action">
                    <label for="photoInputEdit-${idx}" class="btn-kwh-browse">
                        <i class="bi bi-camera-fill"></i> Pilih Foto
                    </label>
                    <input type="file" name="photos[${idx}]" id="photoInputEdit-${idx}" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhotoEdit(this, ${idx})" hidden>
                </div>
                <div class="rform-group" style="margin-top: 6px;">
                    <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                    <input type="text" name="captions[${idx}]" class="rform-input" placeholder="Keterangan foto baru..." required>
                </div>
            </div>
        `;
            grid.appendChild(card);
            reindexPhotoCardsEdit();
        }

        function removePhotoCardEdit(idx) {
            const grid = document.getElementById('kwhEditPhotoCardsGrid');
            const cards = grid.querySelectorAll('.kwh-photo-card');
            if (cards.length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimal 1 Foto',
                    text: 'Dokumentasi kWh wajib memiliki minimal 1 foto.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }
            const el = document.getElementById('photoCardEdit-' + idx);
            if (el) {
                el.remove();
                reindexPhotoCardsEdit();
            }
        }

        function reindexPhotoCardsEdit() {
            const cards = document.querySelectorAll('#kwhEditPhotoCardsGrid .kwh-photo-card');
            cards.forEach((card, i) => {
                const title = card.querySelector('.kwh-photo-card-title');
                if (title) title.innerText = 'Foto ' + (i + 1);
            });
            const badge = document.getElementById('kwhEditPhotoCountBadge');
            if (badge) badge.innerHTML = `<i class="bi bi-images"></i> Total: ${cards.length} Foto`;
        }

        function resetKwhEditForm() {
            Swal.fire({
                title: 'Kembalikan Data Semula?',
                text: 'Perubahan yang belum tersimpan akan dikembalikan ke data awal.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        }

        function openKwhLightbox(src, caption) {
            if (!src) return;
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

        document.getElementById('btnSubmitEdit').addEventListener('click', function() {
            const form = document.getElementById('kwhEditForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Data kWh yang sudah diubah akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Perbarui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    <div id="imageLightboxModal" class="kwh-lightbox-modal" onclick="closeKwhLightbox(event)">
        <div class="kwh-lightbox-wrapper">
            <button type="button" class="kwh-lightbox-close" onclick="closeKwhLightboxDirect()" title="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
            <img id="lightboxImg" class="kwh-lightbox-img" src="" alt="Preview Foto HD">
            <div id="lightboxCaption" class="kwh-lightbox-caption"></div>
        </div>
    </div>

</body>

</html>
