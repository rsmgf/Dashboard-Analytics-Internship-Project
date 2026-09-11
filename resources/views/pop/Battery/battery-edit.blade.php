<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Baterai {{ $battery->nomor_bank }} - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/css/sidebar.css',
        'resources/css/battery-create.css',
        'resources/css/battery-detail.css'
    ])
</head>
<body>
    <div class="app-container">
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="rectifier-content">
                <div class="detail-page-header" style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <a href="{{ route('batteries.show', [$pop->id, $battery->id]) }}" class="back-button" title="Kembali ke Detail Baterai">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop, 'route' => 'batteries.index', 'params' => ['pop' => $pop->id]],
                            ['label' => 'Edit Baterai (' . $battery->nomor_bank . ')'],
                        ]" />
                        <p style="font-size: 0.8rem; color: #64748b; margin: 2px 0 0;">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi ?? 'Jambi' }}</p>
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

                <form action="{{ route('batteries.update', [$pop->id, $battery->id]) }}" method="POST" enctype="multipart/form-data" id="batteryEditForm">
                    @csrf
                    @method('PUT')

                    {{-- SECTION 1: GENERAL INFORMATION --}}
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>POP</label>
                                <input type="text" class="form-control disabled-input" value="{{ $pop->kode_pop }} - {{ $pop->nama_pop }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Building <span class="required">*</span></label>
                                <input type="text" class="form-control @error('building') is-invalid @enderror" name="building" value="{{ old('building', $battery->building) }}" placeholder="Masukkan building" required>
                                @error('building') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>PIC <span class="required">*</span></label>
                                <input type="text" class="form-control @error('pic') is-invalid @enderror" name="pic" value="{{ old('pic', $battery->pic) }}" placeholder="Masukkan PIC" required>
                                @error('pic') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Type POP <span class="required">*</span></label>
                                <input type="text" class="form-control @error('type_pop') is-invalid @enderror" name="type_pop" value="{{ old('type_pop', $battery->type_pop) }}" placeholder="Masukkan type POP" required>
                                @error('type_pop') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Recti</label>
                                <input type="text" class="form-control @error('recti') is-invalid @enderror" name="recti" value="{{ old('recti', $battery->recti) }}" placeholder="Contoh: RECTI 01">
                                @error('recti') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: CHECKLIST BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <p class="form-section-subtitle">Informasi Baterai</p>
                        
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="rectifier_id" name="rectifier_id" class="table-input @error('rectifier_id') is-invalid @enderror" required>
                                        <option value="" disabled>-- Pilih Nomor Rectifier --</option>
                                        @forelse ($rectifiers as $r)
                                            <option value="{{ $r->id }}" {{ old('rectifier_id', $battery->rectifier_id) == $r->id ? 'selected' : '' }}>
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
                                    <input type="text" class="table-input @error('nomor_bank') is-invalid @enderror" name="nomor_bank" value="{{ old('nomor_bank', $battery->nomor_bank) }}" placeholder="Masukkan nomor bank" required>
                                    @error('nomor_bank') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="merk_battery" name="merk_battery" class="table-input @error('merk_battery') is-invalid @enderror" required>
                                        <option value="" disabled>Pilih Merk</option>
                                        @php
                                            $currentMerk = old('merk_battery', $battery->merk_battery);
                                            $merkList = ['Sacred Sun', 'BSB', 'Fortis Power', 'Monolite', 'Nagoya', 'Narada', 'Nippres', 'Sinergi', 'Vision', 'Shoto', 'Coslight', 'Leoch', 'Huawei', 'ZTE'];
                                        @endphp
                                        @foreach ($merkList as $m)
                                            <option value="{{ $m }}" {{ strcasecmp($currentMerk, $m) === 0 ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                    @error('merk_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    @php $currentJenis = old('jenis_battery', $battery->jenis_battery ?? 'Lithium'); @endphp
                                    <select id="jenis_battery" name="jenis_battery" class="table-input @error('jenis_battery') is-invalid @enderror" required>
                                        <option value="" disabled>Pilih Jenis</option>
                                        <option value="Lithium" {{ strcasecmp($currentJenis, 'Lithium') === 0 ? 'selected' : '' }}>Lithium</option>
                                        <option value="VRLA" {{ strcasecmp($currentJenis, 'VRLA') === 0 ? 'selected' : '' }}>VRLA</option>
                                    </select>
                                    @error('jenis_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" class="table-input @error('tipe_battery') is-invalid @enderror" name="tipe_battery" id="tipe_battery" value="{{ old('tipe_battery', $battery->tipe_battery) }}" placeholder="Masukkan tipe battery" required>
                                    @error('tipe_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" step="0.01" min="0" id="tegangan" name="tegangan" class="table-input" value="{{ old('tegangan', $battery->tegangan ?? 48) }}" placeholder="Masukkan tegangan" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" step="0.01" min="0" id="kapasitas_battery" name="kapasitas_battery" class="table-input @error('kapasitas_battery') is-invalid @enderror" value="{{ old('kapasitas_battery', $battery->kapasitas_battery) }}" placeholder="Masukkan kapasitas battery (Contoh: 100)" required>
                                    @error('kapasitas_battery') <span class="text-danger" style="font-size: 0.75rem; color:#ef4444;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <div id="defaultUjiText" class="default-uji-text" style="display: none;">Pilih jenis battery terlebih dahulu</div>
                                    
                                    <!-- Wrapper Lithium (1 Input) -->
                                    <div id="lithiumUjiWrapper" class="uji-wrapper" style="width: 100%;">
                                        <input type="text" id="kapasitas_uji_1" name="kapasitas_uji" class="table-input" value="{{ old('kapasitas_uji', $battery->kapasitas_uji !== null ? number_format($battery->kapasitas_uji, 2, ',', '') : '') }}" placeholder="Masukkan kapasitas uji (contoh: 91,67)">
                                    </div>

                                    <!-- Wrapper VRLA (4 Input 2x2 Grid) -->
                                    <div id="vrlaUjiWrapper" class="uji-grid-4" style="display: none; width: 100%;">
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 1</span>
                                            <input type="text" class="table-input vrla-input" id="vrla_1" placeholder="Kapasitas 1">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 3</span>
                                            <input type="text" class="table-input vrla-input" id="vrla_3" placeholder="Kapasitas 3">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 2</span>
                                            <input type="text" class="table-input vrla-input" id="vrla_2" placeholder="Kapasitas 2">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 4</span>
                                            <input type="text" class="table-input vrla-input" id="vrla_4" placeholder="Kapasitas 4">
                                        </div>
                                    </div>
                                    <small style="color:#94a3b8; font-size:0.75rem; margin-top:4px; display:block;">Bisa input angka dengan koma (contoh: 91,67).</small>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery %</div>
                                <div class="checklist-field">
                                    <input type="text" id="kapasitas_persen" name="kapasitas_battery_persen" class="table-input auto-field" value="{{ old('kapasitas_battery_persen', $battery->kapasitas_battery_persen !== null ? number_format($battery->kapasitas_battery_persen, 2) . '%' : '') }}" readonly tabindex="-1">
                                    <input type="hidden" name="performa_baterai" id="performa_baterai" value="{{ old('performa_baterai', $battery->performa_baterai) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: UJI BATERAI --}}
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label>Tanggal Uji Terakhir</label>
                                <input type="date" class="form-control" id="tanggal_uji_terakhir" name="tanggal_uji_terakhir" value="{{ old('tanggal_uji_terakhir', $battery->tanggal_uji_terakhir ? $battery->tanggal_uji_terakhir->format('Y-m-d') : '') }}">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Penggantian <span class="required">*</span></label>
                                <input type="date" class="form-control" id="tanggal_penggantian" name="tanggal_penggantian" value="{{ old('tanggal_penggantian', $battery->tanggal_penggantian ? $battery->tanggal_penggantian->format('Y-m-d') : '') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Status Uji Baterai</label>
                                <div class="status-display-wrapper">
                                    <div id="statusUjiDisplay" class="form-control status-readonly-box status-warning" aria-readonly="true">
                                        <span class="status-dot status-dot-warning" id="statusDot"></span>
                                        <strong id="statusUjiText">{{ $battery->status_uji ?? 'BLM UJI BATT' }}</strong>
                                        <span class="status-source">Otomatis dari sistem</span>
                                    </div>
                                </div>
                                <input type="hidden" id="status_uji" name="status_uji" value="{{ old('status_uji', $battery->status_uji ?? 'BLM UJI BATT') }}">
                            </div>

                            <div class="form-group" style="margin-top: 15px;">
                                <label>Area STI <span class="required">*</span></label>
                                <input type="text" class="form-control @error('area_sti') is-invalid @enderror" name="area_sti" value="{{ old('area_sti', $battery->area_sti ?? $pop->kota_kabupaten) }}" placeholder="Masukkan Area STI" required>
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
                                        @if (!empty($battery->photo_battery) && file_exists(public_path('storage/' . $battery->photo_battery)))
                                            <img id="previewImage" src="{{ asset('storage/' . $battery->photo_battery) }}" alt="Preview" style="display: block;">
                                            <div id="noPreviewText" class="no-preview" style="display: none;">
                                                <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                <span>Belum ada foto yang dipilih</span>
                                            </div>
                                        @else
                                            <img id="previewImage" src="" alt="Preview" style="display: none;">
                                            <div id="noPreviewText" class="no-preview">
                                                <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                <span>Belum ada foto yang dipilih</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="keterangan_gambar">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar" name="keterangan_gambar" class="form-control" value="{{ old('keterangan_gambar', $battery->keterangan_gambar ?? '') }}" placeholder="Masukkan keterangan gambar">
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <a href="{{ route('batteries.show', [$pop->id, $battery->id]) }}" class="btn-reset" style="height: 42px; padding: 0 24px; border: none; border-radius: 8px; background: #64748b; color: #ffffff; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                        <button type="submit" class="btn-submit" style="height: 42px; padding: 0 28px; border-radius: 8px; background: #0070d8; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: none;">
                            <i class="bi bi-floppy"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('batteryEditForm');
            const jenisBattery = document.getElementById('jenis_battery');
            const kapasitasBattery = document.getElementById('kapasitas_battery');
            const kapasitasPersen = document.getElementById('kapasitas_persen');
            const performaHidden = document.getElementById('performa_baterai');
            const lithiumWrapper = document.getElementById('lithiumUjiWrapper');
            const vrlaWrapper = document.getElementById('vrlaUjiWrapper');
            const defaultUjiText = document.getElementById('defaultUjiText');
            
            const tglUjiEl = document.getElementById('tanggal_uji_terakhir');
            const statusUji = document.getElementById('status_uji');
            const statusDisplay = document.getElementById('statusUjiDisplay');
            const statusText = document.getElementById('statusUjiText');
            const statusDot = document.getElementById('statusDot');

            function toggleJenisBattery(val) {
                if (val === 'Lithium') {
                    if (defaultUjiText) defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'block';
                    vrlaWrapper.style.display = 'none';
                } else if (val === 'VRLA') {
                    if (defaultUjiText) defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'grid';
                } else {
                    if (defaultUjiText) defaultUjiText.style.display = 'block';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'none';
                }
            }

            jenisBattery.addEventListener('change', function () {
                toggleJenisBattery(this.value);
                updateBatteryCalculation();
            });

            // Set initial state
            if (jenisBattery.value) {
                toggleJenisBattery(jenisBattery.value);
            }

            function updateStatusBadge(persentase) {
                statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

                if (persentase === null) {
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
                        nilaiUji = sum / count;
                        document.getElementById('kapasitas_uji_1').value = nilaiUji.toFixed(2);
                    }
                }

                if (isNaN(nilaiBattery) || nilaiBattery <= 0 || isNaN(nilaiUji) || nilaiUji === null) {
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

            // Initial calculation
            updateBatteryCalculation();
            updateStatusFromDate();

            // Photo preview handler
            const photoInput = document.getElementById('photo_battery');
            const previewImage = document.getElementById('previewImage');
            const noPreviewText = document.getElementById('noPreviewText');

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            previewImage.style.display = 'block';
                            if (noPreviewText) noPreviewText.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Normalisasi koma -> titik saat submit
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
        });
    </script>
</body>
</html>