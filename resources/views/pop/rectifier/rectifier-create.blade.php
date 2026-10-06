<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tambah Rectifier - {{ $pop->nama_pop_display }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <a href="{{ route('rectifiers.index', $pop->id) }}" class="rform-back" title="Kembali ke List Rectifier" aria-label="Kembali ke List Rectifier">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="rform-header-text">
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->kode_pop . ': Rectifier', 'route' => 'rectifiers.index', 'params' => ['pop' => $pop->id]],
                            ['label' => 'Tambah Rectifier'],
                        ]" />
                    </div>
                </div>

                {{-- Flash Message --}}
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

                <form id="rectifierForm" method="POST" action="{{ route('rectifiers.store', $pop->id) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ===============================================
                         SECTION 1 — Information Rectifier (Header)
                    ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-info-circle-fill" style="color:#2563eb; margin-right:8px;"></i>
                            Information Rectifier
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-4">

                                <div class="rform-group">
                                    <label class="rform-label">POP</label>
                                    <input type="text" class="rform-input" value="{{ $pop->nama_pop_display }}" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Type POP</label>
                                    <input type="text" class="rform-input" value="{{ $pop->tipe_pop ?? '' }}" readonly>
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">Tanggal Pemeriksaan <span class="rform-required">*</span></label>
                                    <input type="date" name="tanggal_pemeriksaan" class="rform-input" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}">
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">PIC <span class="rform-required">*</span></label>
                                    <input type="text" name="pic" class="rform-input {{ $errors->has('pic') ? 'is-invalid' : '' }}"
                                        value="{{ old('pic', Auth::user()->name ?? '') }}" placeholder="Nama penanggung jawab">
                                    @error('pic') <span class="rform-error">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            {{-- Nomor Recti (auto-generate, read-only) --}}
                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Nomor Recti
                                        <small style="font-weight:400; color:#94a3b8;">(auto-generate)</small>
                                    </label>
                                    <input type="text" class="rform-input" value="{{ $suggestedNomorRecti ?? ($pop->kode_pop . '_RECT01') }}" readonly
                                        style="background:#f1f5f9; color:#64748b; cursor:not-allowed;">
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Deskripsi</label>
                                    <input type="text" name="deskripsi" class="rform-input"
                                        value="{{ old('deskripsi') }}" placeholder="Keterangan tambahan (opsional)">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===============================================
                         SECTION 2 — Detail Teknis Rectifier
                    ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-cpu-fill" style="color:#2563eb; margin-right:8px;"></i>
                            Detail Teknis Rectifier
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-2">

                                {{-- Kolom Kiri --}}
                                <div style="display:flex; flex-direction:column; gap:14px;">

                                    <div class="rform-group">
                                        <label class="rform-label">Merk <span class="rform-required">*</span></label>
                                        <input type="text" name="merk" class="rform-input {{ $errors->has('merk') ? 'is-invalid' : '' }}"
                                            value="{{ old('merk') }}" placeholder="Contoh: EMERSON">
                                        @error('merk') <span class="rform-error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type <span class="rform-required">*</span></label>
                                        <input type="text" name="type" class="rform-input {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                            value="{{ old('type') }}" placeholder="Contoh: NetSure 531 A91-S1">
                                        @error('type') <span class="rform-error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">SN Rectifier <span class="rform-required">*</span></label>
                                        <input type="text" name="sn_rectifier" class="rform-input {{ $errors->has('sn_rectifier') ? 'is-invalid' : '' }}"
                                            value="{{ old('sn_rectifier') }}" placeholder="Serial Number">
                                        @error('sn_rectifier') <span class="rform-error">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Couple / tidak</label>
                                        <input type="text" name="couple" class="rform-input"
                                            value="{{ old('couple') }}" placeholder="Contoh: COUPLE">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type Modul Controller</label>
                                        <input type="text" name="type_modul_controller" class="rform-input"
                                            value="{{ old('type_modul_controller') }}" placeholder="Contoh: MCU M800D">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Jumlah Slot Modul <span class="rform-required">*</span></label>
                                        <input type="number" name="kapasitas_slot" id="kapasitas_slot"
                                            class="rform-input {{ $errors->has('kapasitas_slot') ? 'is-invalid' : '' }}"
                                            value="{{ old('kapasitas_slot') }}" placeholder="Contoh: 9" min="1">
                                        @error('kapasitas_slot') <span class="rform-error">{{ $message }}</span> @enderror
                                    </div>

                                </div>

                                {{-- Kolom Kanan --}}
                                <div style="display:flex; flex-direction:column; gap:14px;">

                                    <div class="rform-group">
                                        <label class="rform-label">Kapasitas Modul Terpasang</label>
                                        <input type="text" name="kapasitas_modul" id="kapasitas_modul" class="rform-input"
                                            value="{{ old('kapasitas_modul') }}" placeholder="Contoh: 40 A DC / 13 A AC">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Jumlah Modul Power Terpasang</label>
                                        <input type="number" name="jumlah_modul" id="jumlah_modul_input" class="rform-input"
                                            value="{{ old('jumlah_modul') }}" placeholder="Otomatis dari modul SN" readonly
                                            style="background:#f1f5f9; color:#94a3b8;">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type Modul Power</label>
                                        <input type="text" name="type_modul_power" class="rform-input"
                                            value="{{ old('type_modul_power') }}" placeholder="Contoh: R48-2000e3">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Kapasitas Rectifier <small style="font-weight:400;color:#94a3b8">(A)</small></label>
                                        <input type="text" name="kapasitas_rectifier" id="kapasitas_rectifier" class="rform-input"
                                            value="{{ old('kapasitas_rectifier') }}" placeholder="Contoh: 200" oninput="hitungUtilisasi()">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Beban <small style="font-weight:400;color:#94a3b8">(A)</small></label>
                                        <input type="text" name="beban" id="beban" class="rform-input"
                                            value="{{ old('beban') }}" placeholder="Contoh: 36.5" oninput="hitungUtilisasi()">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label" style="display:flex;align-items:center;gap:6px;">
                                            Utilisasi Rectifier (%)
                                            <span class="rform-badge-auto">Auto</span>
                                        </label>
                                        <div style="position:relative;">
                                            <input type="text" name="utilisasi" id="utilisasi" class="rform-input"
                                                value="{{ old('utilisasi') }}" placeholder="Otomatis: Beban ÷ Kapasitas × 100" readonly
                                                style="background:#f1f5f9; color:#334155; cursor:not-allowed; padding-right:80px;">
                                            <span id="utilisasi_badge" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);"></span>
                                        </div>
                                        <small style="color:#94a3b8;font-size:0.72rem;margin-top:3px;display:block;">
                                            <i class="bi bi-info-circle"></i> Terisi otomatis dari Beban ÷ Kapasitas Rectifier × 100
                                        </small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===============================================
                         SECTION 3 — Foto Rectifier
                    ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-camera-fill" style="color:#2563eb; margin-right:8px;"></i>
                            Foto Rectifier
                            <span class="rform-section-sub">Upload foto kondisi rectifier di lokasi</span>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-photo-grid">
                                <div class="rform-photo-col">
                                    <span class="rform-preview-label">Unggah Foto</span>
                                    <div class="rform-drop-zone" id="dropZone" onclick="document.getElementById('fotoInput').click()">
                                        <div class="rform-drop-icon">
                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                        </div>
                                        <div class="rform-drop-text" id="dropText">Masukkan file disini</div>
                                        <button type="button" class="rform-browse-btn">Browse</button>
                                        <div class="rform-drop-hint">Format: JPG, JPEG, PNG &bull; Maks. ukuran: 2 MB</div>
                                        <input type="file" id="fotoInput" name="foto_rectifier" accept=".jpg,.jpeg,.png"
                                            style="display:none;" onchange="previewFoto(this)">
                                    </div>
                                </div>

                                <div class="rform-photo-col">
                                    <span class="rform-preview-label">Preview Foto</span>
                                    <div class="rform-preview-box">
                                        <img id="fotoPreview" src="" alt="" style="display:none;">
                                        <div class="rform-preview-empty" id="fotoEmpty">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('foto_rectifier')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ===============================================
                         SECTION 4 — Serial Number Modul
                    ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-grid-3x3-gap-fill" style="color:#2563eb; margin-right:4px;"></i>
                                Serial Number (Modul)
                                <span id="slotStatusBadge" class="rform-section-sub">Tentukan Jumlah Slot Modul di atas terlebih dahulu</span>
                            </div>
                        </div>
                        <div class="rform-section-body">

                            <div class="rform-module-columns" id="moduleContainer">
                                @if(old('modules'))
                                    @foreach(old('modules') as $i => $mod)
                                        <div class="rform-module-col" id="modul-{{ $i }}">
                                            <label class="rform-module-label">Modul {{ $i + 1 }}</label>
                                            <input type="text" name="modules[{{ $i }}][sn_modul]"
                                                class="rform-input" value="{{ $mod['sn_modul'] ?? '' }}" placeholder="SN Modul">
                                            <input type="hidden" name="modules[{{ $i }}][kapasitas_ampere]"
                                                class="modul-kapasitas-input" value="{{ $mod['kapasitas_ampere'] ?? '' }}">
                                            <button type="button" class="rform-module-remove" onclick="removeModul({{ $i }})">
                                                <i class="bi bi-x-circle"></i> Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div id="noModuleHint" style="{{ old('modules') ? 'display:none;' : 'display:block;' }} padding: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; margin-bottom: 16px; color: #64748b; font-size: 0.82rem;">
                                <i class="bi bi-info-circle" style="color: #3b82f6; margin-right: 4px;"></i>
                                Isi <strong>Jumlah Slot Modul</strong> pada bagian detail teknis di atas, lalu klik tombol <strong>+ Tambah Modul</strong> untuk mendaftarkan modul yang terpasang.
                            </div>

                            <button type="button" class="btn-tambah-modul" id="btnTambahModul" onclick="tambahModul()">
                                <i class="bi bi-plus-lg"></i> Tambah Modul
                            </button>
                        </div>
                    </div>

                    {{-- ===============================================
                         SECTION 5 — Output MCB
                    ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-toggles2" style="color:#2563eb; margin-right:4px;"></i>
                                Output (MCB)
                                <span id="mcbStatusBadge" class="rform-section-sub">Opsional — dapat diisi secara bertahap</span>
                            </div>
                        </div>
                        <div class="rform-section-body">

                            {{-- Field jumlah MCB --}}
                            <div class="rform-group" style="max-width:280px; margin-bottom:16px;">
                                <label class="rform-label">
                                    Jumlah Output (MCB)
                                    <span style="color:#94a3b8; font-weight:400; font-size:0.78rem;">(maks. 50)</span>
                                </label>
                                <input type="number" id="jumlah_output_mcb" name="jumlah_output_mcb"
                                    class="rform-input" min="0" max="50" placeholder="Contoh: 8"
                                    value="{{ old('jumlah_output_mcb', 0) }}"
                                    oninput="onJumlahMcbChange(this.value)">
                            </div>

                            {{-- Dual-table grid, identik gaya asli --}}
                            <div id="mcbGridWrapper" style="display:none;">
                                <div class="rform-output-grid">
                                    <table class="rform-output-table">
                                        <thead>
                                            <tr>
                                                <th>MCB</th>
                                                <th>Merk</th>
                                                <th>Kapasitas</th>
                                                <th>Peruntukan</th>
                                                <th style="width:36px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="mcbBodyLeft"></tbody>
                                    </table>
                                    <table class="rform-output-table" id="mcbTableRight" style="display:none;">
                                        <thead>
                                            <tr>
                                                <th>MCB</th>
                                                <th>Merk</th>
                                                <th>Kapasitas</th>
                                                <th>Peruntukan</th>
                                                <th style="width:36px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="mcbBodyRight"></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ---- Tombol Reset & Simpan ---- --}}
                    <div class="rform-actions">
                        <button type="button" class="rform-btn-reset" onclick="konfirmasiReset()">Reset</button>
                        <button type="submit" class="rform-btn-simpan">
                            <i class="bi bi-check-lg"></i> Simpan
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        // ---- Auto-hitung Utilisasi = (Beban / Kapasitas Rectifier) * 100 ----
        function getUtilisasiBadge(nilai) {
            if (nilai <= 50) {
                return { label: 'Good', bg: '#dcfce7', color: '#16a34a', border: '#bbf7d0' };
            } else if (nilai <= 70) {
                return { label: 'Warning', bg: '#fef9c3', color: '#ca8a04', border: '#fde68a' };
            } else {
                return { label: 'Alert', bg: '#fee2e2', color: '#dc2626', border: '#fecaca' };
            }
        }

        function hitungUtilisasi() {
            const bebanEl     = document.getElementById('beban');
            const kapEl       = document.getElementById('kapasitas_rectifier');
            const utilisasiEl = document.getElementById('utilisasi');
            const badge       = document.getElementById('utilisasi_badge');

            const beban = parseFloat(bebanEl?.value);
            const kap   = parseFloat(kapEl?.value);

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
                            slotInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
        document.getElementById('kapasitas_modul')?.addEventListener('input', function () {
            document.querySelectorAll('.modul-kapasitas-input').forEach(inp => {
                inp.value = this.value;
            });
        });

        // Update status saat kapasitas_slot diubah
        document.getElementById('kapasitas_slot')?.addEventListener('input', function () {
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
        document.getElementById('rectifierForm')?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });

        // Initial check saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', function () {
            updateSlotStatusBadge();
            updateJumlahModul();

            // Restore old outputs jika ada validasi error
            @if(old('jumlah_output_mcb') > 0)
                (function() {
                    const jumlah = {{ (int) old('jumlah_output_mcb', 0) }};
                    document.getElementById('jumlah_output_mcb').value = jumlah;
                    syncMcbRows(jumlah);
                    // Isi ulang nilai old()
                    @foreach(old('outputs', []) as $oi => $ov)
                        (function() {
                            const tr = document.querySelector('.rform-mcb-row[data-mcb-idx="{{ $oi }}"]');
                            if (!tr) return;
                            const m = tr.querySelector('.mcb-merk'); if (m) m.value = '{{ addslashes($ov["merk_mcb"] ?? "") }}';
                            const k = tr.querySelector('.mcb-kapasitas'); if (k) k.value = '{{ addslashes($ov["kapasitas_mcb"] ?? "") }}';
                            const p = tr.querySelector('.mcb-peruntukan'); if (p) p.value = '{{ addslashes($ov["peruntukan"] ?? "") }}';
                        })();
                    @endforeach
                })();
            @endif
        });

        // ---- Dynamic MCB Output System (table-based) ----
        const MCB_HARD_LIMIT = 50;

        function onJumlahMcbChange(rawVal) {
            let val = parseInt(rawVal);
            if (isNaN(val) || val < 0) val = 0;
            if (val > MCB_HARD_LIMIT) {
                val = MCB_HARD_LIMIT;
                document.getElementById('jumlah_output_mcb').value = MCB_HARD_LIMIT;
                showSwalAlert('warning', 'Batas Maksimal', `Jumlah Output MCB tidak boleh melebihi ${MCB_HARD_LIMIT}.`);
            }
            syncMcbRows(val);
        }

        // Kumpulkan nilai yang sudah diisi agar tidak hilang saat rerender
        function collectMcbValues() {
            const vals = {};
            document.querySelectorAll('.rform-mcb-row').forEach(tr => {
                const idx = parseInt(tr.dataset.mcbIdx);
                vals[idx] = {
                    merk: tr.querySelector('.mcb-merk')?.value || '',
                    kapasitas: tr.querySelector('.mcb-kapasitas')?.value || '',
                    peruntukan: tr.querySelector('.mcb-peruntukan')?.value || '',
                };
            });
            return vals;
        }

        function buildMcbRow(idx, val) {
            const tr = document.createElement('tr');
            tr.className = 'rform-mcb-row';
            tr.dataset.mcbIdx = idx;
            tr.innerHTML = `
                <td class="mcb-label">MCB ${idx + 1}</td>
                <td>
                    <input type="hidden" name="outputs[${idx}][nama_mcb]" value="MCB ${idx + 1}">
                    <input type="text" name="outputs[${idx}][merk_mcb]" class="mcb-merk"
                        placeholder="Merk" value="${escHtml(val.merk)}">
                </td>
                <td>
                    <input type="text" name="outputs[${idx}][kapasitas_mcb]" class="mcb-kapasitas"
                        placeholder="Kapasitas" value="${escHtml(val.kapasitas)}">
                </td>
                <td>
                    <input type="text" name="outputs[${idx}][peruntukan]" class="mcb-peruntukan"
                        placeholder="Peruntukan" value="${escHtml(val.peruntukan)}">
                </td>
                <td style="text-align:center;">
                    <button type="button" onclick="deleteMcbRow(${idx})"
                        style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:1rem;padding:2px 6px;border-radius:4px;line-height:1;"
                        title="Hapus baris ini">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            `;
            return tr;
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // jumlah = total baris yang harus ada setelah sync
        function syncMcbRows(jumlah) {
            const vals = collectMcbValues();
            const leftBody  = document.getElementById('mcbBodyLeft');
            const rightBody = document.getElementById('mcbBodyRight');
            const rightTbl  = document.getElementById('mcbTableRight');
            const wrapper   = document.getElementById('mcbGridWrapper');
            const badge     = document.getElementById('mcbStatusBadge');

            leftBody.innerHTML  = '';
            rightBody.innerHTML = '';

            for (let i = 0; i < jumlah; i++) {
                const val = vals[i] || { merk:'', kapasitas:'', peruntukan:'' };
                const row = buildMcbRow(i, val);
                // Pola: kiri cols 0-5, kanan 6-11, kembali kiri 12-17, dst.
                const blockPos = i % 12;
                if (blockPos < 6) {
                    leftBody.appendChild(row);
                } else {
                    rightBody.appendChild(row);
                }
            }

            // Tampilkan / sembunyikan
            if (wrapper) wrapper.style.display = jumlah > 0 ? '' : 'none';
            if (rightTbl) rightTbl.style.display = jumlah > 6 ? '' : 'none';

            if (badge) {
                if (jumlah === 0) {
                    badge.innerText = 'Opsional — dapat diisi secara bertahap';
                    badge.style.color = '#94a3b8';
                } else {
                    badge.innerText = `${jumlah} Output MCB`;
                    badge.style.color = '#16a34a';
                }
            }
        }

        function deleteMcbRow(delIdx) {
            // Kumpulkan nilai SEBELUM hapus
            const vals = collectMcbValues();
            delete vals[delIdx];

            // Reindex: buat array bersih berurutan
            const newVals = {};
            let newIdx = 0;
            Object.keys(vals).sort((a,b) => a - b).forEach(k => {
                newVals[newIdx++] = vals[k];
            });

            // Kurangi jumlah di input
            const inp = document.getElementById('jumlah_output_mcb');
            const newJumlah = newIdx;
            if (inp) inp.value = newJumlah;

            syncMcbRows(newJumlah);

            // Isi ulang nilai (karena syncMcbRows pakai vals kosong jika newVals berisi data)
            document.querySelectorAll('.rform-mcb-row').forEach(tr => {
                const i = parseInt(tr.dataset.mcbIdx);
                const v = newVals[i];
                if (v) {
                    const m = tr.querySelector('.mcb-merk'); if (m) m.value = v.merk;
                    const k = tr.querySelector('.mcb-kapasitas'); if (k) k.value = v.kapasitas;
                    const p = tr.querySelector('.mcb-peruntukan'); if (p) p.value = v.peruntukan;
                }
            });
        }

        function updateMcbStatusBadge() { /* alias for compat */ syncMcbRows(parseInt(document.getElementById('jumlah_output_mcb')?.value || 0)); }


        // ---- Photo preview ----
        function previewFoto(input) {
            const file = input.files ? input.files[0] : null;
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById('fotoPreview');
                const empty = document.getElementById('fotoEmpty');
                const btnText = document.getElementById('btnBrowseText');
                const btnIcon = document.getElementById('btnBrowseIcon');
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                if (empty) empty.style.display = 'none';
                if (btnText) btnText.textContent = 'Ganti Foto';
                if (btnIcon) btnIcon.className = 'bi bi-arrow-repeat';
            };
            reader.readAsDataURL(file);
        }

        // Drag & drop support
        const previewBox = document.getElementById('dropZone');
        if (previewBox) {
            ['dragenter', 'dragover'].forEach(name => {
                previewBox.addEventListener(name, (e) => {
                    e.preventDefault();
                    previewBox.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(name => {
                previewBox.addEventListener(name, (e) => {
                    e.preventDefault();
                    previewBox.classList.remove('dragover');
                });
            });
            previewBox.addEventListener('drop', e => {
                e.preventDefault();
                previewBox.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('fotoInput').files = files;
                    previewFoto({ files: files });
                }
            });
        }

        // ---- Konfirmasi Reset (gaya sama dengan modal Hapus di halaman card) ----
        function konfirmasiReset() {
            Swal.fire({
                title: 'Reset Form?',
                html: `Apakah Anda yakin ingin mereset form ini?<br><small style="color: #64748b;">Seluruh data yang sudah diisi, termasuk modul dan foto, akan dikosongkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-arrow-counterclockwise"></i> Ya, Reset!',
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
                    resetForm();
                }
            });
        }

        // ---- Reset ----
        function resetForm() {
            document.getElementById('rectifierForm').reset();
            document.getElementById('moduleContainer').innerHTML = '';
            // Reset MCB tables
            document.getElementById('mcbBodyLeft').innerHTML = '';
            document.getElementById('mcbBodyRight').innerHTML = '';
            document.getElementById('jumlah_output_mcb').value = 0;
            syncMcbRows(0);
            const img = document.getElementById('fotoPreview');
            const empty = document.getElementById('fotoEmpty');
            const btnText = document.getElementById('btnBrowseText');
            const btnIcon = document.getElementById('btnBrowseIcon');
            if (img) { img.src = ''; img.style.display = 'none'; }
            if (empty) empty.style.display = 'flex';
            if (btnText) btnText.textContent = 'Pilih Foto';
            if (btnIcon) btnIcon.className = 'bi bi-camera-fill';
            updateSlotStatusBadge();
            updateJumlahModul();
        }
    </script>
</body>

</html>