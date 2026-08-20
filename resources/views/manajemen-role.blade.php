<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/css/sidebar.css',
        'resources/css/manajemen-role.css'
    ])
    <style>
        .role-table th:nth-child(6),
        .role-table th:nth-child(7),
        .role-table td:nth-child(6),
        .role-table td:nth-child(7) {
            text-align: center;
        }
        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: color 0.2s ease;
        }
        .sortable-header:hover {
            color: #007BFF;
        }
        .sortable-header i {
            font-size: 11px;
            margin-left: 4px;
            color: #94A3B8;
            transition: .2s ease;
        }
        .sortable-header.active-asc i {
            color: #007BFF;
        }
        .sortable-header.active-desc i {
            color: #007BFF;
        }
    </style>
</head>
<body>
<div class="app-container">
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
                <a href="#" class="sidebar-submenu-item active">
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
            <button type="button" class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
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

        <div class="role-page">
            <div class="role-title">
                <h1>Manajemen User</h1>
            </div>
            <div class="role-alert">
                <span id="roleAlert">Status : Sistem Manajemen User berjalan dengan baik</span>
            </div>
            <div class="search-container" style="display: flex; gap: 15px; align-items: center;">
                <div class="filter-box">
                    <select id="filterStatus" style="padding: 10px 15px; border-radius: 8px; border: 1px solid #E2E8F0; outline: none; font-family: 'Poppins', sans-serif; color: #64748B; cursor: pointer;">
                        <option value="all">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Non-Aktif</option>
                    </select>
                </div>
                <div class="search-box">
                    <input type="text" id="searchUser" placeholder="Search User">
                    <i class="bi bi-search"></i>
                </div>
            </div>

            <div class="table-container">
                <table class="role-table" id="roleTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th class="sortable-header" onclick="sortTable(1, this)">
                                Nama <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(2, this)">
                                Email <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(3, this)">
                                Tanggal Registrasi <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(4, this)">
                                Role Saat Ini <i class="bi bi-arrow-down-up"></i>
                            </th>
                            <th>Aksi</th>
                            <th class="sortable-header" onclick="sortTable(6, this)">
                                Status <i class="bi bi-arrow-down-up"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="roleTableBody">
                        <tr>
                            <td>1.</td>
                            <td>shakila</td>
                            <td>shakila@gmail.com</td>
                            <td>20 Agustus 2026</td>
                            <td class="current-role">Karyawan</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">Set Role</button>
                            </td>
                            <td class="status">
                                <button class="btn-status active" onclick="toggleStatus(this)">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Budi Santoso</td>
                            <td>budi@gmail.com</td>
                            <td>19 Agustus 2026</td>
                            <td class="current-role">Teknisi</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">Set Role</button>
                            </td>
                            <td class="status">
                                <button class="btn-status active" onclick="toggleStatus(this)">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Rina Susanti</td>
                            <td>rina@gmail.com</td>
                            <td>18 Agustus 2026</td>
                            <td class="current-role">Super Admin</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">Set Role</button>
                            </td>
                            <td class="status">
                                <button class="btn-status active" onclick="toggleStatus(this)">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </button>
                            </td>
                        </tr>
                        <tr data-old-role="Teknisi">
                            <td>4.</td>
                            <td>User Baru</td>
                            <td>baru.regis@gmail.com</td>
                            <td>21 Agustus 2026</td>
                            <td class="current-role">-</td>
                            <td class="action">
                                <button class="btn-set-role" onclick="openRoleModal(this)">Set Role</button>
                            </td>
                            <td class="status">
                                <button class="btn-status inactive" onclick="toggleStatus(this)">
                                    <i class="bi bi-x-circle-fill"></i> Non-Aktif
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">Showing 1 to 4 of 4 entries</div>
                <div class="pagination">
                    <button>Previous</button>
                    <button class="page active">1</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal-overlay" id="roleModal">
    <div class="role-modal">
        <div class="modal-header">
            <h2>Edit User</h2>
            <button type="button" class="modal-close" onclick="closeRoleModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>ID Karyawan</label>
                <input type="text" id="idKaryawan" class="form-control" placeholder="xxxxxx909">
            </div>
            <div class="form-group">
                <label>Nama Pegawai</label>
                <input type="text" id="namaPegawai" class="form-control" placeholder="shakila" readonly>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select id="roleSelect" class="form-control">
                    <option value="" disabled>Pilih</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="Teknisi">Teknisi</option>
                    <option value="Super Admin">Super Admin</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-save" onclick="saveRole()">Simpan Perubahan</button>
        </div>
    </div>
</div>

<script>
let selectedRow = null;

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

const accountConfigToggle = document.getElementById('accountConfigToggle');
const accountConfigMenu = document.getElementById('accountConfigMenu');

accountConfigToggle.addEventListener('click', function () {
    accountConfigMenu.classList.toggle('show');
    accountConfigToggle.classList.toggle('open');
});

document.addEventListener("DOMContentLoaded", function() {
    const activeSubmenuItem = accountConfigMenu.querySelector('.sidebar-submenu-item.active');
    if (activeSubmenuItem) {
        accountConfigMenu.classList.add('show');
        accountConfigToggle.classList.add('open');
    }
});

const searchInput = document.getElementById('searchUser');
const filterStatus = document.getElementById('filterStatus');

function filterTable() {
    const keyword = searchInput.value.toLowerCase();
    const statusValue = filterStatus.value;
    const rows = document.querySelectorAll('#roleTableBody tr');
    
    let visibleCount = 1;

    rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();
        const statusBtn = row.querySelector('.status .btn-status');
        const isRowActive = statusBtn.classList.contains('active');
        
        let matchStatus = true;
        if (statusValue === 'active' && !isRowActive) matchStatus = false;
        if (statusValue === 'inactive' && isRowActive) matchStatus = false;

        let matchKeyword = text.includes(keyword);

        if (matchKeyword && matchStatus) {
            row.style.display = '';
            row.cells[0].textContent = visibleCount + ".";
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('keyup', filterTable);
filterStatus.addEventListener('change', filterTable);

function sortTable(columnIndex, headerElement) {
    const tableBody = document.getElementById("roleTableBody");
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    let isAscending = headerElement.dataset.order === "asc";
    headerElement.dataset.order = isAscending ? "desc" : "asc";

    document.querySelectorAll('.sortable-header').forEach(th => {
        th.classList.remove('active-asc', 'active-desc');
        th.querySelector('i').className = 'bi bi-arrow-down-up';
    });

    const icon = headerElement.querySelector('i');
    if (isAscending) {
        headerElement.classList.add('active-desc');
        icon.className = 'bi bi-arrow-down';
    } else {
        headerElement.classList.add('active-asc');
        icon.className = 'bi bi-arrow-up';
    }

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    function parseIdDate(dateStr) {
        const parts = dateStr.trim().split(" ");
        if(parts.length === 3) {
            const day = parts[0].padStart(2, '0');
            const month = String(monthNames.indexOf(parts[1]) + 1).padStart(2, '0');
            const year = parts[2];
            return `${year}${month}${day}`;
        }
        return dateStr;
    }

    rows.sort((rowA, rowB) => {
        let valA = rowA.cells[columnIndex].textContent.trim().toLowerCase();
        let valB = rowB.cells[columnIndex].textContent.trim().toLowerCase();

        if (columnIndex === 3) {
            valA = parseIdDate(valA);
            valB = parseIdDate(valB);
        }

        if (valA < valB) return isAscending ? -1 : 1;
        if (valA > valB) return isAscending ? 1 : -1;
        return 0;
    });

    rows.forEach((row, index) => {
        tableBody.appendChild(row);
    });
    
    filterTable(); 
}

function openRoleModal(button) {
    selectedRow = button.closest('tr');
    const currentName = selectedRow.querySelectorAll('td')[1].textContent.trim();
    let currentRole = selectedRow.querySelector('.current-role').textContent.trim();
    
    if (currentRole === '-') {
        currentRole = selectedRow.getAttribute('data-old-role') || "";
    }

    document.getElementById('namaPegawai').value = currentName;
    document.getElementById('roleSelect').value = currentRole;
    document.getElementById('idKaryawan').value = 'xxxxxx909'; 
    document.getElementById('roleModal').classList.add('show');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('show');
}

function saveRole() {
    if (!selectedRow) return;

    const selectedRole = document.getElementById('roleSelect').value;
    const inputName = document.getElementById('namaPegawai').value.trim();
    const currentRole = selectedRow.querySelector('.current-role').textContent.trim();
    
    const statusBtn = selectedRow.querySelector('.status .btn-status');
    const isRowActive = statusBtn.classList.contains('active');

    if (!selectedRole) {
        Swal.fire({ icon: 'warning', title: 'Role Belum Dipilih', text: 'Silakan pilih role terlebih dahulu.', confirmButtonColor: '#0066DC' });
        return;
    }

    Swal.fire({
        icon: 'question',
        title: 'Yakin Ingin Mengganti Role?',
        html: `Role user <strong>${inputName}</strong> akan diubah menjadi <strong>${selectedRole}</strong>.`,
        showCancelButton: true,
        confirmButtonText: 'Ya, Ganti Role',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0066DC',
        cancelButtonColor: '#64748B',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (!isRowActive) {
                selectedRow.setAttribute('data-old-role', selectedRole);
            } else {
                selectedRow.querySelector('.current-role').textContent = selectedRole;
            }
            
            closeRoleModal();
            Swal.fire({ icon: 'success', title: 'Role Berhasil Diubah', text: `Role ${inputName} berhasil diperbarui.`, confirmButtonColor: '#0066DC', timer: 2500 });
        }
    });
}

function toggleStatus(button) {
    const row = button.closest('tr');
    const name = row.querySelectorAll('td')[1].textContent.trim();
    const roleCell = row.querySelector('.current-role');
    const currentRole = roleCell.textContent.trim();

    if (button.classList.contains('active')) {
        Swal.fire({
            icon: 'warning', title: 'Cabut Akses User?',
            html: `Yakin ingin menonaktifkan user <strong>${name}</strong>?`,
            showCancelButton: true, confirmButtonText: 'Ya, Non-Aktifkan', cancelButtonText: 'Batal', confirmButtonColor: '#dc3545', cancelButtonColor: '#64748B', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (currentRole !== '-') {
                    row.setAttribute('data-old-role', currentRole);
                }

                button.classList.replace('active', 'inactive');
                button.innerHTML = `<i class="bi bi-x-circle-fill"></i> Non-Aktif`;
                
                roleCell.textContent = '-';

                Swal.fire({ icon: 'success', title: 'Akses Dicabut', confirmButtonColor: '#0066DC', timer: 2000 });
                filterTable();
            }
        });
    } else {
        Swal.fire({
            icon: 'question', title: 'Aktifkan / Approve User?',
            html: `Berikan akses login untuk <strong>${name}</strong>?`,
            showCancelButton: true, confirmButtonText: 'Ya, Aktifkan', cancelButtonText: 'Batal', confirmButtonColor: '#0066DC', cancelButtonColor: '#64748B', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.classList.replace('inactive', 'active');
                button.innerHTML = `<i class="bi bi-check-circle-fill"></i> Aktif`;
                
                const oldRole = row.getAttribute('data-old-role');
                if (oldRole) {
                    roleCell.textContent = oldRole;
                }

                Swal.fire({ icon: 'success', title: 'User Diaktifkan', confirmButtonColor: '#0066DC', timer: 2000 });
                filterTable();
            }
        });
    }
}

document.getElementById('roleModal').addEventListener('click', function (event) {
    if (event.target === this) { closeRoleModal(); }
});
</script>
</body>
</html>