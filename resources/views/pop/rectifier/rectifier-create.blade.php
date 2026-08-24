<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Rectifier - {{ $pop->nama_pop }} - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/rectifier-form.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                {{-- Back --}}

                <x-breadcrumb :items="[
                    ['label' => 'POP', 'route' => 'pops.index'],
                    ['label' => $pop->nama_pop . ': Rectifier', 'route' => 'rectifiers.index', 'params' => ['pop' => $pop->id]],
                    ['label' => 'Tambah Rectifier'],
                ]" />

                <a href="{{ route('rectifiers.index', $pop->id) }}" class="rform-back" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
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

                <form id="rectifierForm" method="POST" action="{{ route('rectifiers.store', $pop->id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- ===============================================
                     SECTION 1 — Information Rectifier (Header)
                ================================================ --}}
                    <div class="rform-section">
                        <div class="rform-section-header">Information Rectifier</div>
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
                                        value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}">
                                </div>

                                <div class="rform-group">
                                    <label class="rform-label">PIC <span class="rform-required">*</span></label>
                                    <input type="text" name="pic"
                                        class="rform-input {{ $errors->has('pic') ? 'is-invalid' : '' }}"
                                        value="{{ old('pic', Auth::user()->name ?? '') }}"
                                        placeholder="Nama penanggung jawab">
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
                                        value="{{ old('nama_alias') }}" placeholder="Contoh: Rectifier Utama 1">
                                    @error('nama_alias')
                                        <span class="rform-error">{{ $message }}</span>
                                    @enderror
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
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-2">

                                {{-- Kolom Kiri --}}
                                <div style="display:flex; flex-direction:column; gap:14px;">

                                    <div class="rform-group">
                                        <label class="rform-label">Merk <span class="rform-required">*</span></label>
                                        <input type="text" name="merk"
                                            class="rform-input {{ $errors->has('merk') ? 'is-invalid' : '' }}"
                                            value="{{ old('merk') }}" placeholder="Contoh: EMERSON">
                                        @error('merk')
                                            <span class="rform-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type <span class="rform-required">*</span></label>
                                        <input type="text" name="type"
                                            class="rform-input {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                            value="{{ old('type') }}" placeholder="Contoh: NetSure 531 A91-S1">
                                        @error('type')
                                            <span class="rform-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">SN Rectifier <span
                                                class="rform-required">*</span></label>
                                        <input type="text" name="sn_rectifier"
                                            class="rform-input {{ $errors->has('sn_rectifier') ? 'is-invalid' : '' }}"
                                            value="{{ old('sn_rectifier') }}" placeholder="Serial Number">
                                        @error('sn_rectifier')
                                            <span class="rform-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Couple / tidak</label>
                                        <input type="text" name="couple" class="rform-input"
                                            value="{{ old('couple') }}" placeholder="Contoh: COUPLE">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type Modul Controller</label>
                                        <input type="text" name="type_modul_controller" class="rform-input"
                                            value="{{ old('type_modul_controller') }}"
                                            placeholder="Contoh: MCU M800D">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Jumlah Slot Modul <span
                                                class="rform-required">*</span></label>
                                        <input type="number" name="kapasitas_slot" id="kapasitas_slot"
                                            class="rform-input {{ $errors->has('kapasitas_slot') ? 'is-invalid' : '' }}"
                                            value="{{ old('kapasitas_slot') }}" placeholder="Contoh: 9"
                                            min="1">
                                        @error('kapasitas_slot')
                                            <span class="rform-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                {{-- Kolom Kanan --}}
                                <div style="display:flex; flex-direction:column; gap:14px;">

                                    <div class="rform-group">
                                        <label class="rform-label">Kapasitas Modul Terpasang</label>
                                        <input type="text" name="kapasitas_modul" id="kapasitas_modul"
                                            class="rform-input" value="{{ old('kapasitas_modul') }}"
                                            placeholder="Contoh: 40 A DC / 13 A AC">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Jumlah Modul Power Terpasang</label>
                                        <input type="number" name="jumlah_modul" id="jumlah_modul_input"
                                            class="rform-input" value="{{ old('jumlah_modul') }}"
                                            placeholder="Otomatis dari modul SN" readonly
                                            style="background:#f1f5f9; color:#94a3b8;">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Type Modul Power</label>
                                        <input type="text" name="type_modul_power" class="rform-input"
                                            value="{{ old('type_modul_power') }}" placeholder="Contoh: R48-2000e3">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Kapasitas Rectifier</label>
                                        <input type="text" name="kapasitas_rectifier" class="rform-input"
                                            value="{{ old('kapasitas_rectifier') }}" placeholder="Contoh: 200 A">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Beban (A)</label>
                                        <input type="text" name="beban" class="rform-input"
                                            value="{{ old('beban') }}" placeholder="Contoh: 36.5">
                                    </div>

                                    <div class="rform-group">
                                        <label class="rform-label">Utilisasi Rectifier (%)</label>
                                        <input type="text" name="utilisasi" id="utilisasi" class="rform-input"
                                            value="{{ old('utilisasi') }}" placeholder="Contoh: 18.2">
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
                            Foto Rectifier
                            <span class="rform-section-sub">Upload foto kondisi rectifier di lokasi</span>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-photo-grid">
                                <div class="rform-drop-zone" id="dropZone"
                                    onclick="document.getElementById('fotoInput').click()">
                                    <div class="rform-drop-icon">
                                        <i class="bi bi-cloud-arrow-up-fill"></i>
                                    </div>
                                    <div class="rform-drop-text">Masukkan file disini</div>
                                    <button type="button" class="rform-browse-btn">Browse</button>
                                    <div class="rform-drop-hint">Format: JPG, JPEG, PNG • Maks. ukuran: 10 MB</div>
                                    <input type="file" id="fotoInput" name="foto_rectifier"
                                        accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewFoto(this)">
                                </div>

                                <div class="rform-photo-preview">
                                    <span class="rform-preview-label">Preview foto</span>
                                    <div class="rform-preview-box">
                                        <img id="fotoPreview" src="" alt="" style="display:none;">
                                        <div class="rform-preview-empty" id="fotoEmpty">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
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
                            <div>
                                Serial Number (Modul)
                                <span id="slotStatusBadge" class="rform-section-sub">Tentukan Jumlah Slot Modul di
                                    atas terlebih dahulu</span>
                            </div>
                        </div>
                        <div class="rform-section-body">

                            <div class="rform-module-columns" id="moduleContainer">
                                @if (old('modules'))
                                    @foreach (old('modules') as $i => $mod)
                                        <div class="rform-module-col" id="modul-{{ $i }}">
                                            <label class="rform-module-label">Modul {{ $i + 1 }}</label>
                                            <input type="text" name="modules[{{ $i }}][sn_modul]"
                                                class="rform-input" value="{{ $mod['sn_modul'] ?? '' }}"
                                                placeholder="SN Modul">
                                            <input type="hidden"
                                                name="modules[{{ $i }}][kapasitas_ampere]"
                                                class="modul-kapasitas-input"
                                                value="{{ $mod['kapasitas_ampere'] ?? '' }}">
                                            <button type="button" class="rform-module-remove"
                                                onclick="removeModul({{ $i }})">
                                                <i class="bi bi-x-circle"></i> Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div id="noModuleHint"
                                style="{{ old('modules') ? 'display:none;' : 'display:block;' }} padding: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; margin-bottom: 16px; color: #64748b; font-size: 0.82rem;">
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
                        <div class="rform-section-header">Output (MCB)</div>
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
                                            <tr>
                                                <td class="mcb-label">MCB {{ $idx + 1 }}</td>
                                                <td>
                                                    <input type="hidden"
                                                        name="outputs[{{ $idx }}][nama_mcb]"
                                                        value="MCB {{ $idx + 1 }}">
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][merk_mcb]"
                                                        value="{{ old('outputs.' . $idx . '.merk_mcb') }}"
                                                        placeholder="Merk">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][kapasitas_mcb]"
                                                        value="{{ old('outputs.' . $idx . '.kapasitas_mcb') }}"
                                                        placeholder="Kapasitas">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $idx }}][peruntukan]"
                                                        value="{{ old('outputs.' . $idx . '.peruntukan') }}"
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
                                            @php $i = $idx + 6; @endphp
                                            <tr>
                                                <td class="mcb-label">MCB {{ $i + 1 }}</td>
                                                <td>
                                                    <input type="hidden"
                                                        name="outputs[{{ $i }}][nama_mcb]"
                                                        value="MCB {{ $i + 1 }}">
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][merk_mcb]"
                                                        value="{{ old('outputs.' . $i . '.merk_mcb') }}"
                                                        placeholder="Merk">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][kapasitas_mcb]"
                                                        value="{{ old('outputs.' . $i . '.kapasitas_mcb') }}"
                                                        placeholder="Kapasitas">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="outputs[{{ $i }}][peruntukan]"
                                                        value="{{ old('outputs.' . $i . '.peruntukan') }}"
                                                        placeholder="Peruntukan">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ---- Tombol Reset & Simpan ---- --}}
                    <div class="rform-actions"
                        style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                        <!-- Tombol Reset dengan style merah tegas -->
                        <button type="button" class="rform-btn-reset" onclick="confirmReset()"
                            style="background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 10px 22px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                        </button>

                        <!-- Tombol Simpan -->
                        <button type="button" class="rform-btn-simpan" onclick="confirmSubmit()"
                            style="background-color: #0284c7; color: white; border: none; padding: 10px 26px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i class="bi bi-check-lg"></i> Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
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

        function tambahModul() {
            const maxSlot = getMaxSlot();

            // 1. Validasi jika jumlah slot belum diisi
            if (maxSlot <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Slot Belum Diisi',
                    text: 'Silakan tentukan "Jumlah Slot Modul" terlebih dahulu pada bagian Detail Teknis Rectifier.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }

            const container = document.getElementById('moduleContainer');
            const currentCount = container.querySelectorAll('.rform-module-col').length;

            // 2. Validasi kapasitas penuh
            if (currentCount >= maxSlot) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kapasitas Penuh!',
                    html: `Jumlah modul tidak boleh melebihi <strong>Jumlah Slot Modul (${maxSlot})</strong>.`,
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            // 3. Tambahkan modul baru
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
                Swal.fire({
                    icon: 'warning',
                    title: 'Kelebihan Modul!',
                    text: `Jumlah modul yang terpasang (${currentCount}) melebihi Jumlah Slot Modul yang baru (${maxSlot}). Silakan sesuaikan modul yang terpasang.`,
                    confirmButtonColor: '#f59e0b'
                });
            }
            updateSlotStatusBadge();
        });

        // Hitung jumlah modul otomatis
        function updateJumlahModul() {
            const currentCount = document.querySelectorAll('.rform-module-col').length;
            const jmlInput = document.getElementById('jumlah_modul_input');
            if (jmlInput) jmlInput.value = currentCount;
        }

        // Initial check saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', function() {
            updateSlotStatusBadge();
            updateJumlahModul();
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
        function confirmReset() {
            Swal.fire({
                title: 'Reset Seluruh Form?',
                text: 'Semua isian data teknis, serial number modul, dan foto yang sudah diinput akan dikosongkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Kosongkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rectifierForm').reset();
                    document.getElementById('moduleContainer').innerHTML = '';
                    document.getElementById('fotoPreview').src = '';
                    document.getElementById('fotoPreview').style.display = 'none';
                    document.getElementById('fotoEmpty').style.display = 'flex';
                    updateSlotStatusBadge();
                    updateJumlahModul();

                    Swal.fire({
                        icon: 'success',
                        title: 'Form Telah Dikosongkan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Konfirmasi SweetAlert2 untuk Simpan Data
        function confirmSubmit() {
            const form = document.getElementById('rectifierForm');

            // Cek validasi HTML bawaan (misal field required yang masih kosong)
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Simpan Data Rectifier?',
                text: 'Pastikan seluruh parameter teknis dan nomor seri modul sudah sesuai.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>
