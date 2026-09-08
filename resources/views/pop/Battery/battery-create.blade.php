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

                <form action="#" method="POST" id="batteryForm">
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

                    <div class="form-card">
                        <h3 class="form-section-title">Checklist Baterai</h3>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="nomor_recti">Nomor Recti <span class="required">*</span></label>
                                <input type="text" id="nomor_recti" name="nomor_recti" class="form-control" placeholder="Masukkan nomor recti" required>
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_battery">Kapasitas Battery (AH) <span class="required">*</span></label>
                                <input type="number" id="kapasitas_battery" name="kapasitas_battery" class="form-control" min="0" step="0.01" placeholder="Masukkan kapasitas battery" required>
                            </div>

                            <div class="form-group">
                                <label for="nomor_bank">Nomor Bank <span class="required">*</span></label>
                                <input type="text" id="nomor_bank" name="nomor_bank" class="form-control" placeholder="Masukkan nomor bank" required>
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_uji">Kapasitas Uji (AH)</label>
                                <input type="number" id="kapasitas_uji" name="kapasitas_uji" class="form-control auto-field" readonly tabindex="-1">
                                <small class="auto-info">Diisi otomatis oleh sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="merk_battery">Merk Battery <span class="required">*</span></label>
                                <input type="text" id="merk_battery" name="merk_battery" class="form-control" placeholder="Masukkan merk battery" required>
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_battery_persen">Kapasitas Battery %</label>
                                <input type="text" id="kapasitas_battery_persen" name="kapasitas_battery_persen" class="form-control auto-field" readonly tabindex="-1">
                                <small class="auto-info">Dihitung otomatis oleh sistem</small>
                            </div>

                            <div class="form-group">
                                <label for="jenis_battery">Jenis Battery <span class="required">*</span></label>
                                <input type="text" id="jenis_battery" name="jenis_battery" class="form-control" placeholder="Masukkan jenis battery" required>
                            </div>

                            <div class="form-group">
                                <label for="backup_timer">Backup Timer (Hour) <span class="required">*</span></label>
                                <input type="number" id="backup_timer" name="backup_timer" class="form-control" min="0" step="0.01" placeholder="Masukkan backup timer" required>
                            </div>

                            <div class="form-group">
                                <label for="tipe_battery">Tipe Battery <span class="required">*</span></label>
                                <input type="text" id="tipe_battery" name="tipe_battery" class="form-control" placeholder="Masukkan tipe battery" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label for="tanggal_uji_terakhir">Tanggal Uji Terakhir <span class="required">*</span></label>
                                <input type="date" id="tanggal_uji_terakhir" name="tanggal_uji_terakhir" class="form-control" required>
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

                            <div class="form-group">
                                <label for="area_sti">Area STI <span class="required">*</span></label>
                                <input type="text" id="area_sti" name="area_sti" class="form-control" placeholder="Masukkan area STI" required>
                            </div>
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
            const kapasitasBattery = document.getElementById('kapasitas_battery');
            const kapasitasUji = document.getElementById('kapasitas_uji');
            const kapasitasPersen = document.getElementById('kapasitas_battery_persen');
            const statusUji = document.getElementById('status_uji');
            const statusDisplay = document.getElementById('statusDisplay');
            const statusText = document.getElementById('statusText');
            const statusDot = document.getElementById('statusDot');

            function hitungKapasitasUji(nilaiBattery) {
                if (!nilaiBattery || nilaiBattery <= 0) {
                    return '';
                }
                const hasil = nilaiBattery;
                return hasil.toFixed(2);
            }

            function hitungPersentase(nilaiBattery, nilaiUji) {
                if (!nilaiBattery || nilaiBattery <= 0 || nilaiUji === '' || nilaiUji === null || isNaN(nilaiUji)) {
                    return null;
                }
                const hasil = (nilaiUji / nilaiBattery) * 100;
                return hasil;
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

                if (isNaN(nilaiBattery) || nilaiBattery <= 0) {
                    kapasitasUji.value = '';
                    kapasitasPersen.value = '';
                    updateStatus(null);
                    return;
                }

                const hasilUji = hitungKapasitasUji(nilaiBattery);
                kapasitasUji.value = hasilUji;

                const nilaiUji = parseFloat(hasilUji);
                const persentase = hitungPersentase(nilaiBattery, nilaiUji);

                if (persentase === null) {
                    kapasitasPersen.value = '';
                } else {
                    kapasitasPersen.value = persentase.toFixed(2) + '%';
                }

                updateStatus(persentase);
            }

            kapasitasBattery.addEventListener('input', updateBatteryCalculation);

            form.addEventListener('reset', function () {
                setTimeout(function () {
                    kapasitasUji.value = '';
                    kapasitasPersen.value = '';
                    statusUji.value = '';
                    statusText.textContent = '-';

                    statusDisplay.classList.remove('status-excellent', 'status-good', 'status-warning', 'status-danger');
                    statusDot.classList.remove('status-dot-excellent', 'status-dot-good', 'status-dot-warning', 'status-dot-danger');
                }, 10);
            });

            form.addEventListener('submit', function (event) {
                updateBatteryCalculation();

                console.log('Data form:', {
                    pop: document.getElementById('pop').value,
                    building: document.getElementById('building').value,
                    pic: document.getElementById('pic').value,
                    type_pop: document.getElementById('type_pop').value,
                    recti: document.getElementById('recti').value,
                    nomor_recti: document.getElementById('nomor_recti').value,
                    kapasitas_battery: document.getElementById('kapasitas_battery').value,
                    kapasitas_uji: document.getElementById('kapasitas_uji').value,
                    nomor_bank: document.getElementById('nomor_bank').value,
                    merk_battery: document.getElementById('merk_battery').value,
                    kapasitas_battery_persen: document.getElementById('kapasitas_battery_persen').value,
                    jenis_battery: document.getElementById('jenis_battery').value,
                    backup_timer: document.getElementById('backup_timer').value,
                    tipe_battery: document.getElementById('tipe_battery').value,
                    tanggal_uji_terakhir: document.getElementById('tanggal_uji_terakhir').value,
                    tanggal_penggantian: document.getElementById('tanggal_penggantian').value,
                    status_uji: document.getElementById('status_uji').value,
                    area_sti: document.getElementById('area_sti').value
                });
            });
        });
    </script>
</body>
</html>