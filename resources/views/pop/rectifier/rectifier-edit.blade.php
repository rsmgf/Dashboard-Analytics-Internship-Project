<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rectifier - {{ $rectifier->nama_alias }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
        // ---- Konfirmasi Simpan Perubahan ----
        function konfirmasiSimpan() {
            Swal.fire({
                icon: 'question',
                title: 'Simpan Perubahan?',
                text: 'Pastikan semua data sudah benar sebelum menyimpan.',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rectifierForm').submit();
                }
            });
        }
    </script>

    @vite(['resources/css/sidebar.css', 'resources/css/rectifier-form.css'])
</head>

<body>
    <div class="app-container">

        {{-- SIDEBAR COMPONENT --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            {{-- TOPBAR COMPONENT --}}
            <x-topbar />

            <div class="rform-content">

                {{-- HEADER BAR: Back + Breadcrumb As Title --}}
                <div class="rform-header-bar">
                    <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}" class="rform-back"
                        title="Kembali ke Detail Rectifier">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="rform-header-text">
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop, 'route' => 'rectifiers.index', 'params' => ['pop' => $pop->id]],
                            ['label' => 'Edit Rectifier (' . ($rectifier->nama_alias ?? $rectifier->merk) . ')'],
                        ]" />
                        <p class="rform-page-sub">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot;
                            {{ $pop->kota_kabupaten }}, {{ $pop->provinsi }}</p>
                    </div>
                </div>

                {{-- Flash --}}
                @if (session('error'))
                    <div class="rform-flash error">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="rform-flash error">
                        <div>
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali:</strong>
                            <ul style="margin:4px 0 0 16px; padding:0;">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form id="rectifierForm" method="POST"
                    action="{{ route('rectifiers.update', [$pop->id, $rectifier->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ===============================================
                     SECTION 1 — Information Rectifier (Header)
                ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">1</span>
                            Information Rectifier
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-4">

                                <div class="rform-group">
                                    <label class="rform-label">POP</label>
                                    <input type="text" class="rform-input" value="{{ $pop->kode_pop }}" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Type POP</label>
                                    <input type="text" class="rform-input" value="{{ $pop->tipe_pop ?? '' }}"
                                        readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Tanggal Pemeriksaan <span
                                            class="rform-required">*</span></label>
                                    <input type="date" name="tanggal_pemeriksaan" class="rform-input"
                                        value="{{ old('tanggal_pemeriksaan', $rectifier->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rectifier->tanggal_pemeriksaan)->format('Y-m-d') : '') }}">
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">PIC <span class="rform-required">*</span></label>
                                    <input type="text" name="pic"
                                        class="rform-input {{ $errors->has('pic') ? 'is-invalid' : '' }}"
                                        value="{{ old('pic', $rectifier->pic) }}" placeholder="Nama penanggung jawab">
                                    @error('pic')
                                        <span class="rform-error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            {{-- Nama Alias (digunakan sebagai judul card) --}}
                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Nama Alias Rectifier <span
                                            class="rform-required">*</span>
                                        <small style="font-weight:400; color:#94a3b8;">(tampil di card)</small>
                                    </label>
                                    <input type="text" name="nama_alias"
                                        class="rform-input {{ $errors->has('nama_alias') ? 'is-invalid' : '' }}"
                                        value="{{ old('nama_alias', $rectifier->nama_alias) }}"
                                        placeholder="Contoh: Rectifier Utama 1">
                                    @error('nama_alias')
                                        <span class="rform-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Deskripsi</label>
                                    <input type="text" name="deskripsi" class="rform-input"
                                        value="{{ old('deskripsi', $rectifier->deskripsi) }}"
                                        placeholder="Keterangan tambahan (opsional)">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===============================================
                     SECTION 2 — Detail Teknis Rectifier
                ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">2</span>
                            Spesifikasi Panel kWh Meter
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-4">
                                <div class="rform-group">
                                    <label class="rform-label">MCB Utama <span class="rform-required">*</span></label>
                                    <input type="text" id="mcbUtama" name="mcb_utama"
                                        class="rform-input decimal-input"
                                        value="{{ old('mcb_utama', $kwh->mcb_utama) }}" required>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Jumlah Phasa <span
                                            class="rform-required">*</span></label>
                                    <select id="jumlahPhasa" name="jumlah_phasa" class="rform-select" required>
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

                                <div class="rform-group">
                                    <label class="rform-label">Merk / Type Arrester</label>
                                    <input type="text" name="merk_type_arrester" class="rform-input"
                                        value="{{ old('merk_type_arrester', $kwh->merk_type_arrester) }}">
                                </div>
                            </div>

                            <div class="rform-row rform-row-1">
                                <div class="rform-group">
                                    <label class="rform-label">Daya Listrik (PS GI) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" id="dayaPsGi" class="rform-input" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===============================================
                     SECTION 3 — Foto Rectifier
                ================================================ --}}
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
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_rn', $kwh->teg_rn) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>R</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusR"
                                                        name="arus_r" class="rform-input decimal-input"
                                                        value="{{ old('arus_r', $kwh->arus_r) }}" required><span
                                                        class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>S - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_sn"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_sn', $kwh->teg_sn) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusS"
                                                        name="arus_s" class="rform-input decimal-input"
                                                        value="{{ old('arus_s', $kwh->arus_s) }}" required><span
                                                        class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>T - N</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_tn"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_tn', $kwh->teg_tn) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td><strong>T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" id="arusT"
                                                        name="arus_t" class="rform-input decimal-input"
                                                        value="{{ old('arus_t', $kwh->arus_t) }}" required><span
                                                        class="unit-text">A</span></div>
                                            </td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>R - S</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rs"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_rs', $kwh->teg_rs) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>S - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_st"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_st', $kwh->teg_st) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="3-only">
                                            <td><strong>R - T</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_rt"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_rt', $kwh->teg_rt) }}" required><span
                                                        class="unit-text">Vac</span></div>
                                            </td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                            <td class="kwh-empty-cell">&mdash;</td>
                                        </tr>
                                        <tr class="phasa-row" data-phasa="always">
                                            <td><strong>N - G</strong></td>
                                            <td>
                                                <div class="input-with-unit"><input type="text" name="teg_ng"
                                                        class="rform-input decimal-input"
                                                        value="{{ old('teg_ng', $kwh->teg_ng) }}" required><span
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
                                    <input type="text" id="totalDayaTerpakai" class="rform-input" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Total Beban (A) <span
                                            class="rform-required">*</span></label>
                                    <input type="text" id="totalBeban" class="rform-input" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===============================================
                     SECTION 4 — Serial Number Modul
                ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header"
                            style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="rform-section-step">4</span>
                                Serial Number (Modul)
                                <span id="slotStatusBadge" class="rform-section-sub">Terpasang:
                                    {{ $rectifier->modules->count() }} dari {{ $rectifier->kapasitas_slot }} slot
                                    tersedia</span>
                            </div>
                        </div>
                        <div class="rform-section-body">

                            <div class="rform-module-columns" id="moduleContainer">
                                @foreach ($rectifier->modules as $i => $module)
                                    <div class="rform-module-col" id="modul-{{ $i }}">
                                        <label class="rform-module-label">Modul {{ $i + 1 }}</label>
                                        <input type="hidden" name="modules[{{ $i }}][id]"
                                            value="{{ $module->id }}">
                                        <input type="text" name="modules[{{ $i }}][sn_modul]"
                                            class="rform-input"
                                            value="{{ old('modules.' . $i . '.sn_modul', $module->sn_modul) }}"
                                            placeholder="SN Modul">
                                        <input type="hidden" name="modules[{{ $i }}][kapasitas_ampere]"
                                            class="modul-kapasitas-input"
                                            value="{{ old('modules.' . $i . '.kapasitas_ampere', $module->kapasitas_ampere) }}">
                                        <button type="button" class="rform-module-remove"
                                            onclick="removeModul({{ $i }})">
                                            <i class="bi bi-x-circle"></i> Hapus
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div id="noModuleHint"
                                style="{{ $rectifier->modules->count() > 0 ? 'display:none;' : 'display:block;' }} padding: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; margin-bottom: 16px; color: #64748b; font-size: 0.82rem;">
                                <i class="bi bi-info-circle" style="color: #3b82f6; margin-right: 4px;"></i>
                                Isi <strong>Jumlah Slot Modul</strong> pada bagian detail teknis di atas, lalu klik
                                tombol <strong>+ Tambah Modul</strong> untuk mendaftarkan modul yang terpasang.
                            </div>

                            <button type="button" class="btn-tambah-modul" id="btnTambahModul"
                                onclick="tambahModul()">
                                <i class="bi bi-plus-lg"></i> Tambah Modul
                            </button>
                        </div>
                    </div>

                    {{-- ===============================================
                     SECTION 5 — Output MCB
                ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <span class="rform-section-step">5</span>
                            Output (MCB)
                            <span class="rform-section-sub">Opsional — dapat diisi secara bertahap</span>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-output-grid">
                                {{-- Tabel Kiri (MCB 1 - 6) --}}
                                <table class="rform-output-table">
                                    <thead>
                                        <tr>
                                            <th>MCB</th>
                                            <th>Merk</th>
                                            <th>Kapasitas</th>
                                            <th>Peruntukan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($idx = 0; $idx < 6; $idx++)
                                            @php $existingOutput = $rectifier->outputs->get($idx); @endphp
                                            <tr>
                                                <td class="mcb-label">MCB {{ $idx + 1 }}</td>
                                                <td>
                                                    <input type="hidden"
                                                        name="outputs[{{ $idx }}][nama_mcb]"
                                                        value="MCB {{ $idx + 1 }}">
                                                    @if ($existingOutput)
                                                        <input type="hidden" name="outputs[{{ $idx }}][id]"
                                                            value="{{ $existingOutput->id }}">
                                                    @endif
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][merk_mcb]"
                                                        value="{{ old('outputs.' . $idx . '.merk_mcb', $existingOutput->merk_mcb ?? '') }}"
                                                        placeholder="Merk">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][kapasitas_mcb]"
                                                        value="{{ old('outputs.' . $idx . '.kapasitas_mcb', $existingOutput->kapasitas_mcb ?? '') }}"
                                                        placeholder="Kapasitas">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][peruntukan]"
                                                        value="{{ old('outputs.' . $idx . '.peruntukan', $existingOutput->peruntukan ?? '') }}"
                                                        placeholder="Peruntukan">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>

                                {{-- Tabel Kanan (MCB 7 - 12) --}}
                                <table class="rform-output-table">
                                    <thead>
                                        <tr>
                                            <th>MCB</th>
                                            <th>Merk</th>
                                            <th>Kapasitas</th>
                                            <th>Peruntukan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($idx = 0; $idx < 6; $idx++)
                                            @php
                                                $i = $idx + 6;
                                                $existingOutput = $rectifier->outputs->get($i);
                                            @endphp
                                            <tr>
                                                <td class="mcb-label">MCB {{ $i + 1 }}</td>
                                                <td>
                                                    <input type="hidden"
                                                        name="outputs[{{ $i }}][nama_mcb]"
                                                        value="MCB {{ $i + 1 }}">
                                                    @if ($existingOutput)
                                                        <input type="hidden" name="outputs[{{ $i }}][id]"
                                                            value="{{ $existingOutput->id }}">
                                                    @endif
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][merk_mcb]"
                                                        value="{{ old('outputs.' . $i . '.merk_mcb', $existingOutput->merk_mcb ?? '') }}"
                                                        placeholder="Merk">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][kapasitas_mcb]"
                                                        value="{{ old('outputs.' . $i . '.kapasitas_mcb', $existingOutput->kapasitas_mcb ?? '') }}"
                                                        placeholder="Kapasitas">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][peruntukan]"
                                                        value="{{ old('outputs.' . $i . '.peruntukan', $existingOutput->peruntukan ?? '') }}"
                                                        placeholder="Peruntukan">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ---- Tombol Batal & Simpan ---- --}}
                    <div class="rform-actions">
                        <a href="{{ route('rectifiers.show', [$pop->id, $rectifier->id]) }}"
                            class="rform-btn-reset">Batal</a>
                        <button type="button" class="rform-btn-simpan" onclick="konfirmasiSimpan()">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        function parseDecimal(value) {
            if (!value) return 0;
            return parseFloat(String(value).replace(',', '.')) || 0;
        }

        function formatDecimal(value, decimals = 2) {
            return value.toFixed(decimals).replace('.', ',');
        }

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

        document.getElementById('mcbUtama').addEventListener('input', hitungDayaPsGi);
        document.getElementById('jumlahPhasa').addEventListener('change', function() {
            hitungDayaPsGi();
            togglePhasaRows();
        });
        document.getElementById('arusR').addEventListener('input', hitungTotalDanBeban);
        document.getElementById('arusS').addEventListener('input', hitungTotalDanBeban);
        document.getElementById('arusT').addEventListener('input', hitungTotalDanBeban);

        document.getElementById('kwhEditForm').addEventListener('submit', function() {
            document.querySelectorAll('.decimal-input').forEach(function(input) {
                input.value = input.value.replace(',', '.');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            togglePhasaRows();
            hitungDayaPsGi();
        });

        // ---- Auto-hitung Utilisasi = (Beban / Kapasitas Rectifier) * 100 ----
        function getUtilisasiBadge(nilai) {
            if (nilai <= 50) {
                return {
                    label: 'Safe',
                    bg: '#dcfce7',
                    color: '#16a34a',
                    border: '#bbf7d0'
                };
            } else if (nilai <= 70) {
                return {
                    label: 'Warning',
                    bg: '#fef9c3',
                    color: '#ca8a04',
                    border: '#fde68a'
                };
            } else {
                return {
                    label: 'Alert',
                    bg: '#fee2e2',
                    color: '#dc2626',
                    border: '#fecaca'
                };
            }
        }

        function hitungUtilisasi() {
            const bebanEl = document.getElementById('beban');
            const kapEl = document.getElementById('kapasitas_rectifier');
            const utilisasiEl = document.getElementById('utilisasi');
            const badge = document.getElementById('utilisasi_badge');

            const beban = parseFloat(bebanEl?.value);
            const kap = parseFloat(kapEl?.value);

            if (!isNaN(beban) && !isNaN(kap) && kap > 0) {
                const hasil = ((beban / kap) * 100).toFixed(2);
                utilisasiEl.value = hasil;

                if (badge) {
                    const s = getUtilisasiBadge(parseFloat(hasil));
                    badge.textContent = s.label;
                    badge.style.cssText = `
                    position:absolute; right:10px; top:50%; transform:translateY(-50%);
                    display:inline-flex; align-items:center; padding:2px 10px;
                    border-radius:999px; font-size:0.7rem; font-weight:700;
                    background:${s.bg}; color:${s.color}; border:1px solid ${s.border};
                    white-space:nowrap;
                `;
                }
            } else {
                utilisasiEl.value = '';
                if (badge) badge.textContent = '';
            }
        }

        function getMaxSlot() {
            const slotInput = document.getElementById('kapasitas_slot');
            const val = slotInput ? parseInt(slotInput.value) : 0;
            return isNaN(val) ? 0 : val;
        }

        function updateSlotStatusBadge() {
            const maxSlot = getMaxSlot();
            const currentCount = document.querySelectorAll('.rform-module-col').length;
            const badge = document.getElementById('slotStatusBadge');
            const hint = document.getElementById('noModuleHint');

            if (hint) {
                hint.style.display = currentCount === 0 ? 'block' : 'none';
            }

            if (badge) {
                if (maxSlot <= 0) {
                    badge.innerText = 'Tentukan Jumlah Slot Modul di atas terlebih dahulu';
                    badge.style.color = '#ef4444';
                } else {
                    badge.innerText = `Terpasang: ${currentCount} dari ${maxSlot} slot tersedia`;
                    badge.style.color = currentCount > maxSlot ? '#ef4444' : '#16a34a';
                }
            }
        }

        function reindexModules() {
            const cols = document.querySelectorAll('.rform-module-col');
            cols.forEach((col, idx) => {
                col.id = 'modul-' + idx;
                const label = col.querySelector('.rform-module-label');
                if (label) label.innerText = 'Modul ' + (idx + 1);

                const snInput = col.querySelector('input[name*="[sn_modul]"]');
                if (snInput) snInput.name = `modules[${idx}][sn_modul]`;

                const kapInput = col.querySelector('input[name*="[kapasitas_ampere]"]');
                if (kapInput) kapInput.name = `modules[${idx}][kapasitas_ampere]`;

                const removeBtn = col.querySelector('.rform-module-remove');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeModul(${idx})`);
                }
            });
            updateJumlahModul();
            updateSlotStatusBadge();
        }

        function showSwalAlert(icon, title, text, callback) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Mengerti'
                }).then(() => {
                    if (callback) callback();
                });
            } else {
                alert(text);
                if (callback) callback();
            }
        }

        function tambahModul() {
            const maxSlot = getMaxSlot();

            if (maxSlot <= 0) {
                showSwalAlert(
                    'warning',
                    'Jumlah Slot Belum Diisi',
                    'Silakan tentukan "Jumlah Slot Modul" terlebih dahulu di bagian Detail Teknis Rectifier!',
                    () => {
                        const slotInput = document.getElementById('kapasitas_slot');
                        if (slotInput) {
                            slotInput.focus();
                            slotInput.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                );
                return;
            }

            const container = document.getElementById('moduleContainer');
            const currentCount = container.querySelectorAll('.rform-module-col').length;

            if (currentCount >= maxSlot) {
                showSwalAlert(
                    'error',
                    'Kapasitas Penuh!',
                    `Jumlah modul tidak boleh melebihi Jumlah Slot Modul (${maxSlot}).`
                );
                return;
            }

            const idx = currentCount;
            const col = document.createElement('div');
            col.className = 'rform-module-col';
            col.id = 'modul-' + idx;
            col.innerHTML = `
            <label class="rform-module-label">Modul ${idx + 1}</label>
            <input type="text" name="modules[${idx}][sn_modul]" class="rform-input" placeholder="SN Modul">
            <input type="hidden" name="modules[${idx}][kapasitas_ampere]" class="modul-kapasitas-input"
                   value="${document.getElementById('kapasitas_modul')?.value || ''}">
            <button type="button" class="rform-module-remove" onclick="removeModul(${idx})">
                <i class="bi bi-x-circle"></i> Hapus
            </button>
        `;
            container.appendChild(col);
            reindexModules();
        }

        function removeModul(idx) {
            const el = document.getElementById('modul-' + idx);
            if (el) {
                el.remove();
                reindexModules();
            }
        }

        // Sync kapasitas_modul ke semua hidden modul inputs
        document.getElementById('kapasitas_modul')?.addEventListener('input', function() {
            document.querySelectorAll('.modul-kapasitas-input').forEach(inp => {
                inp.value = this.value;
            });
        });

        // Update status saat kapasitas_slot diubah
        document.getElementById('kapasitas_slot')?.addEventListener('input', function() {
            const maxSlot = getMaxSlot();
            const currentCount = document.querySelectorAll('.rform-module-col').length;
            if (maxSlot > 0 && currentCount > maxSlot) {
                showSwalAlert(
                    'warning',
                    'Perhatian',
                    `Jumlah modul yang terpasang (${currentCount}) melebihi Jumlah Slot Modul yang baru (${maxSlot}). Silakan sesuaikan modul yang terpasang.`
                );
            }
            updateSlotStatusBadge();
        });

        // Hitung jumlah modul otomatis
        function updateJumlahModul() {
            const currentCount = document.querySelectorAll('.rform-module-col').length;
            const jmlInput = document.getElementById('jumlah_modul_input');
            if (jmlInput) jmlInput.value = currentCount;
        }

        // Mencegah form auto-submit saat menekan tombol Enter pada input field
        document.getElementById('rectifierForm')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });

        // ---- Init pada load ----
        document.addEventListener('DOMContentLoaded', function() {
            updateSlotStatusBadge();
            updateJumlahModul();

            // Tampilkan badge utilisasi berdasarkan nilai existing
            const utilisasiEl = document.getElementById('utilisasi');
            if (utilisasiEl && utilisasiEl.value) {
                const badge = document.getElementById('utilisasi_badge');
                const s = getUtilisasiBadge(parseFloat(utilisasiEl.value));
                if (badge) {
                    badge.textContent = s.label;
                    badge.style.cssText = `
                    position:absolute; right:10px; top:50%; transform:translateY(-50%);
                    display:inline-flex; align-items:center; padding:2px 10px;
                    border-radius:999px; font-size:0.7rem; font-weight:700;
                    background:${s.bg}; color:${s.color}; border:1px solid ${s.border};
                    white-space:nowrap;
                `;
                }
            }
        });

        // ---- Photo preview ----
        function previewFoto(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('fotoPreview').src = e.target.result;
                document.getElementById('fotoPreview').style.display = 'block';
                document.getElementById('fotoEmpty').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // Drag & drop support
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.style.background = '#dbeafe';
            });
            dropZone.addEventListener('dragleave', () => {
                dropZone.style.background = '';
            });
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.style.background = '';
                const file = e.dataTransfer.files[0];
                if (file) {
                    document.getElementById('fotoInput').files = e.dataTransfer.files;
                    previewFoto({
                        files: [file]
                    });
                }
            });
        }

        // ---- Reset ----
        function resetForm() {
            document.getElementById('rectifierForm').reset();
            document.getElementById('moduleContainer').innerHTML = '';
            document.getElementById('fotoPreview').style.display = 'none';
            document.getElementById('fotoEmpty').style.display = 'flex';
            updateSlotStatusBadge();
            updateJumlahModul();
        }

        // ---- Konfirmasi Simpan Perubahan ----
        function konfirmasiSimpan() {
            Swal.fire({
                icon: 'question',
                title: 'Simpan Perubahan?',
                text: 'Pastikan semua data sudah benar sebelum menyimpan.',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rectifierForm').submit();
                }
            });
        }
    </script>
</body>

</html>
