<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Genset - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/genset-create.css'
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
                    <div class="rectifier-page-info">
                        <a href="{{ route('gensets.index', $pop->id) }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
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

                <form action="{{ route('gensets.store', $pop->id) }}" method="POST" id="gensetForm" enctype="multipart/form-data">
                    @csrf

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" class="form-control disabled-input" value="{{ $pop->kode_pop }}" readonly>
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
                                    placeholder="Masukkan PIC" value="{{ old('pic') }}" required>
                                @error('pic')<div class="invalid-feedback" style="color:#ef4444;font-size:0.8rem;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="bentuk_fisik">Building <span class="required">*</span></label>
                                <input type="text" id="bentuk_fisik" name="bentuk_fisik" class="form-control @error('bentuk_fisik') is-invalid @enderror"
                                    placeholder="Masukkan bentuk fisik" value="{{ old('bentuk_fisik') }}" required>
                                @error('bentuk_fisik')<div class="invalid-feedback" style="color:#ef4444;font-size:0.8rem;">{{ $message }}</div>@enderror
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
                                    <select id="merk_genset" name="merk_genset" class="table-input" required>
                                        <option value="" disabled {{ old('merk_genset') ? '' : 'selected' }}>Pilih Merk</option>
                                        @foreach (['Potise', 'Ada', 'Perkins', 'Truepower', 'Stamford', 'Yanmar', 'Himoinsa', 'Cummins', 'Others'] as $merk)
                                            <option value="{{ $merk }}" {{ old('merk_genset') === $merk ? 'selected' : '' }}>{{ $merk }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="merk_genset_others" name="merk_genset_others" class="table-input mt-2"
                                        placeholder="Masukkan merk lainnya"
                                        value="{{ old('merk_genset_others') }}"
                                        style="display: {{ old('merk_genset') === 'Others' ? 'block' : 'none' }};">
                                    @error('merk_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="checklist-row">
                                <div class="checklist-label">Model <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="model" name="model" class="table-input" required {{ old('merk_genset') ? '' : 'disabled' }}>
                                        <option value="" disabled {{ old('model') ? '' : 'selected' }}>Pilih merk genset terlebih dahulu</option>
                                    </select>
                                    <input type="text" id="model_others" name="model_others" class="table-input mt-2"
                                        placeholder="Masukkan model lainnya"
                                        value="{{ old('model_others') }}"
                                        style="display: {{ old('model') === 'Others' ? 'block' : 'none' }};">
                                    @error('model')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- SN Genset -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Genset <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_genset" name="sn_genset" class="table-input"
                                        placeholder="Masukkan SN genset" value="{{ old('sn_genset') }}" required>
                                    @error('sn_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Kapasitas (KVA) -->
                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas (KVA) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="kapasitas_kva" name="kapasitas_kva" class="table-input"
                                        placeholder="Masukkan kapasitas KVA" value="{{ old('kapasitas_kva') }}" min="0" step="0.01" required>
                                    @error('kapasitas_kva')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Tipe Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="tipe_engine" name="tipe_engine" class="table-input" required>
                                        <option value="" disabled {{ old('tipe_engine') ? '' : 'selected' }}>Pilih Tipe Engine</option>
                                        @foreach (['Perkins', 'Yanmar', 'Stamford', 'Deepsea', 'Others'] as $engine)
                                            <option value="{{ $engine }}" {{ old('tipe_engine') === $engine ? 'selected' : '' }}>{{ $engine }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="tipe_engine_others" name="tipe_engine_others" class="table-input mt-2"
                                        placeholder="Masukkan tipe engine lainnya"
                                        value="{{ old('tipe_engine_others') }}"
                                        style="display: {{ old('tipe_engine') === 'Others' ? 'block' : 'none' }};">
                                    @error('tipe_engine')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- SN Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_engine" name="sn_engine" class="table-input"
                                        placeholder="Masukkan SN engine" value="{{ old('sn_engine') }}" required>
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
                                    <option value="" disabled selected>Pilih Tahun Pasang</option>
                                </select>
                                @error('tahun_pasang')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pm">Tanggal PM <span class="required">*</span></label>
                                <input type="date" id="tanggal_pm" name="tanggal_pm" class="form-control"
                                    value="{{ old('tanggal_pm') }}" required>
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

                    <!-- Photo Genset -->
                    <div class="form-card">
                        <h3 class="form-section-title">Photo Genset</h3>
                        <div class="form-group">
                            <label>Upload foto kondisi Genset di lokasi</label>
                            <div class="upload-container">
                                <div class="upload-dropzone" id="dropzoneGenset">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <span class="upload-text">Masukkan file disini</span>
                                    <label for="photo_genset" class="btn-browse">Browse</label>
                                    <input type="file" id="photo_genset" name="photo_genset" accept="image/jpeg,image/png,image/jpg" hidden>
                                </div>
                                <div class="preview-container">
                                    <span class="preview-title">Preview foto</span>
                                    <div class="preview-box">
                                        <img id="previewGenset" src="" alt="Preview Genset" style="display: none;">
                                        <div id="noPreviewGenset" class="no-preview">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                            @error('photo_genset')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="keterangan_gambar_genset">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar_genset" name="keterangan_gambar_genset"
                                class="form-control" placeholder="Masukkan keterangan gambar"
                                value="{{ old('keterangan_gambar_genset') }}">
                        </div>
                    </div>

                    <!-- Photo Engine -->
                    <div class="form-card">
                        <h3 class="form-section-title">Photo Engine</h3>
                        <div class="form-group">
                            <label>Upload foto kondisi Engine di lokasi</label>
                            <div class="upload-container">
                                <div class="upload-dropzone" id="dropzoneEngine">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <span class="upload-text">Masukkan file disini</span>
                                    <label for="photo_engine" class="btn-browse">Browse</label>
                                    <input type="file" id="photo_engine" name="photo_engine" accept="image/jpeg,image/png,image/jpg" hidden>
                                </div>
                                <div class="preview-container">
                                    <span class="preview-title">Preview foto</span>
                                    <div class="preview-box">
                                        <img id="previewEngine" src="" alt="Preview Engine" style="display: none;">
                                        <div id="noPreviewEngine" class="no-preview">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                            @error('photo_engine')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="keterangan_gambar_engine">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar_engine" name="keterangan_gambar_engine"
                                class="form-control" placeholder="Masukkan keterangan gambar"
                                value="{{ old('keterangan_gambar_engine') }}">
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
        const oldMerk  = @json(old('merk_genset', ''));
        const oldModel = @json(old('model', ''));

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
            // Populate Tahun Pasang (2013 - 2045)
            const tahunPasangSelect = document.getElementById('tahun_pasang');
            const oldTahun = @json(old('tahun_pasang', ''));
            for (let tahun = 2013; tahun <= 2045; tahun++) {
                let option = document.createElement('option');
                option.value = tahun;
                option.textContent = tahun;
                if (tahun.toString() === oldTahun.toString()) option.selected = true;
                tahunPasangSelect.appendChild(option);
            }

            const merkGenset      = document.getElementById('merk_genset');
            const modelSelect     = document.getElementById('model');
            const merkOthersInput = document.getElementById('merk_genset_others');
            const modelOthersInput = document.getElementById('model_others');

            // Restore state dari old() jika ada (setelah validation gagal)
            if (oldMerk && oldMerk !== 'Others' && modelData[oldMerk]) {
                populateModels(oldMerk, oldModel);
            }

            function populateModels(selectedMerk, currentModelValue = '') {
                modelSelect.innerHTML = '<option value="" disabled>Pilih Model</option>';
                modelSelect.disabled = false;
                if (modelData[selectedMerk]) {
                    modelData[selectedMerk].forEach(m => {
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
                if (currentModelValue === 'Others') {
                    optOthers.selected = true;
                    modelOthersInput.style.display = 'block';
                    modelOthersInput.required = true;
                }
                modelSelect.appendChild(optOthers);
            }

            merkGenset.addEventListener('change', function () {
                const selectedMerk = this.value;
                modelOthersInput.style.display = 'none';
                modelOthersInput.required = false;
                modelOthersInput.value = '';

                if (selectedMerk === 'Others') {
                    merkOthersInput.style.display = 'block';
                    merkOthersInput.required = true;
                    modelSelect.disabled = true;
                    modelSelect.innerHTML = '<option value="" disabled selected>Pilih Model</option>';
                } else {
                    merkOthersInput.style.display = 'none';
                    merkOthersInput.required = false;
                    populateModels(selectedMerk);
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
            const tipeEngine = document.getElementById('tipe_engine');
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

            // Status PM preview (live dari tanggal_pm)
            const tanggalPmInput = document.getElementById('tanggal_pm');
            tanggalPmInput.addEventListener('change', updateStatusPreview);
            updateStatusPreview();

            function updateStatusPreview() {
                const val = tanggalPmInput.value;
                const dot  = document.getElementById('statusDot');
                const text = document.getElementById('statusText');
                if (!val) {
                    dot.style.background  = '#94a3b8';
                    text.textContent = 'Belum PM';
                    return;
                }
                const pm       = new Date(val);
                const now      = new Date();
                const diffMs   = now - pm;
                const diffMonth = diffMs / (1000 * 60 * 60 * 24 * 30);
                if (diffMonth >= 6) {
                    dot.style.background  = '#f59e0b';
                    text.textContent = 'Jadwal PM';
                } else {
                    dot.style.background  = '#10b981';
                    text.textContent = 'Sudah PM';
                }
            }

            // Image Previews
            function setupImagePreview(inputId, previewImgId, noPreviewId) {
                const input      = document.getElementById(inputId);
                const previewImg = document.getElementById(previewImgId);
                const noPreview  = document.getElementById(noPreviewId);
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            previewImg.src = event.target.result;
                            previewImg.style.display = 'block';
                            noPreview.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            setupImagePreview('photo_genset', 'previewGenset', 'noPreviewGenset');
            setupImagePreview('photo_engine', 'previewEngine', 'noPreviewEngine');
        });
    </script>
</body>
</html>