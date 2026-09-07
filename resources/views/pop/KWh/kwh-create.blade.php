<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah kWh - PLN Icon Plus</title>

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
                    <a href="{{ route('kwh.card', $pop->id) }}" class="rform-back" title="Kembali ke List kWh">
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
                            ['label' => 'Tambah kWh'],
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

                <form id="kwhForm" action="{{ route('kwh.store', $pop->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="rform-two-col">
                        <div class="rform-section">
                            <div class="rform-section-header">
                                <span class="rform-section-step">1</span>
                                General Information
                            </div>
                            <div class="rform-section-body">
                                <div class="rform-row rform-row-2">
                                    <div class="rform-group">
                                        <label class="rform-label">POP</label>
                                        <input type="text" class="rform-input"
                                            value="{{ $pop->kode_pop }} - {{ $pop->nama_pop }}" readonly>
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Building <span
                                                class="rform-required">*</span></label>
                                        <input type="text" name="building" class="rform-input"
                                            value="{{ old('building') }}" placeholder="Contoh: POP-SB" required>
                                    </div>
                                </div>

                                <div class="rform-row rform-row-2">
                                    <div class="rform-group">
                                        <label class="rform-label">PIC / Petugas <span
                                                class="rform-required">*</span></label>
                                        <input type="text" name="pic" class="rform-input"
                                            value="{{ old('pic') }}" placeholder="Masukkan nama PIC" required>
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type POP <span
                                                class="rform-required">*</span></label>
                                        <input type="text" name="type_pop" class="rform-input"
                                            value="{{ old('type_pop') }}" placeholder="Contoh: POP-SB" required>
                                    </div>
                                </div>

                                <div class="rform-row rform-row-2">
                                    <div class="rform-group">
                                        <label class="rform-label">ID Customer (PLN) <span
                                                class="rform-required">*</span></label>
                                        <input type="text" name="id_customer_pln" class="rform-input"
                                            value="{{ old('id_customer_pln') }}" placeholder="Masukkan ID Customer PLN"
                                            required>
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Tanggal Pemeriksaan <span
                                                class="rform-required">*</span></label>
                                        <input type="date" name="tanggal_pemeriksaan" class="rform-input"
                                            value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required>
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
                                <div class="rform-row rform-row-2">
                                    <div class="rform-group">
                                        <label class="rform-label">MCB Utama <span
                                                class="rform-required">*</span></label>
                                        <input type="text" id="mcbUtama" name="mcb_utama"
                                            class="rform-input decimal-input" value="{{ old('mcb_utama') }}"
                                            placeholder="Cukup masukkan angka MCB" required>
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Jumlah Phasa <span
                                                class="rform-required">*</span></label>
                                        <select id="jumlahPhasa" name="jumlah_phasa" class="rform-select" required>
                                            <option value="3 Phasa" @selected(old('jumlah_phasa', '3 Phasa') == '3 Phasa')>3 Phasa</option>
                                            <option value="1 Phasa" @selected(old('jumlah_phasa') == '1 Phasa')>1 Phasa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="rform-row rform-row-2">
                                    <div class="rform-group">
                                        <label class="rform-label">Keberadaan Arrester <span
                                                class="rform-required">*</span></label>
                                        <select name="keberadaan_arrester" class="rform-select" required>
                                            <option value="ADA" @selected(old('keberadaan_arrester', 'ADA') == 'ADA')>ADA (Terpasang)
                                            </option>
                                            <option value="TIDAK ADA" @selected(old('keberadaan_arrester') == 'TIDAK ADA')>TIDAK ADA</option>
                                        </select>
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Merk / Type Arrester</label>
                                        <input type="text" name="merk_type_arrester" class="rform-input"
                                            value="{{ old('merk_type_arrester') }}"
                                            placeholder="Contoh: OBO / OBO Bettermann V20">
                                    </div>
                                </div>

                                <div class="rform-row rform-row-1">
                                    <div class="rform-group">
                                        <label class="rform-label">Daya Listrik (PS GI) <span
                                                class="rform-required">*</span></label>
                                        <input type="text" id="dayaPsGi" class="rform-input"
                                            placeholder="Berdasarkan phasa dan MCB yang telah diinput" readonly>
                                    </div>
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
                                        <tr class="phasa-row" data-phasa="always">
                                            <td><strong>R - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rn"
                                                        class="rform-input decimal-input" value="{{ old('teg_rn') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>R</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusR"
                                                        name="arus_r" class="rform-input decimal-input"
                                                        value="{{ old('arus_r') }}" placeholder="Masukkan nilai"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>S - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_sn"
                                                        class="rform-input decimal-input" value="{{ old('teg_sn') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusS"
                                                        name="arus_s" class="rform-input decimal-input"
                                                        value="{{ old('arus_s') }}" placeholder="Masukkan nilai"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>T - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_tn"
                                                        class="rform-input decimal-input" value="{{ old('teg_tn') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusT"
                                                        name="arus_t" class="rform-input decimal-input"
                                                        value="{{ old('arus_t') }}" placeholder="Masukkan nilai"
                                                        required><span class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>R - S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rs"
                                                        class="rform-input decimal-input" value="{{ old('teg_rs') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>S - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_st"
                                                        class="rform-input decimal-input" value="{{ old('teg_st') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>R - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rt"
                                                        class="rform-input decimal-input" value="{{ old('teg_rt') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="always">
                                            <td><strong>N - G</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_ng"
                                                        class="rform-input decimal-input" value="{{ old('teg_ng') }}"
                                                        placeholder="Masukkan nilai" required><span
                                                        class="unit-text">Vac</span></div>
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
                                    <input type="text" id="totalDayaTerpakai" class="rform-input decimal-input"
                                        placeholder="Berdasarkan phasa dan arus beban R-S-T" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Total Beban (A) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" id="totalBeban" class="rform-input decimal-input"
                                        placeholder="Total Beban R-S-T" readonly>
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
                                            value="{{ old('warna_r', 'Merah') }}" placeholder="Warna Fasa R"
                                            required></div>
                                    <div class="cable-input-row"><span class="cable-badge">S</span><input
                                            type="text" name="warna_s" class="rform-input"
                                            value="{{ old('warna_s', 'Kuning Hijau') }}" placeholder="Warna Fasa S"
                                            required></div>
                                    <div class="cable-input-row"><span class="cable-badge">T</span><input
                                            type="text" name="warna_t" class="rform-input"
                                            value="{{ old('warna_t', 'Hitam') }}" placeholder="Warna Fasa T"
                                            required></div>
                                    <div class="cable-input-row"><span class="cable-badge">N</span><input
                                            type="text" name="warna_n" class="rform-input"
                                            value="{{ old('warna_n', 'Biru') }}" placeholder="Warna Netral" required>
                                    </div>
                                    <div class="cable-input-row"><span class="cable-badge">G</span><input
                                            type="text" name="warna_g" class="rform-input"
                                            value="{{ old('warna_g', 'Kuning Hijau') }}"
                                            placeholder="Warna Grounding" required></div>
                                </div>

                                <div class="cable-form-block">
                                    <div class="cable-block-heading"><i class="bi bi-rulers"></i> Ukuran Kabel
                                        (mm&sup2;)</div>
                                    <div class="cable-input-row"><span class="cable-badge">R</span><input
                                            type="text" name="ukuran_r" class="rform-input"
                                            value="{{ old('ukuran_r', '16mm') }}" placeholder="Ukuran mm²" required>
                                    </div>
                                    <div class="cable-input-row"><span class="cable-badge">S</span><input
                                            type="text" name="ukuran_s" class="rform-input"
                                            value="{{ old('ukuran_s', '16mm') }}" placeholder="Ukuran mm²" required>
                                    </div>
                                    <div class="cable-input-row"><span class="cable-badge">T</span><input
                                            type="text" name="ukuran_t" class="rform-input"
                                            value="{{ old('ukuran_t', '16mm') }}" placeholder="Ukuran mm²" required>
                                    </div>
                                    <div class="cable-input-row"><span class="cable-badge">N</span><input
                                            type="text" name="ukuran_n" class="rform-input"
                                            value="{{ old('ukuran_n', '16mm') }}" placeholder="Ukuran mm²" required>
                                    </div>
                                    <div class="cable-input-row"><span class="cable-badge">G</span><input
                                            type="text" name="ukuran_g" class="rform-input"
                                            value="{{ old('ukuran_g', '16mm') }}" placeholder="Ukuran mm²" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rform-section">
                        <div class="rform-section-header" style="justify-content: space-between;">
                            <div><span class="rform-section-step">5</span> Dokumentasi Foto kWh</div>
                            <span id="kwhPhotoCountBadge" class="photo-count-badge"><i class="bi bi-images"></i>
                                Total: 1 Foto</span>
                        </div>
                        <div class="rform-section-body">
                            <div id="kwhPhotoCardsGrid" class="kwh-photo-cards-grid">
                                <div class="kwh-photo-card" id="photoCard-0">
                                    <div class="kwh-photo-card-header">
                                        <span class="kwh-photo-card-title">Foto 1</span>
                                        <button type="button" class="btn-photo-remove" onclick="removePhotoCard(0)"
                                            title="Hapus foto ini">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                    <div class="kwh-photo-card-body">
                                        <div class="kwh-photo-preview-box">
                                            <div class="kwh-photo-empty" id="photoEmpty-0">
                                                <i class="bi bi-image"></i>
                                                <small>Belum ada foto</small>
                                            </div>
                                            <img id="photoPreview-0" class="kwh-photo-img" style="display: none;"
                                                alt="Preview Foto"
                                                onclick="openKwhLightbox(this.src, document.querySelector('#photoCard-0 input[type=text]')?.value || 'Foto 1')">
                                        </div>
                                        <div class="kwh-photo-upload-action">
                                            <label for="photoInput-0" class="btn-kwh-browse">
                                                <i class="bi bi-camera-fill"></i> Pilih Foto
                                            </label>
                                            <input type="file" name="photos[0]" id="photoInput-0"
                                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                                onchange="previewDynamicPhoto(this, 0)" hidden>
                                        </div>
                                        <div class="rform-group" style="margin-top: 6px;">
                                            <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto
                                                <span class="rform-required">*</span></label>
                                            <input type="text" name="captions[0]" class="rform-input"
                                                placeholder="Contoh: Tampak kWh Utama" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 18px;">
                                <button type="button" class="rform-btn-add-module" onclick="addPhotoCard()">
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
                        <button type="button" class="rform-btn-reset" onclick="resetKwhForm()">Reset</button>
                        <button type="submit" class="rform-btn-simpan">
                            <i class="bi bi-check-lg"></i> Simpan
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        function formatDecimal(value, decimals = 2) {
            return value.toFixed(decimals).replace('.', ',');
        }

        function parseDecimal(value) {
            if (!value) return 0;
            return parseFloat(String(value).replace(',', '.')) || 0;
        }

        function togglePhasaRows() {
            const phasa = document.getElementById('jumlahPhasa').value;
            const rows = document.querySelectorAll('.phasa-row[data-phasa="3-only"]');

            rows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                if (phasa === '1 Phasa') {
                    row.style.display = 'none';
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.removeAttribute('required');
                    });
                } else {
                    row.style.display = '';
                    inputs.forEach(input => {
                        input.disabled = false;
                        input.setAttribute('required', 'required');
                    });
                }
            });

            hitungTotalDanBeban();
        }

        document.getElementById('jumlahPhasa').addEventListener('change', togglePhasaRows);
        document.addEventListener('DOMContentLoaded', togglePhasaRows);

        function hitungTotalDanBeban() {
            const arusR = parseDecimal(document.getElementById('arusR')?.value);
            const arusS = parseDecimal(document.getElementById('arusS')?.value);
            const arusT = parseDecimal(document.getElementById('arusT')?.value);
            const phasa = document.getElementById('jumlahPhasa').value;

            let totalDaya = 0;
            if (phasa === '1 Phasa') {
                totalDaya = arusR * 220;
            } else {
                const arusTerbesar = Math.max(arusR, arusS, arusT);
                totalDaya = arusTerbesar * 380 * 0.75 * 1.73;
            }

            const totalBeban = arusR + arusS + arusT;

            document.getElementById('totalDayaTerpakai').value = totalDaya > 0 ? formatDecimal(totalDaya) + ' VA' : '';
            document.getElementById('totalBeban').value = totalBeban > 0 ? formatDecimal(totalBeban) + ' A' : '';
        }

        document.getElementById('arusR').addEventListener('input', hitungTotalDanBeban);
        document.getElementById('arusS').addEventListener('input', hitungTotalDanBeban);
        document.getElementById('arusT').addEventListener('input', hitungTotalDanBeban);
        document.getElementById('jumlahPhasa').addEventListener('change', hitungTotalDanBeban);

        function hitungDayaPsGi() {
            const mcb = parseDecimal(document.getElementById('mcbUtama').value);
            const phasa = document.getElementById('jumlahPhasa').value;

            let daya = 0;
            if (phasa === '1 Phasa') {
                daya = 220 * mcb;
            } else if (phasa === '3 Phasa') {
                daya = 3 * 220 * mcb;
            }

            daya = Math.round(daya);
            document.getElementById('dayaPsGi').value = daya > 0 ? daya.toLocaleString('id-ID') + ' VA' : '';
        }

        document.getElementById('mcbUtama').addEventListener('input', hitungDayaPsGi);
        document.getElementById('jumlahPhasa').addEventListener('change', hitungDayaPsGi);

        let photoIndexCounter = 1;

        function previewDynamicPhoto(input, idx) {
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
                const img = document.getElementById('photoPreview-' + idx);
                const empty = document.getElementById('photoEmpty-' + idx);
                if (img && empty) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    empty.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }

        function addPhotoCard() {
            const grid = document.getElementById('kwhPhotoCardsGrid');
            const idx = photoIndexCounter++;
            const currentCount = grid.querySelectorAll('.kwh-photo-card').length + 1;

            const card = document.createElement('div');
            card.className = 'kwh-photo-card';
            card.id = 'photoCard-' + idx;
            card.innerHTML = `
            <div class="kwh-photo-card-header">
                <span class="kwh-photo-card-title">Foto ${currentCount}</span>
                <button type="button" class="btn-photo-remove" onclick="removePhotoCard(${idx})" title="Hapus foto ini">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
            <div class="kwh-photo-card-body">
                <div class="kwh-photo-preview-box">
                    <div class="kwh-photo-empty" id="photoEmpty-${idx}">
                        <i class="bi bi-image"></i>
                        <small>Belum ada foto</small>
                    </div>
                    <img id="photoPreview-${idx}" class="kwh-photo-img" style="display: none;" alt="Preview Foto" onclick="openKwhLightbox(this.src, document.querySelector('#photoCard-${idx} input[type=text]')?.value || 'Foto ${currentCount}')">
                </div>
                <div class="kwh-photo-upload-action">
                    <label for="photoInput-${idx}" class="btn-kwh-browse">
                        <i class="bi bi-camera-fill"></i> Pilih Foto
                    </label>
                    <input type="file" name="photos[${idx}]" id="photoInput-${idx}" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhoto(this, ${idx})" hidden>
                </div>
                <div class="rform-group" style="margin-top: 6px;">
                    <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                    <input type="text" name="captions[${idx}]" class="rform-input" placeholder="Keterangan foto..." required>
                </div>
            </div>
        `;
            grid.appendChild(card);
            reindexPhotoCards();
        }

        function removePhotoCard(idx) {
            const grid = document.getElementById('kwhPhotoCardsGrid');
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
            const el = document.getElementById('photoCard-' + idx);
            if (el) {
                el.remove();
                reindexPhotoCards();
            }
        }

        function reindexPhotoCards() {
            const cards = document.querySelectorAll('#kwhPhotoCardsGrid .kwh-photo-card');
            cards.forEach((card, i) => {
                const title = card.querySelector('.kwh-photo-card-title');
                if (title) title.innerText = 'Foto ' + (i + 1);
            });
            const badge = document.getElementById('kwhPhotoCountBadge');
            if (badge) badge.innerHTML = `<i class="bi bi-images"></i> Total: ${cards.length} Foto`;
        }

        function resetKwhForm() {
            Swal.fire({
                title: 'Reset Formulir?',
                text: 'Seluruh isian data dan foto yang telah Anda masukkan akan dikosongkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('kwhForm').reset();
                    photoIndexCounter = 1;
                    const grid = document.getElementById('kwhPhotoCardsGrid');
                    grid.innerHTML = `
                    <div class="kwh-photo-card" id="photoCard-0">
                        <div class="kwh-photo-card-header">
                            <span class="kwh-photo-card-title">Foto 1</span>
                            <button type="button" class="btn-photo-remove" onclick="removePhotoCard(0)" title="Hapus foto ini">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                        <div class="kwh-photo-card-body">
                            <div class="kwh-photo-preview-box">
                                <div class="kwh-photo-empty" id="photoEmpty-0">
                                    <i class="bi bi-image"></i>
                                    <small>Belum ada foto</small>
                                </div>
                                <img id="photoPreview-0" class="kwh-photo-img" style="display: none;" alt="Preview Foto" onclick="openKwhLightbox(this.src, document.querySelector('#photoCard-0 input[type=text]')?.value || 'Foto 1')">
                            </div>
                            <div class="kwh-photo-upload-action">
                                <label for="photoInput-0" class="btn-kwh-browse">
                                    <i class="bi bi-camera-fill"></i> Pilih Foto
                                </label>
                                <input type="file" name="photos[0]" id="photoInput-0" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewDynamicPhoto(this, 0)" hidden>
                            </div>
                            <div class="rform-group" style="margin-top: 6px;">
                                <label class="rform-label" style="font-size: 0.76rem;">Keterangan Foto <span class="rform-required">*</span></label>
                                <input type="text" name="captions[0]" class="rform-input" placeholder="Contoh: Tampak kWh Utama" required>
                            </div>
                        </div>
                    </div>
                `;
                    reindexPhotoCards();
                    Swal.fire({
                        icon: 'success',
                        title: 'Formulir Dikosongkan',
                        showConfirmButton: false,
                        timer: 1200
                    });
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

        document.querySelectorAll('.decimal-input').forEach(function(input) {
            input.addEventListener('input', function() {
                // izinkan angka, koma, titik, dan minus (untuk kasus khusus kalau ada nilai negatif)
                this.value = this.value.replace(/[^0-9.,\-]/g, '');
            });
        });

        document.getElementById('kwhForm').addEventListener('submit', function() {
            document.querySelectorAll('.decimal-input').forEach(function(input) {
                input.value = input.value.replace(',', '.');
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
