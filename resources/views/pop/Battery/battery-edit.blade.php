<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Baterai - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
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
                <div class="detail-page-header">
                    <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" id="batteryEditForm">
                    @csrf
                    @method('PUT')

                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>POP</label>
                                <input type="text" class="form-control disabled-input" value="POP_1MBN10004" disabled>
                            </div>

                            <div class="form-group">
                                <label>Building <span class="required">*</span></label>
                                <input type="text" class="form-control" name="building" value="17/08/2026" required>
                            </div>

                            <div class="form-group">
                                <label>PIC <span class="required">*</span></label>
                                <input type="text" class="form-control" name="pic" value="POP-SB" required>
                            </div>

                            <div class="form-group">
                                <label>Type POP <span class="required">*</span></label>
                                <input type="text" class="form-control" name="type_pop" value="POP-SB" required>
                            </div>

                            <div class="form-group">
                                <label>Recti <span class="required">*</span></label>
                                <input type="text" class="form-control" name="recti" value="40 A DC / 13 A AC" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <p class="form-section-subtitle">Informasi Baterai</p>
                        
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" class="table-input" name="nomor_recti" value="POP_1SRG012" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" class="table-input" name="nomor_bank" value="BANK01" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="merk_battery" name="merk_battery" class="table-input" required>
                                        <option value="" disabled>Pilih Merk</option>
                                        <option value="Sacred Sun" selected>Sacred Sun</option>
                                        <option value="BSB">BSB</option>
                                        <option value="Fortis Power">Fortis Power</option>
                                        <option value="Monolite">Monolite</option>
                                        <option value="Nagoya">Nagoya</option>
                                        <option value="Narada">Narada</option>
                                        <option value="Nippres">Nippres</option>
                                        <option value="Sinergi">Sinergi</option>
                                        <option value="Vision">Vision</option>
                                    </select>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Jenis Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="jenis_battery" name="jenis_battery" class="table-input" required>
                                        <option value="" disabled>Pilih Jenis</option>
                                        <option value="Lithium" selected>Lithium</option>
                                        <option value="VRLA">VRLA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" class="table-input" name="tipe_battery" value="SSIFP48100B" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" step="0.01" min="0" class="table-input" name="tegangan" value="48" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" step="0.01" min="0" id="kapasitas_battery" name="kapasitas_battery" class="table-input" value="100" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <!-- Wrapper Lithium (1 Input) -->
                                    <div id="lithiumUjiWrapper" class="uji-wrapper" style="width: 100%;">
                                        <input type="number" step="0.01" min="0" id="kapasitas_uji_1" name="kapasitas_uji[]" class="table-input" value="100.00" placeholder="Masukkan kapasitas uji">
                                    </div>

                                    <!-- Wrapper VRLA (4 Input 2x2 Grid) -->
                                    <div id="vrlaUjiWrapper" class="uji-grid-4" style="display: none; width: 100%;">
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 1</span>
                                            <input type="number" step="0.01" min="0" name="kapasitas_uji[]" class="table-input vrla-input" value="25.00" placeholder="Kapasitas 1">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 3</span>
                                            <input type="number" step="0.01" min="0" name="kapasitas_uji[]" class="table-input vrla-input" value="25.00" placeholder="Kapasitas 3">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 2</span>
                                            <input type="number" step="0.01" min="0" name="kapasitas_uji[]" class="table-input vrla-input" value="25.00" placeholder="Kapasitas 2">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 4</span>
                                            <input type="number" step="0.01" min="0" name="kapasitas_uji[]" class="table-input vrla-input" value="25.00" placeholder="Kapasitas 4">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery %</div>
                                <div class="checklist-field">
                                    <input type="text" id="kapasitas_persen" name="kapasitas_persen" class="table-input auto-field" value="100.00%" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label>Tanggal Uji Terakhir</label>
                                <input type="date" class="form-control" name="tanggal_uji_terakhir" value="2025-10-31">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Penggantian <span class="required">*</span></label>
                                <input type="date" class="form-control" name="tanggal_penggantian" value="2020-05-05" required>
                            </div>

                            <div class="form-group">
                                <label>Status Uji Baterai</label>
                                <div class="status-display-wrapper">
                                    <div id="statusUjiDisplay" class="form-control status-readonly-box status-good" aria-readonly="true">
                                        <span class="status-dot status-dot-good" id="statusDot"></span>
                                        <strong id="statusUjiText">GOOD</strong>
                                        <span class="status-source">Otomatis dari sistem</span>
                                    </div>
                                </div>
                                <input type="hidden" id="status_uji" name="status_uji" value="good">
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Photo Battery -->
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
                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks, ukuran : 10 MB</small>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="keterangan_gambar">Tuliskan keterangan gambar</label>
                            <input type="text" id="keterangan_gambar" name="keterangan_gambar" class="form-control" value="Kondisi baterai aman dan aktif di lokasi POP" placeholder="Masukkan keterangan gambar">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn-reset" id="resetButton">Reset</button>
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
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
            const lithiumWrapper = document.getElementById('lithiumUjiWrapper');
            const vrlaWrapper = document.getElementById('vrlaUjiWrapper');
            
            const statusDisplay = document.getElementById('statusUjiDisplay');
            const statusText = document.getElementById('statusUjiText');
            const statusDot = document.getElementById('statusDot');
            const statusInput = document.getElementById('status_uji');
            const resetButton = document.getElementById('resetButton');

            // Logika Jenis Baterai (Lithium vs VRLA)
            jenisBattery.addEventListener('change', function () {
                if (this.value === 'Lithium') {
                    lithiumWrapper.style.display = 'block';
                    vrlaWrapper.style.display = 'none';
                } else if (this.value === 'VRLA') {
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'grid';
                }
                updateBatteryCalculation();
            });

            // Trigger awal sesuai selected value di awal load
            if (jenisBattery.value === 'VRLA') {
                lithiumWrapper.style.display = 'none';
                vrlaWrapper.style.display = 'grid';
            } else {
                lithiumWrapper.style.display = 'block';
                vrlaWrapper.style.display = 'none';
            }

            function updateStatus(persen) {
                statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

                let status = '';
                let label = '';

                if (persen >= 80) {
                    status = 'excellent';
                    label = 'EXCELLENT';
                } else if (persen >= 60) {
                    status = 'good';
                    label = 'GOOD';
                } else if (persen >= 40) {
                    status = 'warning';
                    label = 'WARNING';
                } else {
                    status = 'danger';
                    label = 'POOR';
                }

                statusDisplay.classList.add('status-' + status);
                statusDot.classList.add('status-dot-' + status);
                statusText.textContent = label;
                statusInput.value = status;
            }

            function updateBatteryCalculation() {
                const battery = parseFloat(kapasitasBattery.value) || 0;
                let ujiPertama = 0;

                if (jenisBattery.value === 'Lithium') {
                    ujiPertama = parseFloat(document.getElementById('kapasitas_uji_1').value) || 0;
                } else if (jenisBattery.value === 'VRLA') {
                    const firstVrlaInput = document.querySelector('.vrla-input');
                    ujiPertama = firstVrlaInput ? (parseFloat(firstVrlaInput.value) || 0) : 0;
                }

                if (battery > 0 && ujiPertama >= 0) {
                    const persen = (ujiPertama / battery) * 100;
                    kapasitasPersen.value = persen.toFixed(2) + '%';
                    updateStatus(persen);
                } else {
                    kapasitasPersen.value = '0.00%';
                    updateStatus(0);
                }
            }

            kapasitasBattery.addEventListener('input', updateBatteryCalculation);
            document.getElementById('kapasitas_uji_1').addEventListener('input', updateBatteryCalculation);
            document.querySelectorAll('.vrla-input').forEach(el => {
                el.addEventListener('input', updateBatteryCalculation);
            });

            // Preview Foto Handler
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

            updateBatteryCalculation();

            resetButton.addEventListener('click', function () {
                setTimeout(function () {
                    updateBatteryCalculation();
                    previewImage.style.display = 'none';
                    noPreviewText.style.display = 'flex';
                }, 50);
            });
        });
    </script>
</body>
</html>