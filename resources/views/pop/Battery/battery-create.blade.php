<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Tambah Baterai - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-form.css'
    ])
</head>
<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rform-content">
                {{-- HEADER BAR: Back + Breadcrumb As Title --}}
                <div class="rform-header-bar">
                    <a href="{{ route('batteries.index', $pop->id) }}" class="rform-back" title="Kembali ke Daftar Baterai">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="rform-header-text">
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop, 'route' => 'batteries.index', 'params' => ['pop' => $pop->id]],
                            ['label' => 'Tambah Baterai'],
                        ]" />
                        <p class="rform-page-sub">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</p>
                    </div>
                </div>

                {{-- Alert Error Validasi --}}
                @if (isset($errors) && $errors->any())
                    <div class="rform-flash error">
                        <div>
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Terjadi Kesalahan:</strong>
                            <ul style="margin: 4px 0 0 16px; padding: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('batteries.store', $pop->id) }}" method="POST" id="batteryForm">
                    @csrf

                    {{-- SECTION 1: GENERAL INFORMATION --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-info-circle-fill"></i>
                            <h3>General Information</h3>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">POP</label>
                                    <input type="text" class="rform-input" value="{{ $pop->kode_pop }} - {{ $pop->nama_pop }}" readonly style="background:#f1f5f9; cursor:not-allowed;">
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Type POP <span class="rform-required">*</span></label>
                                    <input type="text" name="type_pop" class="rform-input @error('type_pop') is-invalid @enderror" value="{{ old('type_pop', $pop->tipe_pop) }}" placeholder="Masukkan type POP" required>
                                    @error('type_pop') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Building <span class="rform-required">*</span></label>
                                    <input type="text" name="building" class="rform-input @error('building') is-invalid @enderror" value="{{ old('building', $pop->jenis_bangunan) }}" placeholder="Masukkan building" required>
                                    @error('building') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">PIC <span class="rform-required">*</span></label>
                                    <input type="text" name="pic" class="rform-input @error('pic') is-invalid @enderror" value="{{ old('pic', auth()->user()?->name ?? 'Teknisi') }}" placeholder="Masukkan PIC" required>
                                    @error('pic') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-battery-charging"></i>
                            <h3>Checklist Baterai</h3>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Nomor Rectifier <span class="rform-required">*</span></label>
                                    <select id="rectifier_id" name="rectifier_id" class="rform-select @error('rectifier_id') is-invalid @enderror" required onchange="handleRectifierChange()">
                                        <option value="" disabled {{ old('rectifier_id') ? '' : 'selected' }}>-- Pilih Nomor Rectifier --</option>
                                        @forelse ($rectifiers as $r)
                                            <option value="{{ $r->id }}"
                                                data-beban="{{ $r->beban }}"
                                                {{ old('rectifier_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->recti_label }} (Beban: {{ $r->beban ? $r->beban . ' A' : '-' }})
                                            </option>
                                        @empty
                                            <option value="" disabled>Belum ada Rectifier di POP ini</option>
                                        @endforelse
                                    </select>
                                    @error('rectifier_id') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Nomor Bank <span class="rform-required">*</span></label>
                                    <input type="text" id="nomor_bank" name="nomor_bank" class="rform-input @error('nomor_bank') is-invalid @enderror" value="{{ old('nomor_bank', $suggestedBank) }}" placeholder="Contoh: {{ $pop->kode_pop }}_BANK01" required>
                                    @error('nomor_bank') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Merk Battery <span class="rform-required">*</span></label>
                                    <select id="merk_battery" name="merk_battery" class="rform-select @error('merk_battery') is-invalid @enderror" required onchange="updateTipeOptions()">
                                        <option value="" disabled {{ old('merk_battery') ? '' : 'selected' }}>-- Pilih Merk Baterai --</option>
                                        <option value="SACRED SUN" {{ old('merk_battery') == 'SACRED SUN' ? 'selected' : '' }}>SACRED SUN</option>
                                        <option value="NARADA" {{ old('merk_battery') == 'NARADA' ? 'selected' : '' }}>NARADA</option>
                                        <option value="SHOTO" {{ old('merk_battery') == 'SHOTO' ? 'selected' : '' }}>SHOTO</option>
                                        <option value="COSLIGHT" {{ old('merk_battery') == 'COSLIGHT' ? 'selected' : '' }}>COSLIGHT</option>
                                        <option value="VISION" {{ old('merk_battery') == 'VISION' ? 'selected' : '' }}>VISION</option>
                                        <option value="LEOCH" {{ old('merk_battery') == 'LEOCH' ? 'selected' : '' }}>LEOCH</option>
                                        <option value="HUAWEI" {{ old('merk_battery') == 'HUAWEI' ? 'selected' : '' }}>HUAWEI</option>
                                        <option value="ZTE" {{ old('merk_battery') == 'ZTE' ? 'selected' : '' }}>ZTE</option>
                                        <option value="ENERSYS" {{ old('merk_battery') == 'ENERSYS' ? 'selected' : '' }}>ENERSYS</option>
                                        <option value="MAXLIFE" {{ old('merk_battery') == 'MAXLIFE' ? 'selected' : '' }}>MAXLIFE</option>
                                        <option value="OTHER" {{ old('merk_battery') == 'OTHER' ? 'selected' : '' }}>+ Merk Lainnya</option>
                                    </select>
                                    <input type="text" id="custom_merk_battery" class="rform-input" style="display:none; margin-top:8px;" placeholder="Ketik Merk Baterai Baru...">
                                    @error('merk_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Tipe Battery <span class="rform-required">*</span></label>
                                    <select id="tipe_battery" name="tipe_battery" class="rform-select @error('tipe_battery') is-invalid @enderror" required onchange="handleTipeChange()">
                                        <option value="" disabled selected>-- Pilih Tipe Baterai --</option>
                                    </select>
                                    <input type="text" id="custom_tipe_battery" class="rform-input" style="display:none; margin-top:8px;" placeholder="Ketik Tipe Baterai Baru...">
                                    @error('tipe_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Jenis Battery <span class="rform-required">*</span></label>
                                    <select id="jenis_battery" name="jenis_battery" class="rform-select @error('jenis_battery') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('jenis_battery') ? '' : 'selected' }}>-- Pilih Jenis Baterai --</option>
                                        <option value="Lithium" {{ old('jenis_battery', 'Lithium') == 'Lithium' ? 'selected' : '' }}>Lithium</option>
                                        <option value="VRLA" {{ old('jenis_battery') == 'VRLA' ? 'selected' : '' }}>VRLA</option>
                                    </select>
                                    @error('jenis_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Kapasitas Battery (AH) <span class="rform-required">*</span></label>
                                    <select id="kapasitas_battery" name="kapasitas_battery" class="rform-select @error('kapasitas_battery') is-invalid @enderror" required onchange="updateBatteryCalculation()">
                                        <option value="" disabled {{ old('kapasitas_battery') ? '' : 'selected' }}>-- Pilih Kapasitas (AH) --</option>
                                        <option value="20" {{ old('kapasitas_battery') == '20' ? 'selected' : '' }}>20 AH</option>
                                        <option value="50" {{ old('kapasitas_battery') == '50' ? 'selected' : '' }}>50 AH</option>
                                        <option value="100" {{ old('kapasitas_battery', '100') == '100' ? 'selected' : '' }}>100 AH</option>
                                        <option value="200" {{ old('kapasitas_battery') == '200' ? 'selected' : '' }}>200 AH</option>
                                    </select>
                                    @error('kapasitas_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Kapasitas Uji (AH)</label>
                                    <input type="text" id="kapasitas_uji" name="kapasitas_uji" class="rform-input @error('kapasitas_uji') is-invalid @enderror" value="{{ old('kapasitas_uji') }}" placeholder="Contoh: 91,67 atau 100" oninput="updateBatteryCalculation()">
                                    <small style="color:#94a3b8; font-size:0.75rem; margin-top:3px; display:block;">Diisi manual sesuai hasil pengujian lapangan. Boleh pakai koma (91,67).</small>
                                    @error('kapasitas_uji') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Kapasitas Battery % & Performa</label>
                                    <div style="position: relative;">
                                        <input type="text" id="kapasitas_battery_persen_display" class="rform-input" readonly placeholder="Otomatis: (Kapasitas Uji ÷ Kapasitas) × 100" tabindex="-1" style="background:#f1f5f9; color:#334155; cursor:not-allowed; padding-right:110px;">
                                        <span id="performaBadge" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-weight: 700; font-size: 0.75rem; padding: 2px 8px; border-radius: 999px;"></span>
                                    </div>
                                    <input type="hidden" name="kapasitas_battery_persen" id="kapasitas_battery_persen" value="{{ old('kapasitas_battery_persen') }}">
                                    <input type="hidden" name="performa_baterai" id="performa_baterai" value="{{ old('performa_baterai') }}">
                                    <small style="color:#94a3b8; font-size:0.75rem; margin-top:3px; display:block;">Dihitung otomatis: &ge;90% Excellent, &ge;75% Good Enough, &ge;50% Warning, &lt;50% Alert.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: UJI BATERAI --}}
                    <div class="rform-section">
                        <div class="rform-section-header">
                            <i class="bi bi-speedometer2"></i>
                            <h3>Uji Baterai</h3>
                        </div>
                        <div class="rform-section-body">
                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Tanggal Uji Terakhir</label>
                                    <input type="date" id="tanggal_uji_terakhir" name="tanggal_uji_terakhir" class="rform-input @error('tanggal_uji_terakhir') is-invalid @enderror" value="{{ old('tanggal_uji_terakhir') }}" onchange="updateStatusUji()">
                                    @error('tanggal_uji_terakhir') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Tanggal Penggantian</label>
                                    <input type="date" id="tanggal_penggantian" name="tanggal_penggantian" class="rform-input @error('tanggal_penggantian') is-invalid @enderror" value="{{ old('tanggal_penggantian') }}">
                                </div>
                            </div>

                            <div class="rform-row rform-row-2">
                                <div class="rform-group">
                                    <label class="rform-label">Status Uji Baterai</label>
                                    <input type="hidden" name="status_uji" id="status_uji" value="{{ old('status_uji', 'BLM UJI BATT') }}">
                                    <div style="position:relative;">
                                        <div id="statusDisplay" class="rform-input" style="background:#f1f5f9; cursor:not-allowed; display:flex; align-items:center; gap:8px;" aria-readonly="true">
                                            <span id="statusDot" style="width:8px; height:8px; border-radius:50%; display:inline-block; background:#94a3b8;"></span>
                                            <strong id="statusText" style="font-size:0.85rem; color:#334155;">BLM UJI BATT</strong>
                                            <span style="font-size:0.75rem; color:#94a3b8; margin-left:auto;">Otomatis (&ge;365 hari = JADWAL UJI BATT)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="rform-group">
                                    <label class="rform-label">Area STI <span class="rform-required">*</span></label>
                                    <input type="text" id="area_sti" name="area_sti" class="rform-input @error('area_sti') is-invalid @enderror" value="{{ old('area_sti', $pop->kota_kabupaten ?? 'Jambi') }}" placeholder="Masukkan area STI" required>
                                    @error('area_sti') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="rform-actions">
                        <button type="button" class="rform-btn-reset" id="btnReset" onclick="document.getElementById('batteryForm').reset(); updateBatteryCalculation(); updateStatusUji();">Reset</button>
                        <button type="submit" class="rform-btn-simpan">
                            <i class="bi bi-check-lg"></i> Simpan Data Baterai
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Preset Tipe berdasarkan Merk Baterai
        const tipeMap = {
            'SACRED SUN': ['SSIFP48100B', 'FT48-100', 'FCP-1000', 'FCP-500', 'FCP-300', 'FTB12-100', 'Custom...'],
            'NARADA': ['48NPFC100', 'NES48100', '6-GFM-100', '6-GFM-150', '6-GFM-200', 'Custom...'],
            'SHOTO': ['SDA10-48100', '6-FMX-100', '6-FMX-50', '6-FMX-200', 'Custom...'],
            'COSLIGHT': ['48V100AH', 'GFM-100C', 'Custom...'],
            'VISION': ['CT12-100X', 'V-LFP48100', 'Custom...'],
            'LEOCH': ['LP12-100', 'LFeLi-48100', 'Custom...'],
            'HUAWEI': ['ESM-48100A1', 'ESM-48100B1', 'Custom...'],
            'ZTE': ['ZXDC48', 'ZXDC48 FB100', 'Custom...'],
            'ENERSYS': ['PowerSafe 12V100FC', 'SuperSafe T', 'Custom...'],
            'MAXLIFE': ['ML48-100', 'Custom...'],
            'OTHER': ['Custom...']
        };

        const oldTipe = "{{ old('tipe_battery') }}";
        const oldMerk = "{{ old('merk_battery') }}";

        function updateTipeOptions() {
            const merkSelect = document.getElementById('merk_battery');
            const tipeSelect = document.getElementById('tipe_battery');
            const customMerkInput = document.getElementById('custom_merk_battery');
            const selectedMerk = merkSelect.value;

            if (selectedMerk === 'OTHER') {
                customMerkInput.style.display = 'block';
                customMerkInput.required = true;
            } else {
                customMerkInput.style.display = 'none';
                customMerkInput.required = false;
            }

            tipeSelect.innerHTML = '<option value="" disabled selected>-- Pilih Tipe Baterai --</option>';

            const listTipe = tipeMap[selectedMerk] || ['Custom...'];
            listTipe.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t === 'Custom...' ? 'TIPE_CUSTOM' : t;
                opt.textContent = t;
                if (oldTipe && (oldTipe === t || (t === 'Custom...' && !listTipe.includes(oldTipe)))) {
                    opt.selected = true;
                }
                tipeSelect.appendChild(opt);
            });

            handleTipeChange();
        }

        function handleTipeChange() {
            const tipeSelect = document.getElementById('tipe_battery');
            const customTipeInput = document.getElementById('custom_tipe_battery');
            if (tipeSelect.value === 'TIPE_CUSTOM') {
                customTipeInput.style.display = 'block';
                customTipeInput.required = true;
                if (oldTipe && !tipeMap[document.getElementById('merk_battery').value]?.includes(oldTipe)) {
                    customTipeInput.value = oldTipe;
                }
            } else {
                customTipeInput.style.display = 'none';
                customTipeInput.required = false;
            }
        }

        function handleRectifierChange() {
            const rectiSelect = document.getElementById('rectifier_id');
            const selectedOption = rectiSelect.options[rectiSelect.selectedIndex];
            const beban = parseFloat(selectedOption?.dataset?.beban);
            updateBatteryCalculation();
        }

        function updateBatteryCalculation() {
            const kapBatteryEl = document.getElementById('kapasitas_battery');
            const kapUjiEl = document.getElementById('kapasitas_uji');
            const persenDisplay = document.getElementById('kapasitas_battery_persen_display');
            const persenHidden = document.getElementById('kapasitas_battery_persen');
            const performaHidden = document.getElementById('performa_baterai');
            const performaBadge = document.getElementById('performaBadge');

            const kapBattery = parseFloat(kapBatteryEl.value);
            // Handle koma sebagai pemisah desimal
            const kapUjiRaw = kapUjiEl.value.replace(',', '.');
            const kapUji = parseFloat(kapUjiRaw);

            if (!isNaN(kapBattery) && !isNaN(kapUji) && kapBattery > 0 && kapUji > 0) {
                const hasilPersen = ((kapUji / kapBattery) * 100).toFixed(2);
                persenDisplay.value = hasilPersen + '%';
                persenHidden.value = hasilPersen;

                let label = '';
                let bg = '', color = '', border = '';

                if (hasilPersen >= 90) {
                    label = '1-EXCELLENT';
                    bg = '#dcfce7'; color = '#16a34a'; border = '#bbf7d0';
                } else if (hasilPersen >= 75) {
                    label = '2-GOOD ENOUGH';
                    bg = '#fef9c3'; color = '#854d0e'; border = '#fde68a';
                } else if (hasilPersen >= 50) {
                    label = '3-WARNING';
                    bg = '#ffedd5'; color = '#c2410c'; border = '#fed7aa';
                } else {
                    label = '4-ALERT';
                    bg = '#fee2e2'; color = '#dc2626'; border = '#fecaca';
                }

                performaHidden.value = label;
                performaBadge.textContent = label;
                performaBadge.style.background = bg;
                performaBadge.style.color = color;
                performaBadge.style.border = `1px solid ${border}`;
                performaBadge.style.display = 'inline-block';

                // Hitung estimasi backup time (untuk preview saja, tidak disimpan)
                const rectiSelect = document.getElementById('rectifier_id');
                const selectedOption = rectiSelect.options[rectiSelect.selectedIndex];
                const beban = parseFloat(selectedOption?.dataset?.beban);
                if (!isNaN(beban) && beban > 0) {
                    // preview saja, tidak ada field untuk ditampilkan
                }
            } else if (isNaN(kapUji) || kapUji <= 0) {
                persenDisplay.value = 'BLM UJI BATT';
                persenHidden.value = '';
                performaHidden.value = 'BLM UJI BATT';
                performaBadge.textContent = 'BLM UJI BATT';
                performaBadge.style.background = '#f1f5f9';
                performaBadge.style.color = '#64748b';
                performaBadge.style.border = '1px solid #cbd5e1';
                performaBadge.style.display = 'inline-block';
            } else {
                persenDisplay.value = '';
                persenHidden.value = '';
                performaHidden.value = '';
                performaBadge.textContent = '';
                performaBadge.style.display = 'none';
            }
        }

        function updateStatusUji() {
            const tglUjiEl = document.getElementById('tanggal_uji_terakhir');
            const statusUjiHidden = document.getElementById('status_uji');
            const statusDisplay = document.getElementById('statusDisplay');
            const statusText = document.getElementById('statusText');
            const statusDot = document.getElementById('statusDot');

            statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
            statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

            if (!tglUjiEl.value) {
                statusText.textContent = 'BLM UJI BATT';
                statusUjiHidden.value = 'BLM UJI BATT';
                statusDisplay.style.background = '#f8fafc';
                statusDisplay.style.borderColor = '#cbd5e1';
                return;
            }

            const tglUji = new Date(tglUjiEl.value);
            const now = new Date();
            const diffTime = Math.abs(now - tglUji);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays >= 365) {
                statusText.textContent = 'JADWAL UJI BATT';
                statusUjiHidden.value = 'JADWAL UJI BATT';
                statusDisplay.classList.add('status-warning');
                statusDot.classList.add('status-dot-warning');
            } else {
                statusText.textContent = 'SUDAH UJI BATT';
                statusUjiHidden.value = 'SUDAH UJI BATT';
                statusDisplay.classList.add('status-excellent');
                statusDot.classList.add('status-dot-excellent');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (oldMerk) {
                document.getElementById('merk_battery').value = oldMerk;
                updateTipeOptions();
            }
            updateBatteryCalculation();
            updateStatusUji();

            const form = document.getElementById('batteryForm');
            form.addEventListener('submit', function (e) {
                // Normalkan koma → titik pada kapasitas_uji sebelum submit
                const kapUjiEl = document.getElementById('kapasitas_uji');
                if (kapUjiEl.value) {
                    kapUjiEl.value = kapUjiEl.value.replace(',', '.');
                }

                const merkSelect = document.getElementById('merk_battery');
                const customMerk = document.getElementById('custom_merk_battery');
                if (merkSelect.value === 'OTHER' && customMerk.value) {
                    const opt = document.createElement('option');
                    opt.value = customMerk.value;
                    opt.selected = true;
                    merkSelect.appendChild(opt);
                }

                const tipeSelect = document.getElementById('tipe_battery');
                const customTipe = document.getElementById('custom_tipe_battery');
                if (tipeSelect.value === 'TIPE_CUSTOM' && customTipe.value) {
                    const opt = document.createElement('option');
                    opt.value = customTipe.value;
                    opt.selected = true;
                    tipeSelect.appendChild(opt);
                }
            });
        });
    </script>
</body>
</html>