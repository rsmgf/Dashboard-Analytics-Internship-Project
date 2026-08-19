<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Role - PLN Icon Plus</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    @vite([
        'resources/css/sidebar.css',
        'resources/css/manajemen-role.css'
    ])
</head>
<body>

<div class="app-container">

   <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar" id="sidebar">
        <!-- LOGO -->
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus">
        </div>

        <!-- DASHBOARD -->
        <div class="sidebar-section">
            <div class="section-title">Dashboard</div>
            <a href="#" class="sidebar-menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- GENERAL & MANAJEMEN USER -->
        <div class="sidebar-section">
            <div class="section-title">General</div>
            
            <a href="#" class="sidebar-menu">
                <i class="bi bi-shield-fill"></i>
                <span>POP</span>
            </a>
            
            <a href="#" class="sidebar-menu">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Form RMA</span>
            </a>
            
            <a href="#" class="sidebar-menu active">
                <i class="bi bi-people-fill"></i>
                <span>Manajemen User</span>
            </a>
        </div>

        <!-- LOGOUT -->
        <div class="sidebar-section" style="margin-top: 20px;">
            <a href="#" class="sidebar-menu">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
            </a>
        </div>
    </aside>

    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <button type="button" class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- USER PROFILE -->
            <div class="user-profile">
                <div class="user-info">
                    <span class="user-name">
                        {{ Auth::check() ? Auth::user()->name : 'Aliba-ba' }}
                    </span>
                    <span class="user-email">
                        {{ Auth::check() ? Auth::user()->email : 'aliba-ba@gmail.com' }}
                    </span>
                </div>
                <div class="user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </header>

        <!-- =====================================================
             CONTENT
        ====================================================== -->
        <div class="role-page">
            
            <!-- TITLE -->
            <div class="role-title">
                <h1>Manajemen User</h1>
            </div>

            <!-- ALERT -->
            <div class="role-alert">
                <span>Status : Role shakila berhasil diubah ke karyawan</span>
            </div>

            <!-- SEARCH -->
            <div class="search-container">
                <div class="search-box">
                    <input type="text" id="searchUser" placeholder="Search User">
                    <i class="bi bi-search"></i>
                </div>
            </div>

            <!-- =====================================================
                 TABLE
            ====================================================== -->
            <div class="table-container">
                <table class="role-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role Saat Ini</th>
                            <th>Aksi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    
                    <tbody id="roleTableBody">
                        <!-- ROW 1 -->
                        <tr>
                            <td>1.</td>
                            <td>shakila</td>
                            <td>shakila@gmail.com</td>
                            <td class="current-role">Karyawan</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">
                                    Set Role
                                </button>
                            </td>
                            <td class="status">
                                <button class="btn-status active" onclick="toggleStatus(this)">
                                    <i class="bi bi-check-circle-fill"></i> Aktifkan
                                </button>
                            </td>
                        </tr>

                        <!-- ROW 2 -->
                        <tr>
                            <td>2.</td>
                            <td>shakila</td>
                            <td>shakila@gmail.com</td>
                            <td class="current-role">Teknisi</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">
                                    Set Role
                                </button>
                            </td>
                            <td class="status">
                                <button class="btn-status inactive" onclick="toggleStatus(this)">
                                    <i class="bi bi-x-circle-fill"></i> Non-Aktifkan
                                </button>
                            </td>
                        </tr>
                        
                        <!-- ROW 3 -->
                        <tr>
                            <td>3.</td>
                            <td>shakila</td>
                            <td>shakila@gmail.com</td>
                            <td class="current-role">Super Admin</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">
                                    Set Role
                                </button>
                            </td>
                            <td class="status">
                                <button class="btn-status active" onclick="toggleStatus(this)">
                                    <i class="bi bi-check-circle-fill"></i> Aktifkan
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- =====================================================
                 PAGINATION
            ====================================================== -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing 1 to 10 of 4,961 entries
                </div>
                
                <div class="pagination">
                    <button>Previous</button>
                    <button class="page active">1</button>
                    <button class="page">2</button>
                    <button class="page">3</button>
                    <span>...</span>
                    <button class="page">10</button>
                    <button>Next</button>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- =====================================================
     MODAL EDIT USER
====================================================== -->
<div class="modal-overlay" id="roleModal">
    <div class="role-modal">
        
        <!-- MODAL HEADER -->
        <div class="modal-header">
            <h2>Edit User</h2>
            <button type="button" class="modal-close" onclick="closeRoleModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
            <div class="form-group">
                <label>ID Karyawan</label>
                <input type="text" id="idKaryawan" class="form-control" placeholder="xxxxxx909">
            </div>

            <div class="form-group">
                <label>Nama Pegawai</label>
                <input type="text" id="namaPegawai" class="form-control" placeholder="shakila">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select id="roleSelect" class="form-control">
                    <option value="" disabled selected>Pilih</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="Teknisi">Teknisi</option>
                    <option value="Super Admin">Super Admin</option>
                </select>
            </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer">
            <button type="button" class="btn-save" onclick="saveRole()">
                Simpan Perubahan
            </button>
        </div>
        
    </div>
</div>

<!-- =====================================================
     JAVASCRIPT
====================================================== -->
<script>
let selectedRow = null;

/* =====================================================
   SIDEBAR
===================================================== */
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarIcon = sidebarToggle.querySelector('i');

sidebarToggle.addEventListener('click', function () {
    document.body.classList.toggle('sidebar-collapsed');
    if (document.body.classList.contains('sidebar-collapsed')) {
        sidebarIcon.classList.replace('bi-list', 'bi-chevron-right');
    } else {
        sidebarIcon.classList.replace('bi-chevron-right', 'bi-list');
    }
});

/* =====================================================
   SEARCH
===================================================== */
document.getElementById('searchUser').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('#roleTableBody tr');
    
    rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});

/* =====================================================
   OPEN MODAL
===================================================== */
function openRoleModal(button) {
    selectedRow = button.closest('tr');
    
    // Ambil data dari tabel
    const currentName = selectedRow.querySelectorAll('td')[1].textContent.trim();
    const currentRole = selectedRow.querySelector('.current-role').textContent.trim();

    // Isi data ke modal form
    document.getElementById('namaPegawai').value = currentName;
    document.getElementById('roleSelect').value = currentRole;
    document.getElementById('idKaryawan').value = "xxxxxx909"; // Mock data
    
    // Tampilkan Modal
    document.getElementById('roleModal').classList.add('show');
}

/* =====================================================
   CLOSE MODAL
===================================================== */
function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('show');
}

/* =====================================================
   SAVE ROLE & UPDATE TABEL (Frontend)
===================================================== */
function saveRole() {
    if (!selectedRow) return;

    const selectedRole = document.getElementById('roleSelect').value;
    const inputName = document.getElementById('namaPegawai').value;

    // Update nama & role di tabel
    selectedRow.querySelectorAll('td')[1].textContent = inputName;
    selectedRow.querySelector('.current-role').textContent = selectedRole;

    closeRoleModal();
}

/* =====================================================
   STATUS
===================================================== */
function toggleStatus(button) {
    if (button.classList.contains('active')) {
        button.classList.remove('active');
        button.classList.add('inactive');
        button.innerHTML = '<i class="bi bi-x-circle-fill"></i> Non-Aktifkan';
    } else {
        button.classList.remove('inactive');
        button.classList.add('active');
        button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Aktifkan';
    }
}

/* =====================================================
   CLOSE MODAL KETIKA KLIK LUAR
===================================================== */
document.getElementById('roleModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeRoleModal();
    }
});
</script>

</body>
</html>