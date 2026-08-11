<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form RMA - PLN Icon Plus</title>

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- GOOGLE FONT - POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS FRONTEND -->
    @vite([
        'resources/css/sidebar.css',
        'resources/css/rma.css'
    ])
</head>
<body>

    <div class="app-container">

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
            </div>

            <div class="sidebar-section">
                <div class="section-title">Dashboard</div>
                <a href="#" class="sidebar-menu">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="section-title">General</div>
                <a href="#" class="sidebar-menu">
                    <i class="bi bi-shield-fill"></i>
                    <span>POP</span>
                </a>
                <a href="#" class="sidebar-menu active">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Form RMA</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="section-title">Akun</div>
                <a href="#" class="sidebar-menu">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR -->
            <header class="topbar">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <!-- CONTENT LAYOUT -->
            <div class="rma-layout">

                <!-- FORM AREA -->
                <div class="rma-form-area">
                    
                    <div class="alert-box info-alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Isi Form berikut setiap melakukan pengembalian alat</span>
                        <button type="button" class="alert-close" aria-label="Tutup">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    <form id="rmaForm">
                        <!-- DATA MATERIAL -->
                        <div class="form-card">
                            <h2>Return Material Authorization (RMA)</h2>

                            <div class="form-group">
                                <label for="no_ios">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                                <input type="text" id="no_ios" name="no_ios" class="form-control" placeholder="Masukkan nomor dokumen">
                            </div>

                            <div class="form-group">
                                <label>Valuation Type <span>*</span></label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="valuation_type" value="Ex-Project">
                                        <span>Ex-Project</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="valuation_type" value="Dismantle">
                                        <span>Dismantle</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="valuation_type" value="Rusak-L">
                                        <span>Rusak-L</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="valuation_type" value="Rusak-TL">
                                        <span>Rusak-TL</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tanggal">Tanggal <span>*</span></label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="lokasi_asal">Lokasi asal <span>*</span></label>
                                <input type="text" id="lokasi_asal" name="lokasi_asal" class="form-control" placeholder="Masukkan lokasi asal">
                            </div>

                            <div class="form-group">
                                <label for="supervisor">Supervisor/Manager Name <span>*</span></label>
                                <input type="text" id="supervisor" name="supervisor" class="form-control" placeholder="Nama Supervisor atau Manager">
                            </div>

                            <div class="form-group">
                                <label for="customer_name">Customer Name (CPE)</label>
                                <div class="field-description">Beri tanda (-) jika tidak ada</div>
                                <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Nama Customer">
                            </div>

                            <div class="form-group">
                                <label for="merk">Merk <span>*</span></label>
                                <input type="text" id="merk" name="merk" class="form-control" placeholder="Merk perangkat">
                            </div>

                            <div class="form-group">
                                <label for="type">Type <span>*</span></label>
                                <input type="text" id="type" name="type" class="form-control" placeholder="Tipe perangkat">
                            </div>

                            <div class="form-group">
                                <label for="serial_number">Serial Number (SN) / Batch <span>*</span></label>
                                <input type="text" id="serial_number" name="serial_number" class="form-control" placeholder="Contoh: SN-123456789">
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span>*</span></label>
                                <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan kondisi secara singkat..."></textarea>
                            </div>
                        </div>

                        <!-- CHECKER -->
                        <div class="form-card">
                            <div class="alert-box warning-alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Beri tanda checklist pada kotak jika material rusak</span>
                                <button type="button" class="alert-close" aria-label="Tutup">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>

                            <div class="checker-grid">
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Dead on Arrival"> Dead on Arrival</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Physical Damage"> Physical Damage</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Dead on Operational"> Dead on Operational</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Miscellaneous"> Miscellaneous</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="BER Indication"> BER Indication</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Intermittent"> Intermittent</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Software Error"> Software Error</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Rectifier/Inverter Faulty"> Rectifier/Inverter Faulty</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Channel Error"> Channel Error</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Charging/Static Switch"> Charging/Static Switch</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Port Error"> Port Error</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Battery Faulty"> Battery Faulty</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Tx Laser Faulty"> Tx Laser Faulty</label>
                                <label class="checker-item"><input type="checkbox" name="damage[]" value="Rx Laser Faulty"> Rx Laser Faulty</label>
                            </div>

                            <div class="form-group" style="margin-top: 24px;">
                                <label for="reason">Alasan</label>
                                <div class="field-description">Optional</div>
                                <textarea id="reason" name="reason" class="form-control" placeholder="Tuliskan alasan tambahan bila ada..."></textarea>
                            </div>
                        </div>

                        <!-- SUBMIT -->
                        <div class="form-submit">
                            <button type="button" class="next-button" id="nextButton">Selanjutnya</button>
                        </div>
                    </form>
                </div>

                <!-- NOTE ASIDE -->
                <aside class="note-card">
                    <h3>Note</h3>
                    <div class="note-list">
                        <div class="note-item">
                            <span>Continue</span>
                            <p>Indikator error terjadi permanen terus menerus</p>
                        </div>
                        <div class="note-item">
                            <span>Intermittent</span>
                            <p>Indikator error terjadi kadang-kadang sangat random</p>
                        </div>
                        <div class="note-item">
                            <span>Dead on Arrival</span>
                            <p>Perangkat mati total rusak pada jangka waktu 24 jam setelah pemasangan</p>
                        </div>
                        <div class="note-item">
                            <span>Dead on Operational</span>
                            <p>Perangkat mati total/rusak pada jangka waktu 24 jam setelah pemasangan</p>
                        </div>
                        <div class="note-item">
                            <span>BER Indication</span>
                            <p>Indikator Error pada display modul/NMS/hasil disertakan no. thry error</p>
                        </div>
                        <div class="note-item">
                            <span>Software Error</span>
                            <p>Gangguan yang disebabkan firmware/OS/Interface/Protocol</p>
                        </div>
                        <div class="note-item">
                            <span>Tributary Error</span>
                            <p>Low Order Modul Error (PDH/SDH)</p>
                        </div>
                        <div class="note-item">
                            <span>Channel Error</span>
                            <p>64K channelize &lt;2Mb Fault (for FEVM, V.24, Voice Ch)</p>
                        </div>
                        <div class="note-item">
                            <span>Port Error</span>
                            <p>Port membangkitkan Error/mati total (IP Network Family, Converter)</p>
                        </div>
                        <div class="note-item">
                            <span>Laser Tx Faulty</span>
                            <p>Only Optical Module TX Loss, No Signal, High Temp, Laser Bias</p>
                        </div>
                        <div class="note-item">
                            <span>Laser Rx Faulty</span>
                            <p>Only Optical Module No.RX, Frame Error</p>
                        </div>
                        <div class="note-item">
                            <span>Physical Damage</span>
                            <p>Rusak fisik perangkat (Benturan, Short Circuit, Liquid)</p>
                        </div>
                        <div class="note-item">
                            <span>Miscellaneous</span>
                            <p>Sebab lain yang tidak tertulis diatas, mohon indikasi dijelaskan</p>
                        </div>
                    </div>
                </aside>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // SIDEBAR TOGGLE
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle.querySelector('i');

            sidebarToggle.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                // Mengubah icon menjadi 'hamburger' atau 'back' (opsional, tergantung preferensi)
                if (isCollapsed) {
                    sidebarIcon.classList.remove('bi-list');
                    sidebarIcon.classList.add('bi-chevron-right');
                } else {
                    sidebarIcon.classList.remove('bi-chevron-right');
                    sidebarIcon.classList.add('bi-list');
                }
            });

            // CLOSE ALERT
            const closeButtons = document.querySelectorAll('.alert-close');
            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const alert = button.closest('.alert-box');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 200); // transisi halus
                    }
                });
            });

            // NEXT BUTTON FRONTEND LOGIC
            const nextButton = document.getElementById('nextButton');
            nextButton.addEventListener('click', function () {
                console.log('Tombol Selanjutnya diklik');
                // Tambahkan sweetalert atau validasi JS disini ke depannya
            });

        });
    </script>
</body>
</html>