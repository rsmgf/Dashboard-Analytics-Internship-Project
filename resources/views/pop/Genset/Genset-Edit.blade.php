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
                        <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <form action="#" method="POST" id="gensetForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" name="pop" class="form-control disabled-input" value="POP_1MBN10004" readonly>
                            </div>

                            <div class="form-group">
                                <label for="zona">Zona <span class="required">*</span></label>
                                <input type="text" id="zona" name="zona" class="form-control" value="Zona 1" placeholder="Masukkan zona" required>
                            </div>

                            <div class="form-group">
                                <label for="pic">PIC <span class="required">*</span></label>
                                <input type="text" id="pic" name="pic" class="form-control" value="Ahmad Fauzi" placeholder="Masukkan PIC" required>
                            </div>

                            <div class="form-group">
                                <label for="type_pop">Type POP <span class="required">*</span></label>
                                <input type="text" id="type_pop" name="type_pop" class="form-control" value="Main Hub" placeholder="Masukkan type POP" required>
                            </div>

                            <div class="form-group">
                                <label for="bentuk_fisik">Bentuk Fisik <span class="required">*</span></label>
                                <input type="text" id="bentuk_fisik" name="bentuk_fisik" class="form-control" value="Indoor Cabinet" placeholder="Masukkan bentuk fisik" required>
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
                                        <option value="" disabled>Pilih Merk</option>
                                        <option value="Potise">Potise</option>
                                        <option value="Ada">Ada</option>
                                        <option value="Perkins" selected>Perkins</option>
                                        <option value="Truepower">Truepower</option>
                                        <option value="Stamford">Stamford</option>
                                        <option value="Yanmar">Yanmar</option>
                                        <option value="Himoinsa">Himoinsa</option>
                                        <option value="Cummins">Cummins</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <input type="text" id="merk_genset_others" name="merk_genset_others" class="table-input mt-2" placeholder="Masukkan merk lainnya" style="display: none;">
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="checklist-row">
                                <div class="checklist-label">Model <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="model" name="model" class="table-input" required>
                                        <option value="" disabled>Pilih Model</option>
                                        <option value="Perkins 1104A" selected>Perkins 1104A</option>
                                        <option value="Perkins 2206S">Perkins 2206S</option>
                                        <option value="Perkins 4003">Perkins 4003</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <input type="text" id="model_others" name="model_others" class="table-input mt-2" placeholder="Masukkan model lainnya" style="display: none;">
                                </div>
                            </div>

                            <!-- Tipe Model -->
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Model <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="tipe_model" name="tipe_model" class="table-input" required>
                                        <option value="" disabled>Pilih Tipe Model</option>
                                        <option value="Model A" selected>Model A</option>
                                        <option value="Model B">Model B</option>
                                    </select>
                                </div>
                            </div>

                            <!-- SN Genset -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Genset <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_genset" name="sn_genset" class="table-input" value="SN-PRK-998231" placeholder="Masukkan SN genset" required>
                                </div>
                            </div>

                            <!-- Kapasitas (KVA) -->
                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas (KVA) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="kapasitas_kva" name="kapasitas_kva" class="table-input" value="50" placeholder="Masukkan kapasitas KVA" required>
                                </div>
                            </div>

                            <!-- Tipe Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="tipe_engine" name="tipe_engine" class="table-input" required>
                                        <option value="" disabled>Pilih Tipe Engine</option>
                                        <option value="Perkins" selected>Perkins</option>
                                        <option value="Yanmar">Yanmar</option>
                                        <option value="Stamford">Stamford</option>
                                        <option value="Deepsea">Deepsea</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <input type="text" id="tipe_engine_others" name="tipe_engine_others" class="table-input mt-2" placeholder="Masukkan tipe engine lainnya" style="display: none;">
                                </div>
                            </div>

                            <!-- SN Engine -->
                            <div class="checklist-row">
                                <div class="checklist-label">SN Engine <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="sn_engine" name="sn_engine" class="table-input" value="ENG-PRK-44512" placeholder="Masukkan SN engine" required>
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
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pm">Tanggal PM <span class="required">*</span></label>
                                <input type="date" id="tanggal_pm" name="tanggal_pm" class="form-control" value="2026-06-12" required>
                                <small class="upload-info">Jadwal Pemeliharaan (PM) rutin disarankan setiap 6 bulan sekali.</small>
                            </div>

                            <div class="form-group">
                                <label>Status Genset</label>
                                <input type="hidden" name="status_genset" id="status_genset" value="excellent">
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
                                        <img id="previewGenset" src="https://via.placeholder.com/600x400?text=Foto+Genset" alt="Preview Genset" style="display: block;">
                                        <div id="noPreviewGenset" class="no-preview" style="display: none;">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="keterangan_gambar_genset">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar_genset" name="keterangan_gambar_genset" class="form-control" value="Kondisi unit genset terpasang rapi di shelter utama POP." placeholder="Masukkan keterangan gambar">
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
                                        <img id="previewEngine" src="https://via.placeholder.com/600x400?text=Foto+Engine" alt="Preview Engine" style="display: block;">
                                        <div id="noPreviewEngine" class="no-preview" style="display: none;">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran: 10 MB</small>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="keterangan_gambar_engine">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar_engine" name="keterangan_gambar_engine" class="form-control" value="Kondisi mesin engine bersih tanpa indikasi kebocoran oli." placeholder="Masukkan keterangan gambar">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ url()->previous() }}" class="btn-reset" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Populate Tahun Pasang (2013 - 2045)
            const tahunPasangSelect = document.getElementById('tahun_pasang');
            const selectedTahun = "2021";
            for (let tahun = 2013; tahun <= 2045; tahun++) {
                let option = document.createElement('option');
                option.value = tahun;
                option.textContent = tahun;
                if (tahun.toString() === selectedTahun) {
                    option.selected = true;
                }
                tahunPasangSelect.appendChild(option);
            }

            // Mapping Model berdasarkan Merk Genset
            const modelData = {
                'Potise': ['Model Potise A', 'Model Potise B'],
                'Ada': ['Model Ada X', 'Model Ada Y'],
                'Perkins': ['Perkins 1104A', 'Perkins 2206S', 'Perkins 4003'],
                'Truepower': ['Truepower TP-10', 'Truepower TP-20'],
                'Stamford': ['Stamford UCI224', 'Stamford S4L1'],
                'Yanmar': ['Yanmar 3TNV', 'Yanmar 4TNV98'],
                'Himoinsa': ['Himoinsa HFW-50', 'Himoinsa HYW-13'],
                'Cummins': ['Cummins C33D5', 'Cummins C55D5']
            };

            const merkGenset = document.getElementById('merk_genset');
            const modelSelect = document.getElementById('model');
            const merkOthersInput = document.getElementById('merk_genset_others');
            const modelOthersInput = document.getElementById('model_others');

            function updateModelOptions(selectedMerk, currentModelValue = '') {
                modelSelect.innerHTML = '<option value="" disabled>Pilih Model</option>';
                modelSelect.disabled = false;
                merkOthersInput.style.display = 'none';
                merkOthersInput.required = false;

                if (selectedMerk === 'Others') {
                    merkOthersInput.style.display = 'block';
                    merkOthersInput.required = true;
                    modelSelect.disabled = true;
                } else {
                    if (modelData[selectedMerk]) {
                        modelData[selectedMerk].forEach(model => {
                            let opt = document.createElement('option');
                            opt.value = model;
                            opt.textContent = model;
                            if (model === currentModelValue) {
                                opt.selected = true;
                            }
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
            }

            merkGenset.addEventListener('change', function () {
                updateModelOptions(this.value);
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

            // Tipe Engine Others Logic
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

            // Image Previews Setup
            function setupImagePreview(inputId, previewImgId, noPreviewId) {
                const input = document.getElementById(inputId);
                const previewImg = document.getElementById(previewImgId);
                const noPreview = document.getElementById(noPreviewId);

                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            previewImg.src = event.target.result;
                            previewImg.style.display = 'block';
                            noPreview.style.display = 'none';
                        }
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