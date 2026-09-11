<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Baterai - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/css/sidebar.css',
        'resources/css/battery-create.css'
    ])
</head>
<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rectifier-content">
                <div class="rectifier-page-header" style="border-bottom: none; margin-bottom: 20px;">
                    <div class="rectifier-page-info" style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('batteries.index', $pop->id) }}" class="back-button" title="Kembali ke Daftar Baterai">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <x-breadcrumb :items="[
                                ['label' => 'POP', 'route' => 'pops.index'],
                                ['label' => $pop->nama_pop, 'route' => 'batteries.index', 'params' => ['pop' => $pop->id]],
                                ['label' => 'Tambah Baterai'],
                            ]" />
                            <p style="font-size: 0.8rem; color: #64748b; margin: 2px 0 0;">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Alert Error Validasi --}}
                @if (isset($errors) && $errors->any())
                    <div style="margin-bottom: 20px; background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; padding: 12px 16px; border-radius: 8px;">
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> Terjadi Kesalahan:</strong>
                        <ul style="margin: 4px 0 0 16px; padding: 0; font-size: 0.85rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('batteries.store', $pop->id) }}" method="POST" id="batteryForm" enctype="multipart/form-data">
                    @csrf

                    {{-- SECTION 1: GENERAL INFORMATION --}}
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" class="form-control disabled-input" value="{{ $pop->kode_pop }} - {{ $pop->nama_pop }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="building">Building <span class="required">*</span></label>
                                <input type="text" id="building" name="building" class="form-control @error('building') is-invalid @enderror" value="{{ old('building', $pop->jenis_bangunan) }}" placeholder="Masukkan building" required>
                                @error('building') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="pic">PIC <span class="required">*</span></label>
                                <input type="text" id="pic" name="pic" class="form-control @error('pic') is-invalid @enderror" value="{{ old('pic', auth()->user()?->name ?? 'Teknisi') }}" placeholder="Masukkan PIC" required>
                                @error('pic') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="type_pop">Type POP <span class="required">*</span></label>
                                <input type="text" id="type_pop" name="type_pop" class="form-control @error('type_pop') is-invalid @enderror" value="{{ old('type_pop', $pop->tipe_pop) }}" placeholder="Masukkan type POP" required>
                                @error('type_pop') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="recti">Recti</label>
                                <input type="text" id="recti" name="recti" class="form-control @error('recti') is-invalid @enderror" value="{{ old('recti') }}" placeholder="Contoh: RECTI 01">
                                @error('recti') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="rectifier_id" name="rectifier_id" class="table-input @error('rectifier_id') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('rectifier_id') ? '' : 'selected' }}>-- Pilih Nomor Rectifier --</option>
                                        @forelse ($rectifiers as $r)
                                            <option value="{{ $r->id }}" {{ old('rectifier_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->nomor_recti }} ({{ $r->recti_label }}) - Beban: {{ $r->beban ? $r->beban . ' A' : '-' }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Belum ada Rectifier di POP ini</option>
                                        @endforelse
                                    </select>
                                    @error('rectifier_id') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="nomor_bank" name="nomor_bank" class="table-input @error('nomor_bank') is-invalid @enderror" value="{{ old('nomor_bank', $suggestedBank ?? '') }}" placeholder="Contoh: {{ $pop->kode_pop }}_BANK01" required>
                                    @error('nomor_bank') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="merk_battery" name="merk_battery" class="table-input @error('merk_battery') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('merk_battery') ? '' : 'selected' }}>Pilih Merk</option>
                                        <option value="Sacred Sun" {{ old('merk_battery') == 'Sacred Sun' ? 'selected' : '' }}>Sacred Sun</option>
                                        <option value="BSB" {{ old('merk_battery') == 'BSB' ? 'selected' : '' }}>BSB</option>
                                        <option value="Fortis Power" {{ old('merk_battery') == 'Fortis Power' ? 'selected' : '' }}>Fortis Power</option>
                                        <option value="Monolite" {{ old('merk_battery') == 'Monolite' ? 'selected' : '' }}>Monolite</option>
                                        <option value="Nagoya" {{ old('merk_battery') == 'Nagoya' ? 'selected' : '' }}>Nagoya</option>
                                        <option value="Narada" {{ old('merk_battery') == 'Narada' ? 'selected' : '' }}>Narada</option>
                                        <option value="Nippres" {{ old('merk_battery') == 'Nippres' ? 'selected' : '' }}>Nippres</option>
                                        <option value="Sinergi" {{ old('merk_battery') == 'Sinergi' ? 'selected' : '' }}>Sinergi</option>
                                        <option value="Vision" {{ old('merk_battery') == 'Vision' ? 'selected' : '' }}>Vision</option>
                                        <option value="Shoto" {{ old('merk_battery') == 'Shoto' ? 'selected' : '' }}>Shoto</option>
                                        <option value="Coslight" {{ old('merk_battery') == 'Coslight' ? 'selected' : '' }}>Coslight</option>
                                        <option value="Leoch" {{ old('merk_battery') == 'Leoch' ? 'selected' : '' }}>Leoch</option>
                                        <option value="Huawei" {{ old('merk_battery') == 'Huawei' ? 'selected' : '' }}>Huawei</option>
                                        <option value="ZTE" {{ old('merk_battery') == 'ZTE' ? 'selected' : '' }}>ZTE</option>
                                    </select>
                                    @error('merk_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="jenis_battery" name="jenis_battery" class="table-input @error('jenis_battery') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('jenis_battery') ? '' : 'selected' }}>Pilih Jenis</option>
                                        <option value="Lithium" {{ old('jenis_battery', 'Lithium') == 'Lithium' ? 'selected' : '' }}>Lithium</option>
                                        <option value="VRLA" {{ old('jenis_battery') == 'VRLA' ? 'selected' : '' }}>VRLA</option>
                                    </select>
                                    @error('jenis_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="tipe_battery" name="tipe_battery" class="table-input @error('tipe_battery') is-invalid @enderror" value="{{ old('tipe_battery') }}" placeholder="Masukkan tipe battery" required>
                                    @error('tipe_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="tegangan" name="tegangan" class="table-input" min="0" step="0.01" value="{{ old('tegangan', 48) }}" placeholder="Masukkan tegangan" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="kapasitas_battery" name="kapasitas_battery" class="table-input @error('kapasitas_battery') is-invalid @enderror" min="0" step="0.01" value="{{ old('kapasitas_battery', 100) }}" placeholder="Masukkan kapasitas battery (Contoh: 100)" required>
                                    @error('kapasitas_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <div id="defaultUjiText" class="default-uji-text">Pilih jenis battery terlebih dahulu</div>
                                    
                                    <!-- Lithium (1 Input) -->
                                    <div id="lithiumUjiWrapper" class="uji-wrapper" style="display: none;">
                                        <input type="text" id="kapasitas_uji_1" name="kapasitas_uji" class="table-input" value="{{ old('kapasitas_uji') }}" placeholder="Masukkan kapasitas uji (bisa koma, contoh: 91,67)">
                                    </div>

                                    <!-- VRLA (4 Input 2x2 Grid) -->
                                    <div id="vrlaUjiWrapper" class="uji-grid-4" style="display: none;">
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 1</span>
                                            <input type="text" id="vrla_1" class="table-input vrla-input" placeholder="Kapasitas 1">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 3</span>
                                            <input type="text" id="vrla_3" class="table-input vrla-input" placeholder="Kapasitas 3">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 2</span>
                                            <input type="text" id="vrla_2" class="table-input vrla-input" placeholder="Kapasitas 2">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 4</span>
                                            <input type="text" id="vrla_4" class="table-input vrla-input" placeholder="Kapasitas 4">
                                        </div>
                                    </div>
                                    <small style="color:#94a3b8; font-size:0.75rem; margin-top:4px; display:block;">Bisa input angka dengan koma (contoh: 91,67).</small>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery % <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="kapasitas_battery_persen" name="kapasitas_battery_persen" class="table-input auto-field" readonly tabindex="-1" placeholder="Dihitung otomatis oleh sistem" value="{{ old('kapasitas_battery_persen') }}">
                                    <input type="hidden" name="performa_baterai" id="performa_baterai" value="{{ old('performa_baterai') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: UJI BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label for="tanggal_uji_terakhir">Tanggal Uji Terakhir</label>
                                <input type="date" id="tanggal_uji_terakhir" name="tanggal_uji_terakhir" class="form-control" value="{{ old('tanggal_uji_terakhir') }}">
                            </div>

                            <div class="form-group">
                                <label for="tanggal_penggantian">Tanggal Penggantian <span class="required">*</span></label>
                                <input type="date" id="tanggal_penggantian" name="tanggal_penggantian" class="form-control" value="{{ old('tanggal_penggantian') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Status Uji Baterai</label>
                                <input type="hidden" name="status_uji" id="status_uji" value="{{ old('status_uji', 'BLM UJI BATT') }}">

                                <div class="status-display-wrapper">
                                    <div id="statusDisplay" class="form-control status-readonly-box" aria-readonly="true">
                                        <span id="statusDot" class="status-dot"></span>
                                        <strong id="statusText">BLM UJI BATT</strong>
                                        <span class="status-source">Otomatis dari sistem</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 15px;">
                                <label for="area_sti">Area STI <span class="required">*</span></label>
                                <input type="text" id="area_sti" name="area_sti" class="form-control @error('area_sti') is-invalid @enderror" value="{{ old('area_sti', $pop->kota_kabupaten) }}" placeholder="Masukkan Area STI" required>
                                @error('area_sti') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: PHOTO BATTERY --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Photo Battery</h3>
                        <div class="form-group">
                            <label>Upload foto kondisi Battery di lokasi</label>
                            <div class="upload-container">
                                <div class="upload-dropzone" id="dropzone">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <span class="upload-text">Masukkan file disini</span>
                                    <label for="photo_battery" class="btn-browse">Browse</label>
                                    <input type="file" id="photo_battery" name="photo_battery" accept="image/jpeg,image/png,image/jpg" hidden>
                                </div>
                                <div class="preview-container">
                                    <span class="preview-title">Preview foto</span>
                                    <div class="preview-box">
                                        <img id="previewImage" src="" alt="Preview" style="display: none;">
                                        <div id="noPreviewText" class="no-preview">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="keterangan_gambar">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar" name="keterangan_gambar" class="form-control" placeholder="Masukkan keterangan gambar" value="{{ old('keterangan_gambar') }}">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn-reset" id="btnReset">Reset</button>
                        <button type="submit" class="btn-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('batteryForm');
            const jenisBattery = document.getElementById('jenis_battery');
            const kapasitasBattery = document.getElementById('kapasitas_battery');
            const kapasitasPersen = document.getElementById('kapasitas_battery_persen');
            const performaHidden = document.getElementById('performa_baterai');
            const lithiumWrapper = document.getElementById('lithiumUjiWrapper');
            const vrlaWrapper = document.getElementById('vrlaUjiWrapper');
            const defaultUjiText = document.getElementById('defaultUjiText');
            
            const tglUjiEl = document.getElementById('tanggal_uji_terakhir');
            const statusUji = document.getElementById('status_uji');
            const statusDisplay = document.getElementById('statusDisplay');
            const statusText = document.getElementById('statusText');
            const statusDot = document.getElementById('statusDot');

            function toggleJenisBattery(val) {
                if (val === 'Lithium') {
                    defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'block';
                    vrlaWrapper.style.display = 'none';
                } else if (val === 'VRLA') {
                    defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'grid';
                } else {
                    defaultUjiText.style.display = 'block';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'none';
                }
            }

            jenisBattery.addEventListener('change', function () {
                toggleJenisBattery(this.value);
                updateBatteryCalculation();
            });

            // Initial toggle state
            if (jenisBattery.value) {
                toggleJenisBattery(jenisBattery.value);
            }

            function updateStatusBadge(persentase) {
                statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

                if (persentase === null) {
                    // Update status berdasarkan tanggal jika ada
                    updateStatusFromDate();
                    return;
                }

                if (persentase >= 90) {
                    performaHidden.value = '1-EXCELLENT';
                } else if (persentase >= 75) {
                    performaHidden.value = '2-GOOD ENOUGH';
                } else if (persentase >= 50) {
                    performaHidden.value = '3-WARNING';
                } else {
                    performaHidden.value = '4-ALERT';
                }
            }

            function updateStatusFromDate() {
                statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

                if (!tglUjiEl.value) {
                    statusText.textContent = 'BLM UJI BATT';
                    statusUji.value = 'BLM UJI BATT';
                    statusDisplay.classList.add('status-warning');
                    statusDot.classList.add('status-dot-warning');
                    return;
                }

                const tglUji = new Date(tglUjiEl.value);
                const now = new Date();
                const diffDays = Math.ceil(Math.abs(now - tglUji) / (1000 * 60 * 60 * 24));

                if (diffDays >= 365) {
                    statusText.textContent = 'JADWAL UJI BATT';
                    statusUji.value = 'JADWAL UJI BATT';
                    statusDisplay.classList.add('status-danger');
                    statusDot.classList.add('status-dot-danger');
                } else {
                    statusText.textContent = 'SUDAH UJI BATT';
                    statusUji.value = 'SUDAH UJI BATT';
                    statusDisplay.classList.add('status-good');
                    statusDot.classList.add('status-dot-good');
                }
            }

            function updateBatteryCalculation() {
                const nilaiBattery = parseFloat(kapasitasBattery.value);
                let nilaiUji = null;

                if (jenisBattery.value === 'Lithium') {
                    const inputLithium = document.getElementById('kapasitas_uji_1');
                    const valRaw = inputLithium.value.replace(',', '.');
                    nilaiUji = parseFloat(valRaw);
                } else if (jenisBattery.value === 'VRLA') {
                    const vrlaInputs = document.querySelectorAll('.vrla-input');
                    let sum = 0, count = 0;
                    vrlaInputs.forEach(inp => {
                        const val = parseFloat(inp.value.replace(',', '.'));
                        if (!isNaN(val) && val > 0) {
                            sum += val;
                            count++;
                        }
                    });
                    if (count > 0) {
                        nilaiUji = sum / count; // Rata-rata 4 monobloc
                        // Set nilai ke input hidden / single kapasitas_uji
                        document.getElementById('kapasitas_uji_1').value = nilaiUji.toFixed(2);
                    }
                }

                if (isNaN(nilaiBattery) || nilaiBattery <= 0 || isNaN(nilaiUji) || nilaiUji === null) {
                    kapasitasPersen.value = '';
                    performaHidden.value = 'BLM UJI BATT';
                    updateStatusBadge(null);
                    return;
                }

                const persentase = (nilaiUji / nilaiBattery) * 100;
                kapasitasPersen.value = persentase.toFixed(2) + '%';
                updateStatusBadge(persentase);
            }

            kapasitasBattery.addEventListener('input', updateBatteryCalculation);
            document.getElementById('kapasitas_uji_1').addEventListener('input', updateBatteryCalculation);
            document.querySelectorAll('.vrla-input').forEach(el => {
                el.addEventListener('input', updateBatteryCalculation);
            });
            tglUjiEl.addEventListener('change', updateStatusFromDate);

            // Inisialisasi awal
            updateBatteryCalculation();
            updateStatusFromDate();

            // Photo preview handler
            const photoInput = document.getElementById('photo_battery');
            const previewImage = document.getElementById('previewImage');
            const noPreviewText = document.getElementById('noPreviewText');

            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImage.src = event.target.result;
                        previewImage.style.display = 'block';
                        noPreviewText.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Form Submit: Normalisasi koma -> titik
            form.addEventListener('submit', function () {
                const kapUji = document.getElementById('kapasitas_uji_1');
                if (kapUji && kapUji.value) {
                    kapUji.value = kapUji.value.replace(',', '.');
                }
                const kapBattery = document.getElementById('kapasitas_battery');
                if (kapBattery && kapBattery.value) {
                    kapBattery.value = kapBattery.value.replace(',', '.');
                }
            });

            // Reset handler
            form.addEventListener('reset', function () {
                setTimeout(function () {
                    kapasitasPersen.value = '';
                    statusUji.value = 'BLM UJI BATT';
                    statusText.textContent = 'BLM UJI BATT';
                    defaultUjiText.style.display = 'block';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'none';
                    previewImage.style.display = 'none';
                    noPreviewText.style.display = 'flex';
                    statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                    statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');
                    statusDisplay.classList.add('status-warning');
                    statusDot.classList.add('status-dot-warning');
                }, 10);
            });
        });
    </script>
</body>
</html>