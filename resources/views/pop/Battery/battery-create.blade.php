<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Baterai - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
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
                    <div class="rectifier-page-info">
                        <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <form action="#" method="POST" id="batteryForm" enctype="multipart/form-data">
                    @csrf

                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" name="pop" class="form-control disabled-input" value="POP_1MBN10004" readonly>
                            </div>

                            <div class="form-group">
                                <label for="building">Building <span class="required">*</span></label>
                                <input type="text" id="building" name="building" class="form-control" placeholder="Masukkan building" required>
                            </div>

                            <div class="form-group">
                                <label for="pic">PIC <span class="required">*</span></label>
                                <input type="text" id="pic" name="pic" class="form-control" placeholder="Masukkan PIC" required>
                            </div>

                            <div class="form-group">
                                <label for="type_pop">Type POP <span class="required">*</span></label>
                                <input type="text" id="type_pop" name="type_pop" class="form-control" placeholder="Masukkan type POP" required>
                            </div>

                            <div class="form-group">
                                <label for="recti">Recti <span class="required">*</span></label>
                                <input type="text" id="recti" name="recti" class="form-control" placeholder="Masukkan recti" required>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Baterai dengan Desain Tabel Baris -->
                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <div class="checklist-table-container">
                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Recti <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="nomor_recti" name="nomor_recti" class="table-input" placeholder="Masukkan nomor recti" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Nomor Bank <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="nomor_bank" name="nomor_bank" class="table-input" placeholder="Masukkan nomor bank" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Merk Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="merk_battery" name="merk_battery" class="table-input" required>
                                        <option value="" disabled selected>Pilih Merk</option>
                                        <option value="Sacred Sun">Sacred Sun</option>
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
                                        <option value="" disabled selected>Pilih Jenis</option>
                                        <option value="Lithium">Lithium</option>
                                        <option value="VRLA">VRLA</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tipe Battery diubah menjadi Dropdown -->
                            <div class="checklist-row">
                                <div class="checklist-label">Tipe Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <select id="tipe_battery" name="tipe_battery" class="table-input" required>
                                        <option value="" disabled selected>Pilih Tipe</option>
                                        <option value="SSIFP48100B">SSIFP48100B</option>
                                        <option value="LFPG12100FT">LFPG12100FT</option>
                                        <option value="NSGF 12-100">NSGF 12-100</option>
                                        <option value="NSAF 12-100">NSAF 12-100</option>
                                        <option value="SINELITH-LPF48S-100">SINELITH-LPF48S-100</option>
                                        <option value="CT12-100EX">CT12-100EX</option>
                                        <option value="NSLi 48-100">NSLi 48-100</option>
                                        <option value="LIB 48-100">LIB 48-100</option>
                                        <option value="48NPFC100">48NPFC100</option>
                                        <option value="FP-4850-Li-ico">FP-4850-Li-ico</option>
                                        <option value="SSIFP15S48100A">SSIFP15S48100A</option>
                                        <option value="SINELITH LFP48-50">SINELITH LFP48-50</option>
                                        <option value="SINELITH LFP48-100">SINELITH LFP48-100</option>
                                        <option value="SSIFP18100B">SSIFP18100B</option>
                                        <option value="NSAF 12-200">NSAF 12-200</option>
                                        <option value="12FIT100/M">12FIT100/M</option>
                                    </select>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Tegangan (V) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="tegangan" name="tegangan" class="table-input" min="0" step="0.01" placeholder="Masukkan tegangan" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery (AH) <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="number" id="kapasitas_battery" name="kapasitas_battery" class="table-input" min="0" step="0.01" placeholder="Masukkan kapasitas battery" required>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Uji Battery <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <div id="defaultUjiText" class="default-uji-text">Pilih jenis battery terlebih dahulu</div>
                                    
                                    <!-- Lithium (1 Input) -->
                                    <div id="lithiumUjiWrapper" class="uji-wrapper" style="display: none; width: 100%;">
                                        <input type="number" id="kapasitas_uji_1" name="kapasitas_uji[]" class="table-input" min="0" step="0.01" placeholder="Masukkan kapasitas uji">
                                    </div>

                                    <!-- VRLA (4 Input 2x2 Grid) -->
                                    <div id="vrlaUjiWrapper" class="uji-grid-4" style="display: none; width: 100%;">
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 1</span>
                                            <input type="number" name="kapasitas_uji[]" class="table-input vrla-input" min="0" step="0.01" placeholder="Kapasitas 1">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 3</span>
                                            <input type="number" name="kapasitas_uji[]" class="table-input vrla-input" min="0" step="0.01" placeholder="Kapasitas 3">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 2</span>
                                            <input type="number" name="kapasitas_uji[]" class="table-input vrla-input" min="0" step="0.01" placeholder="Kapasitas 2">
                                        </div>
                                        <div class="uji-sub-item">
                                            <span class="sub-label">Battery 4</span>
                                            <input type="number" name="kapasitas_uji[]" class="table-input vrla-input" min="0" step="0.01" placeholder="Kapasitas 4">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checklist-row">
                                <div class="checklist-label">Kapasitas Battery % <span class="required">*</span></div>
                                <div class="checklist-field">
                                    <input type="text" id="kapasitas_battery_persen" name="kapasitas_battery_persen" class="table-input auto-field" readonly tabindex="-1" placeholder="Dihitung otomatis oleh sistem">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label for="tanggal_uji_terakhir">Tanggal Uji Terakhir</label>
                                <input type="date" id="tanggal_uji_terakhir" name="tanggal_uji_terakhir" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="tanggal_penggantian">Tanggal Penggantian <span class="required">*</span></label>
                                <input type="date" id="tanggal_penggantian" name="tanggal_penggantian" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Status Uji Baterai</label>
                                <input type="hidden" name="status_uji" id="status_uji" value="">

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
                            <input type="text" id="keterangan_gambar" name="keterangan_gambar" class="form-control" placeholder="Masukkan keterangan gambar">
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
            const lithiumWrapper = document.getElementById('lithiumUjiWrapper');
            const vrlaWrapper = document.getElementById('vrlaUjiWrapper');
            const defaultUjiText = document.getElementById('defaultUjiText');
            
            const statusUji = document.getElementById('status_uji');
            const statusDisplay = document.getElementById('statusDisplay');
            const statusText = document.getElementById('statusText');
            const statusDot = document.getElementById('statusDot');

            jenisBattery.addEventListener('change', function () {
                if (this.value === 'Lithium') {
                    defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'block';
                    vrlaWrapper.style.display = 'none';
                } else if (this.value === 'VRLA') {
                    defaultUjiText.style.display = 'none';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'grid';
                } else {
                    defaultUjiText.style.display = 'block';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'none';
                }
            });

            function hitungPersentase(nilaiBattery, nilaiUji) {
                if (!nilaiBattery || nilaiBattery <= 0 || nilaiUji === '' || nilaiUji === null || isNaN(nilaiUji)) {
                    return null;
                }
                return (nilaiUji / nilaiBattery) * 100;
            }

            function updateStatus(persentase) {
                statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');

                if (persentase === null) {
                    statusText.textContent = '-';
                    statusUji.value = '';
                    return;
                }

                if (persentase >= 80) {
                    statusText.textContent = 'EXCELLENT';
                    statusUji.value = 'excellent';
                    statusDisplay.classList.add('status-excellent');
                    statusDot.classList.add('status-dot-excellent');
                } else if (persentase >= 60) {
                    statusText.textContent = 'GOOD';
                    statusUji.value = 'good';
                    statusDisplay.classList.add('status-good');
                    statusDot.classList.add('status-dot-good');
                } else if (persentase >= 40) {
                    statusText.textContent = 'WARNING';
                    statusUji.value = 'warning';
                    statusDisplay.classList.add('status-warning');
                    statusDot.classList.add('status-dot-warning');
                } else {
                    statusText.textContent = 'POOR';
                    statusUji.value = 'poor';
                    statusDisplay.classList.add('status-danger');
                    statusDot.classList.add('status-dot-danger');
                }
            }

            function updateBatteryCalculation() {
                const nilaiBattery = parseFloat(kapasitasBattery.value);
                let nilaiUjiPertama = null;

                if (jenisBattery.value === 'Lithium') {
                    const inputLithium = document.getElementById('kapasitas_uji_1');
                    nilaiUjiPertama = parseFloat(inputLithium.value);
                } else if (jenisBattery.value === 'VRLA') {
                    const inputVrlaPertama = document.querySelector('.vrla-input');
                    if (inputVrlaPertama) {
                        nilaiUjiPertama = parseFloat(inputVrlaPertama.value);
                    }
                }

                if (isNaN(nilaiBattery) || nilaiBattery <= 0 || isNaN(nilaiUjiPertama)) {
                    kapasitasPersen.value = '';
                    updateStatus(null);
                    return;
                }

                const persentase = hitungPersentase(nilaiBattery, nilaiUjiPertama);
                if (persentase === null) {
                    kapasitasPersen.value = '';
                } else {
                    kapasitasPersen.value = persentase.toFixed(2) + '%';
                }

                updateStatus(persentase);
            }

            kapasitasBattery.addEventListener('input', updateBatteryCalculation);
            document.getElementById('kapasitas_uji_1').addEventListener('input', updateBatteryCalculation);
            document.querySelectorAll('.vrla-input').forEach(el => {
                el.addEventListener('input', updateBatteryCalculation);
            });

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

            form.addEventListener('reset', function () {
                setTimeout(function () {
                    kapasitasPersen.value = '';
                    statusUji.value = '';
                    statusText.textContent = '-';
                    defaultUjiText.style.display = 'block';
                    lithiumWrapper.style.display = 'none';
                    vrlaWrapper.style.display = 'none';
                    previewImage.style.display = 'none';
                    noPreviewText.style.display = 'flex';
                    statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                    statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');
                }, 10);
            });
        });
    </script>
</body>
</html>