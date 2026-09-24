<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-create.css'
    ])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a href="{{ route('gensets.show', [$pop->id, $genset->id]) }}" class="back-button" title="Kembali ke Detail Genset">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop_display . ': Genset', 'route' => 'gensets.index', 'params' => ['pop' => $pop->id]],
                            ['label' => $genset->nomor_genset, 'route' => 'gensets.show', 'params' => [$pop->id, $genset->id]],
                            ['label' => 'Edit Genset'],
                        ]" />
                    </div>
                </div>

                @if ($errors->any())
                    <div style="margin-bottom: 16px; padding: 12px 16px; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
                        <ul style="margin: 0; padding-left: 16px; color: #b91c1c; font-size: 0.875rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('gensets.update', [$pop->id, $genset->id]) }}" method="POST" id="gensetForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" class="form-control disabled-input" value="{{ $pop->nama_pop_display }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="kota">Kota / Kabupaten</label>
                                <input type="text" id="kota" class="form-control disabled-input" value="{{ $pop->kota_kabupaten }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="tipe_pop">Tipe POP</label>
                                <input type="text" id="tipe_pop" class="form-control disabled-input" value="{{ $pop->tipe_pop ?? '-' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="pic">PIC <span class="required">*</span></label>
                                <input type="text" id="pic" name="pic" class="form-control @error('pic') is-invalid @enderror"
                                    placeholder="Masukkan PIC" value="{{ old('pic', $genset->pic) }}" required>
                                @error('pic')<div style="color:#ef4444;font-size:0.8rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="bentuk_fisik">Bentuk Fisik <span class="required">*</span></label>
                                <input type="text" id="bentuk_fisik" name="bentuk_fisik" class="form-control @error('bentuk_fisik') is-invalid @enderror"
                                    placeholder="Masukkan bentuk fisik" value="{{ old('bentuk_fisik', $genset->bentuk_fisik) }}" required>
                                @error('bentuk_fisik')<div style="color:#ef4444;font-size:0.8rem;">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Genset</h3>
                        <div class="checklist-table-container">
                            <!-- Merk Genset -->
                            <div class="checklist-row">
                                <div class="checklist-label">Merk Genset <span class="required">*</span></div>
                                <div class="checklist-field">
                                    @php
                                        $knownMerks = ['Potise', 'Ada', 'Perkins', 'Truepower', 'Stamford', 'Yanmar', 'Himoinsa', 'Cummins'];
                                        $currentMerk = old('merk_genset', $genset->merk_genset);
                                        $isOtherMerk = !in_array($currentMerk, $knownMerks);
                                        $selectMerkVal = $isOtherMerk ? 'Others' : $currentMerk;
                                    @endphp
                                    <select id="merk_genset" name="merk_genset" class="table-input" required>
                                        <option value="" disabled>Pilih Merk</option>
                                        @foreach ($knownMerks as $merk)
                                            <option value="{{ $merk }}" {{ $selectMerkVal === $merk ? 'selected' : '' }}>{{ $merk }}</option>
                                        @endforeach
                                        <option value="Others" {{ $selectMerkVal === 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <input type="text" id="merk_genset_others" name="merk_genset_others" class="table-input mt-2"
                                        placeholder="Masukkan merk lainnya"
                                        value="{{ $isOtherMerk ? $currentMerk : old('merk_genset_others', '') }}"
                                        style="display: {{ $isOtherMerk ? 'block' : 'none' }};">
                                    @error('merk_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="checklist-row">
                                <div class="checklist-label">Model <span class="required">*</span></div>
                                <div class="checklist-field">
                                    @php
                                        $currentModel = old('model', $genset->model);
                                    @endphp
                                    <select id="model" name="model" class="table-input" required>
                                        <option value="" disabled>Pilih Model</option>
                                        {{-- Opsi akan di-populate oleh JS, tapi kita perlu set nilai lama --}}
                                    </select>
                                    <input type="text" id="model_others" name="model_others" class="table-input mt-2"
                                        placeholder="Masukkan model lainnya"
                                        value="{{ old('model_others', '') }}"
                                        style="display: none;">
                                    @error('model')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- SN Genset -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Genset <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_genset" name="sn_genset" class="table-input"
                                        placeholder="Masukkan SN genset" value="{{ old('sn_genset', $genset->sn_genset) }}" required>
                                    @error('sn_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Kapasitas (KVA) -->
                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas (KVA) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="kapasitas_kva" name="kapasitas_kva" class="table-input"
                                        placeholder="Masukkan kapasitas KVA"
                                        value="{{ old('kapasitas_kva', $genset->kapasitas_kva) }}"
                                        min="0" step="0.01" required>
                                    @error('kapasitas_kva')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Tipe Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    @php
                                        $knownEngines  = ['Perkins', 'Yanmar', 'Stamford', 'Deepsea'];
                                        $currentEngine = old('tipe_engine', $genset->tipe_engine);
                                        $isOtherEngine = !in_array($currentEngine, $knownEngines);
                                        $selectEngineVal = $isOtherEngine ? 'Others' : $currentEngine;
                                    @endphp
                                    <select id="tipe_engine" name="tipe_engine" class="table-input" required>
                                        <option value="" disabled>Pilih Tipe Engine</option>
                                        @foreach ($knownEngines as $engine)
                                            <option value="{{ $engine }}" {{ $selectEngineVal === $engine ? 'selected' : '' }}>{{ $engine }}</option>
                                        @endforeach
                                        <option value="Others" {{ $selectEngineVal === 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <input type="text" id="tipe_engine_others" name="tipe_engine_others" class="table-input mt-2"
                                        placeholder="Masukkan tipe engine lainnya"
                                        value="{{ $isOtherEngine ? $currentEngine : '' }}"
                                        style="display: {{ $isOtherEngine ? 'block' : 'none' }};">
                                    @error('tipe_engine')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- SN Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_engine" name="sn_engine" class="table-input"
                                        placeholder="Masukkan SN engine" value="{{ old('sn_engine', $genset->sn_engine) }}" required>
                                    @error('sn_engine')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uji Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Uji Genset</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label for="tahun_pasang">Tahun Pasang <span class="required">*</span></label>
                                <select id="tahun_pasang" name="tahun_pasang" class="form-control" required>
                                    <option value="" disabled>Pilih Tahun Pasang</option>
                                </select>
                                @error('tahun_pasang')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pm">Tanggal PM <span class="required">*</span></label>
                                <input type="date" id="tanggal_pm" name="tanggal_pm" class="form-control"
                                    value="{{ old('tanggal_pm', $genset->tanggal_pm?->format('Y-m-d')) }}" required>
                                <small class="upload-info">Jadwal Pemeliharaan (PM) rutin disarankan setiap 6 bulan sekali.</small>
                                @error('tanggal_pm')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label>Status Genset</label>
                                <div class="status-display-wrapper">
                                    <div id="statusDisplay" class="form-control status-readonly-box" aria-readonly="true">
                                        <span id="statusDot" class="status-dot"></span>
                                        <strong id="statusText">-</strong>
                                        <span class="status-source">Otomatis dari sistem</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Genset & Photo Engine (Side-by-side) -->
                    <div class="form-grid-2" style="margin-bottom: 20px;">
                        <!-- Photo Genset -->
                        <div class="form-card photo-compact-card" style="margin-bottom: 0;">
                            <h3 class="form-section-title">Photo Genset</h3>
                            <div class="form-group">
                                <label style="font-size: 0.84rem; font-weight: 500; color: #334155;">Foto kondisi fisik Genset di lokasi</label>
                                <div class="photo-compact-preview" id="previewBoxGenset" onclick="document.getElementById('photo_genset').click()">
                                    @if ($genset->photo_genset)
                                        <div id="noPreviewGenset" class="photo-compact-empty" style="display: none;">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                            <small style="color: #94a3b8; font-size: 0.72rem;">Klik atau seret file ke sini</small>
                                        </div>
                                        <img id="imgGenset" src="{{ asset('storage/' . $genset->photo_genset) }}" alt="Preview Genset" class="photo-compact-img" style="display: block;">
                                    @else
                                        <div id="noPreviewGenset" class="photo-compact-empty">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                            <small style="color: #94a3b8; font-size: 0.72rem;">Klik atau seret file ke sini</small>
                                        </div>
                                        <img id="imgGenset" src="" alt="Preview Genset" class="photo-compact-img" style="display: none;">
                                    @endif
                                </div>
                                <div class="photo-compact-actions">
                                    <label for="photo_genset" class="btn-compact-browse" id="btnBrowseGenset">
                                        <i class="bi {{ $genset->photo_genset ? 'bi-arrow-repeat' : 'bi-camera-fill' }}" id="btnIconGenset"></i>
                                        <span id="btnTextGenset">{{ $genset->photo_genset ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                                    </label>
                                    <input type="file" id="photo_genset" name="photo_genset" accept="image/jpeg,image/png,image/jpg" hidden>
                                    <small class="upload-info">Format: JPG, JPEG, PNG (Maks. 10 MB)</small>
                                </div>
                                @error('photo_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="keterangan_gambar_genset">Keterangan Foto</label>
                                <input type="text" id="keterangan_gambar_genset" name="keterangan_gambar_genset"
                                    class="form-control" placeholder="Masukkan keterangan gambar"
                                    value="{{ old('keterangan_gambar_genset', $genset->keterangan_gambar_genset) }}">
                            </div>
                        </div>

                        <!-- Photo Engine -->
                        <div class="form-card photo-compact-card" style="margin-bottom: 0;">
                            <h3 class="form-section-title">Photo Engine</h3>
                            <div class="form-group">
                                <label style="font-size: 0.84rem; font-weight: 500; color: #334155;">Foto kondisi Engine di lokasi</label>
                                <div class="photo-compact-preview" id="previewBoxEngine" onclick="document.getElementById('photo_engine').click()">
                                    @if ($genset->photo_engine)
                                        <div id="noPreviewEngine" class="photo-compact-empty" style="display: none;">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                            <small style="color: #94a3b8; font-size: 0.72rem;">Klik atau seret file ke sini</small>
                                        </div>
                                        <img id="imgEngine" src="{{ asset('storage/' . $genset->photo_engine) }}" alt="Preview Engine" class="photo-compact-img" style="display: block;">
                                    @else
                                        <div id="noPreviewEngine" class="photo-compact-empty">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                            <small style="color: #94a3b8; font-size: 0.72rem;">Klik atau seret file ke sini</small>
                                        </div>
                                        <img id="imgEngine" src="" alt="Preview Engine" class="photo-compact-img" style="display: none;">
                                    @endif
                                </div>
                                <div class="photo-compact-actions">
                                    <label for="photo_engine" class="btn-compact-browse" id="btnBrowseEngine">
                                        <i class="bi {{ $genset->photo_engine ? 'bi-arrow-repeat' : 'bi-camera-fill' }}" id="btnIconEngine"></i>
                                        <span id="btnTextEngine">{{ $genset->photo_engine ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                                    </label>
                                    <input type="file" id="photo_engine" name="photo_engine" accept="image/jpeg,image/png,image/jpg" hidden>
                                    <small class="upload-info">Format: JPG, JPEG, PNG (Maks. 10 MB)</small>
                                </div>
                                @error('photo_engine')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="keterangan_gambar_engine">Keterangan Foto</label>
                                <input type="text" id="keterangan_gambar_engine" name="keterangan_gambar_engine"
                                    class="form-control" placeholder="Masukkan keterangan gambar"
                                    value="{{ old('keterangan_gambar_engine', $genset->keterangan_gambar_engine) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('gensets.show', [$pop->id, $genset->id]) }}" class="btn-reset"
                            style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const selectedMerk  = @json($selectMerkVal ?? '');
        const selectedModel = @json(old('model', $genset->model));
        const selectedTahun = @json(old('tahun_pasang', $genset->tahun_pasang));

        const modelData = {
            'Potise':    ['Model Potise A', 'Model Potise B'],
            'Ada':       ['Model Ada X', 'Model Ada Y'],
            'Perkins':   ['Perkins 1104A', 'Perkins 2206S', 'Perkins 4003'],
            'Truepower': ['Truepower TP-10', 'Truepower TP-20'],
            'Stamford':  ['Stamford UCI224', 'Stamford S4L1'],
            'Yanmar':    ['Yanmar 3TNV', 'Yanmar 4TNV98'],
            'Himoinsa':  ['Himoinsa HFW-50', 'Himoinsa HYW-13'],
            'Cummins':   ['Cummins C33D5', 'Cummins C55D5'],
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Populate Tahun Pasang
            const tahunPasangSelect = document.getElementById('tahun_pasang');
            for (let tahun = 2013; tahun <= 2045; tahun++) {
                let option = document.createElement('option');
                option.value = tahun;
                option.textContent = tahun;
                if (tahun.toString() === selectedTahun.toString()) option.selected = true;
                tahunPasangSelect.appendChild(option);
            }

            const merkGenset       = document.getElementById('merk_genset');
            const modelSelect      = document.getElementById('model');
            const merkOthersInput  = document.getElementById('merk_genset_others');
            const modelOthersInput = document.getElementById('model_others');

            // Populate model saat page load (dari data existing)
            if (selectedMerk && selectedMerk !== 'Others') {
                populateModels(selectedMerk, selectedModel);
            }

            function populateModels(merk, currentModelValue = '') {
                modelSelect.innerHTML = '<option value="" disabled>Pilih Model</option>';
                modelSelect.disabled = false;
                if (modelData[merk]) {
                    modelData[merk].forEach(m => {
                        let opt = document.createElement('option');
                        opt.value = m;
                        opt.textContent = m;
                        if (m === currentModelValue) opt.selected = true;
                        modelSelect.appendChild(opt);
                    });
                }
                let optOthers = document.createElement('option');
                optOthers.value = 'Others';
                optOthers.textContent = 'Others';
                if (currentModelValue === 'Others' || !modelData[merk]?.includes(currentModelValue)) {
                    // Jika model existing tidak ada di list, berarti Others
                    if (!modelData[merk]?.includes(currentModelValue) && currentModelValue !== '') {
                        optOthers.selected = true;
                        modelOthersInput.value = currentModelValue;
                        modelOthersInput.style.display = 'block';
                        modelOthersInput.required = true;
                    }
                }
                modelSelect.appendChild(optOthers);
            }

            merkGenset.addEventListener('change', function () {
                const merk = this.value;
                modelOthersInput.style.display = 'none';
                modelOthersInput.required = false;
                modelOthersInput.value = '';
                if (merk === 'Others') {
                    merkOthersInput.style.display = 'block';
                    merkOthersInput.required = true;
                    modelSelect.disabled = true;
                    modelSelect.innerHTML = '<option value="" disabled selected>Pilih Model</option>';
                } else {
                    merkOthersInput.style.display = 'none';
                    merkOthersInput.required = false;
                    populateModels(merk);
                }
            });

            modelSelect.addEventListener('change', function () {
                if (this.value === 'Others') {
                    modelOthersInput.style.display = 'block';
                    modelOthersInput.required = true;
                } else {
                    modelOthersInput.style.display = 'none';
                    modelOthersInput.required = false;
                    modelOthersInput.value = '';
                }
            });

            // Tipe Engine Others
            const tipeEngine        = document.getElementById('tipe_engine');
            const engineOthersInput = document.getElementById('tipe_engine_others');
            tipeEngine.addEventListener('change', function () {
                if (this.value === 'Others') {
                    engineOthersInput.style.display = 'block';
                    engineOthersInput.required = true;
                } else {
                    engineOthersInput.style.display = 'none';
                    engineOthersInput.required = false;
                    engineOthersInput.value = '';
                }
            });

            // Status PM preview
            const tanggalPmInput = document.getElementById('tanggal_pm');
            tanggalPmInput.addEventListener('change', updateStatusPreview);
            updateStatusPreview();

            function updateStatusPreview() {
                const val  = tanggalPmInput.value;
                const dot  = document.getElementById('statusDot');
                const text = document.getElementById('statusText');
                if (!val) {
                    dot.style.background = '#94a3b8';
                    text.textContent = 'Belum PM';
                    return;
                }
                const pm         = new Date(val);
                const now        = new Date();
                const diffMonth  = (now - pm) / (1000 * 60 * 60 * 24 * 30);
                if (diffMonth >= 6) {
                    dot.style.background = '#f59e0b';
                    text.textContent = 'Jadwal PM';
                } else {
                    dot.style.background = '#10b981';
                    text.textContent = 'Sudah PM';
                }
            }

            // Image Previews (Compact upload with drag-and-drop & button toggle)
            function setupCompactUpload(inputId, boxId, imgId, noPreviewId, btnTextId, btnIconId) {
                const input      = document.getElementById(inputId);
                const previewBox = document.getElementById(boxId);
                const img        = document.getElementById(imgId);
                const noPrev     = document.getElementById(noPreviewId);
                const btnText    = document.getElementById(btnTextId);
                const btnIcon    = document.getElementById(btnIconId);

                if (!input || !previewBox) return;

                function handleFile(file) {
                    if (!file || !file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        img.src = e.target.result;
                        img.style.display = 'block';
                        if (noPrev) noPrev.style.display = 'none';
                        if (btnText) btnText.textContent = 'Ganti Foto';
                        if (btnIcon) btnIcon.className = 'bi bi-arrow-repeat';
                    };
                    reader.readAsDataURL(file);
                }

                input.addEventListener('change', function (e) {
                    if (e.target.files && e.target.files[0]) {
                        handleFile(e.target.files[0]);
                    }
                });

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
                previewBox.addEventListener('drop', (e) => {
                    e.preventDefault();
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        input.files = files;
                        handleFile(files[0]);
                    }
                });
            }

            setupCompactUpload('photo_genset', 'previewBoxGenset', 'imgGenset', 'noPreviewGenset', 'btnTextGenset', 'btnIconGenset');
            setupCompactUpload('photo_engine', 'previewBoxEngine', 'imgEngine', 'noPreviewEngine', 'btnTextEngine', 'btnIconEngine');
        });

        function openPhotoModal(src, title) {
            if (!src) return;
            Swal.fire({
                title: title || 'Foto',
                imageUrl: src,
                imageAlt: title || 'Foto',
                showCloseButton: true,
                showConfirmButton: false,
                width: 'auto',
                customClass: {
                    popup: 'swal-popup-custom'
                }
            });
        }
    </script>
</body>
</html>