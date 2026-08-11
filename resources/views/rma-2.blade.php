<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Bukti - Form RMA</title>

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

            <!-- TOPBAR (Diperbarui dengan Back Button & Toggle Button berdampingan) -->
            <header class="topbar" style="justify-content: flex-start; gap: 12px;">
                <!-- Toggle Sidebar -->
                <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                    <i class="bi bi-list"></i>
                </button>
    
            </header>

            <!-- CONTENT LAYOUT -->
            <div class="rma-layout">

                <!-- FORM AREA -->
                <div class="rma-form-area">
                    
                    <!-- ALERT BOX -->
                    <div class="alert-box info-alert" style="background: #f8fafc; border: 1px solid #c7d2fe; color: #4f46e5;">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Isi halaman ini sebagai bukti pengembalian</span>
                        <button type="button" class="alert-close" aria-label="Tutup">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    <form id="rmaUploadForm">
                        <div class="form-card" style="padding-bottom: 24px;">
                            
                            <!-- UPLOAD HEADER -->
                            <div class="upload-header">
                                <div class="upload-title-area">
                                    <label class="form-group" style="margin-bottom: 0;">Material SFP <span>*</span></label>
                                    <div class="field-description">Maksimal 10 Mb</div>
                                </div>
                                <a href="#" class="tambah-link">
                                    Tambah SFP <i class="bi bi-plus-circle"></i>
                                </a>
                            </div>

                            <!-- UPLOAD DROPZONE -->
                            <div class="upload-dropzone" id="dropzone">
                                <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                                <div class="dropzone-text">Masukkan file disini</div>
                                <input type="file" id="fileInput" name="fileInput" accept="image/*, .pdf" style="display: none;">
                                <button type="button" class="btn-browse" onclick="document.getElementById('fileInput').click()">Browse</button>
                            </div>

                            <!-- ENGINEER SIGN -->
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="engineer_sign">Engineer Sign <span>*</span></label>
                                <select id="engineer_sign" name="engineer_sign" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Pilih Engineer</option>
                                    <option value="engineer_1">Engineer 1</option>
                                    <option value="engineer_2">Engineer 2</option>
                                </select>
                            </div>

                            <!-- FORM ACTIONS -->
                            <div class="form-actions">
                                <button type="button" class="btn-preview">Preview</button>
                                <button type="submit" class="btn-simpan">Simpan</button>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- NOTE ASIDE -->
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
            
            // ==========================================
            // 1. SIDEBAR TOGGLE (Sudah diperbaiki & ditambahkan)
            // ==========================================
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle.querySelector('i');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                    if (isCollapsed) {
                        sidebarIcon.classList.remove('bi-list');
                        sidebarIcon.classList.add('bi-chevron-right');
                    } else {
                        sidebarIcon.classList.remove('bi-chevron-right');
                        sidebarIcon.classList.add('bi-list');
                    }
                });
            }

            // ==========================================
            // 2. CLOSE ALERT
            // ==========================================
            const closeButtons = document.querySelectorAll('.alert-close');
            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const alert = button.closest('.alert-box');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 200);
                    }
                });
            });

            // ==========================================
            // 3. DROPZONE INTERACTION (Frontend Only)
            // ==========================================
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');

            if (dropzone && fileInput) {
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                });

                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                    
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        console.log('File dropped:', fileInput.files[0].name);
                    }
                });

                fileInput.addEventListener('change', (e) => {
                    if(e.target.files.length) {
                        console.log('File selected:', e.target.files[0].name);
                    }
                });
            }
        });
    </script>
</body>
</html>