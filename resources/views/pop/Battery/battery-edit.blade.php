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

                <form action="#" method="POST">
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
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Nomor Recti / Bank <span class="required">*</span></label>
                                <input type="text" class="form-control" name="nomor_recti_bank" value="POP_1SRG012_BANK01" required>
                            </div>

                            <div class="form-group">
                                <label>Kapasitas Battery (AH) <span class="required">*</span></label>
                                <input type="number" step="0.01" min="0" id="kapasitas_battery" name="kapasitas_battery" class="form-control" value="100" required>
                            </div>

                            <div class="form-group">
                                <label>Kapasitas Uji (AH)</label>
                                <input type="number" step="0.01" id="kapasitas_uji" name="kapasitas_uji" class="form-control auto-field" value="100.00" readonly>
                                <small class="auto-info">Terisi otomatis berdasarkan hasil pengujian.</small>
                            </div>

                            <div class="form-group">
                                <label>Merk Battery <span class="required">*</span></label>
                                <input type="text" class="form-control" name="merk_battery" value="SACRED SUN" required>
                            </div>

                            <div class="form-group">
                                <label>Kapasitas Battery (%)</label>
                                <input type="number" step="0.01" id="kapasitas_persen" name="kapasitas_persen" class="form-control auto-field" value="100.00" readonly>
                                <small class="auto-info">Dihitung otomatis dari Kapasitas Uji.</small>
                            </div>

                            <div class="form-group">
                                <label>Jenis Battery <span class="required">*</span></label>
                                <input type="text" class="form-control" name="jenis_battery" value="LITHIUM" required>
                            </div>

                            <div class="form-group">
                                <label>Backup Timer (Hour) <span class="required">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" name="backup_timer" value="6.54" required>
                            </div>

                            <div class="form-group">
                                <label>Tipe Battery <span class="required">*</span></label>
                                <input type="text" class="form-control" name="tipe_battery" value="SSIFP48100B" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <h3 class="form-section-title">Uji Baterai</h3>
                        <div class="form-grid-1">
                            <div class="form-group">
                                <label>Tanggal Uji Terakhir <span class="required">*</span></label>
                                <input type="date" class="form-control" name="tanggal_uji_terakhir" value="2025-10-31" required>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Penggantian <span class="required">*</span></label>
                                <input type="date" class="form-control" name="tanggal_penggantian" value="2020-05-05" required>
                            </div>

                            <div class="form-group">
                                <label>Status Uji Baterai</label>
                                <div id="statusUjiDisplay" class="status-readonly-box status-good">
                                    <span class="status-dot"></span>
                                    <span id="statusUjiText">GOOD</span>
                                </div>
                                <input type="hidden" id="status_uji" name="status_uji" value="good">
                                <small class="auto-info">Status ditentukan otomatis berdasarkan kapasitas baterai.</small>
                            </div>

                            <div class="form-group">
                                <label>Area STI <span class="required">*</span></label>
                                <input type="text" class="form-control" name="area_sti" value="Baten 1" required>
                            </div>
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
            const kapasitasBattery = document.getElementById('kapasitas_battery');
            const kapasitasUji = document.getElementById('kapasitas_uji');
            const kapasitasPersen = document.getElementById('kapasitas_persen');
            const statusDisplay = document.getElementById('statusUjiDisplay');
            const statusText = document.getElementById('statusUjiText');
            const statusInput = document.getElementById('status_uji');
            const resetButton = document.getElementById('resetButton');

            function updateStatus(persen) {
                statusDisplay.className = 'status-readonly-box';

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
                statusText.textContent = label;
                statusInput.value = status;
            }

            function updateBatteryCalculation() {
                const battery = parseFloat(kapasitasBattery.value) || 0;
                const uji = parseFloat(kapasitasUji.value) || 0;

                if (battery > 0 && uji >= 0) {
                    const persen = (uji / battery) * 100;
                    kapasitasPersen.value = persen.toFixed(2);
                    updateStatus(persen);
                } else {
                    kapasitasPersen.value = '0.00';
                    updateStatus(0);
                }
            }

            kapasitasBattery.addEventListener('input', function () {
                updateBatteryCalculation();
            });

            updateBatteryCalculation();

            resetButton.addEventListener('click', function () {
                setTimeout(function () {
                    updateBatteryCalculation();
                }, 50);
            });
        });
    </script>
</body>
</html>