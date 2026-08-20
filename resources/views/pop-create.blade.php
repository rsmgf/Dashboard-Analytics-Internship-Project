<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit POP - PLN Icon Plus</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite([
        'resources/css/sidebar.css',
        'resources/css/add-pop.css'
    ])
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
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
            <a href="#" class="sidebar-menu active">
                <i class="bi bi-shield-fill"></i>
                <span>POP</span>
            </a>
            <a href="#" class="sidebar-menu">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Form RMA</span>
            </a>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-menu sidebar-dropdown" id="accountConfigToggle">
                <i class="bi bi-gear-fill"></i>
                <span>Konfigurasi Akun</span>
                <i class="bi bi-chevron-down dropdown-arrow" id="accountConfigArrow"></i>
            </div>
            <div class="sidebar-submenu" id="accountConfigMenu">
                <a href="#" class="sidebar-submenu-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Manajemen Menu</span>
                </a>
                <a href="#" class="sidebar-submenu-item">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen User</span>
                </a>
            </div>
            <a href="#" class="sidebar-menu">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Buka / Tutup Sidebar">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="user-profile">
                <span>Super Admin</span>
                <div class="profile-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </header>

        <div class="add-pop-content">
            <div class="add-pop-card">
                <div class="add-pop-alert">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Perbarui informasi data POP pada form di bawah ini.</span>
                </div>
                
                <div class="add-pop-title">
                    <h1>Edit Point of Presence</h1>
                    <p>Ubah Data POP</p>
                </div>
                
                <form id="editPopForm">
                    <input type="hidden" id="pop_id" value="{{ $id }}">
                    
                    <div class="add-pop-group">
                        <label for="provinsi">Provinsi <span class="add-pop-required">*</span></label>
                        <input type="text" id="provinsi" name="provinsi" class="add-pop-input" value="Jambi" required>
                    </div>
                    
                    <div class="add-pop-group">
                        <label for="kota">Kota/Kabupaten <span class="add-pop-required">*</span></label>
                        <input type="text" id="kota" name="kota" class="add-pop-input" value="Kota Jambi" required>
                    </div>
                    
                    <div class="add-pop-group">
                        <label for="id_pop">ID POP <span class="add-pop-required">*</span></label>
                        <input type="text" id="id_pop" name="id_pop" class="add-pop-input" value="POP-{{ $id }}" required>
                    </div>
                    
                    <div class="add-pop-group">
                        <label for="nama_pop">Nama POP <span class="add-pop-required">*</span></label>
                        <input type="text" id="nama_pop" name="nama_pop" class="add-pop-input" value="POP Jambi Kota" required>
                    </div>
                    
                    <div class="add-pop-group">
                        <label for="building">Building <span class="add-pop-required">*</span></label>
                        <div class="add-pop-select-wrapper">
                            <select id="building" name="building" class="add-pop-select" required>
                                <option value="" disabled>Pilih Building</option>
                                <option value="Shelter Permanent" selected>Shelter Permanent</option>
                                <option value="Shelter Temporary">Shelter Temporary</option>
                                <option value="Building">Building</option>
                                <option value="Outdoor">Outdoor</option>
                            </select>
                            <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="add-pop-group">
                        <label for="type_pop">Type POP <span class="add-pop-required">*</span></label>
                        <div class="add-pop-select-wrapper">
                            <select id="type_pop" name="type_pop" class="add-pop-select" required>
                                <option value="" disabled>Pilih Type POP</option>
                                <option value="POP-SB" selected>POP-SB</option>
                                <option value="POP-DC">POP-DC</option>
                                <option value="POP-ODC">POP-ODC</option>
                                <option value="POP-FO">POP-FO</option>
                            </select>
                            <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="add-pop-actions">
                        <a href="/pops" class="add-pop-btn-cancel">Batal</a>
                        <button type="submit" class="add-pop-btn-save">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = sidebarToggle?.querySelector('i');

            sidebarToggle?.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-collapsed');
                
                const collapsed = document.body.classList.contains('sidebar-collapsed');

                if (collapsed) {
                    sidebarIcon?.classList.replace('bi-list', 'bi-chevron-right');
                    document.getElementById('accountConfigMenu')?.classList.remove('show');
                    document.getElementById('accountConfigToggle')?.classList.remove('active');
                    document.getElementById('accountConfigArrow')?.classList.replace('bi-chevron-up', 'bi-chevron-down');
                } else {
                    sidebarIcon?.classList.replace('bi-chevron-right', 'bi-list');
                }
            });

            const accountToggle = document.getElementById('accountConfigToggle');
            const accountMenu = document.getElementById('accountConfigMenu');
            const accountArrow = document.getElementById('accountConfigArrow');

            accountToggle?.addEventListener('click', function () {
                const open = accountMenu.classList.toggle('show');
                accountToggle.classList.toggle('active', open);

                if (open) {
                    accountArrow.classList.replace('bi-chevron-down', 'bi-chevron-up');
                } else {
                    accountArrow.classList.replace('bi-chevron-up', 'bi-chevron-down');
                }
            });

            const form = document.getElementById('editPopForm');

            form?.addEventListener('submit', function (event) {
                event.preventDefault(); 

                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: "Pastikan data POP sudah sesuai sebelum disimpan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'swal-popup-custom',
                        title: 'swal-title-custom',
                        htmlContainer: 'swal-html-custom',
                        confirmButton: 'swal-btn-confirm',
                        cancelButton: 'swal-btn-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data POP berhasil diperbarui.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal-popup-custom',
                                title: 'swal-title-custom',
                                htmlContainer: 'swal-html-custom'
                            }
                        }).then(() => {
                            window.location.href = '/pops'; 
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>