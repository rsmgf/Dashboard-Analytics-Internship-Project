<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel POP - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/add-pop.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        /* ---- Import Dropzone ---- */
        .import-dropzone {
            border: 2px dashed #93c5fd;
            background: #f0f7ff;
            border-radius: 12px;
            padding: 40px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .import-dropzone:hover,
        .import-dropzone.dragover {
            background: #e6f0fd;
            border-color: #2563eb;
        }

        .import-dropzone i.drop-icon {
            font-size: 2.8rem;
            color: #2563eb;
            display: block;
            margin-bottom: 10px;
            transition: transform 0.25s ease;
        }

        .import-dropzone:hover i.drop-icon {
            transform: translateY(-4px);
        }

        .import-dropzone .drop-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .import-dropzone .drop-sub {
            font-size: 0.78rem;
            color: #64748b;
            margin-bottom: 16px;
        }

        .btn-pilih-file {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 22px;
            background: linear-gradient(135deg, #0086FF, #005BD4);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 3px 8px rgba(0, 91, 212, 0.22);
        }

        .btn-pilih-file:hover {
            background: linear-gradient(135deg, #0078E8, #0052C2);
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(0, 91, 212, 0.32);
        }

        /* ---- File Preview ---- */
        .file-selected-box {
            display: none;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            padding: 12px 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #15803d;
            font-weight: 500;
        }

        .file-selected-box i {
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .file-selected-name {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-clear-file {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1rem;
            padding: 2px 5px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .btn-clear-file:hover {
            color: #dc2626;
        }

        /* ---- Template Download ---- */
        .template-info-box {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            margin-bottom: 24px;
        }

        .template-info-box i {
            font-size: 1.6rem;
            color: #d97706;
            flex-shrink: 0;
        }

        .template-info-box .ti-text {
            flex: 1;
        }

        .template-info-box .ti-text strong {
            display: block;
            font-size: 0.875rem;
            color: #92400e;
            margin-bottom: 2px;
        }

        .template-info-box .ti-text span {
            font-size: 0.775rem;
            color: #b45309;
        }

        .btn-download-template {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #fff;
            border: 1.5px solid #f59e0b;
            color: #d97706;
            border-radius: 7px;
            font-size: 0.82rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .btn-download-template:hover {
            background: #fef3c7;
            color: #b45309;
        }

        /* ---- Kolom Info Table ---- */
        .col-info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-top: 6px;
        }

        .col-info-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .col-info-table td {
            padding: 7px 12px;
            border: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: top;
        }

        .col-info-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .badge-required {
            background: #fee2e2;
            color: #dc2626;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-optional {
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }

        /* ---- Result Alert ---- */
        .result-box {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }

        .result-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .result-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .result-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
        }

        .result-box ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        /* ============ Helper tampilan ============ */
        .only-mobile {
            display: none;
        }

        .col-info-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .col-info-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
        }

        .import-form {
            margin-top: 28px;
        }

        .import-note {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 8px;
        }

        .import-actions {
            margin-top: 24px;
        }

        .result-text {
            margin: 4px 0 0;
        }

        .result-box,
        .result-box li {
            overflow-wrap: anywhere;
        }

        .col-info-table code {
            background: #eef2f7;
            color: #0f172a;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.78rem;
            white-space: nowrap;
        }

        /* ============ TABLET (≤ 1024px) ============ */
        @media (max-width: 1024px) {
            .import-dropzone {
                padding: 32px 20px;
            }

            .col-info-table {
                min-width: 560px;
                font-size: 0.78rem;
            }

            .col-info-table th,
            .col-info-table td {
                padding: 7px 10px;
            }
        }

        /* ============ MOBILE (≤ 768px) ============ */
        @media (max-width: 768px) {

            /* --- Header + breadcrumb --- */
            .add-pop-header-bar {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: nowrap;
            }

            .add-pop-back {
                flex-shrink: 0;
                width: 34px;
                height: 34px;
                font-size: 1rem;
            }

            .add-pop-header-text {
                min-width: 0;
                /* wajib agar teks bisa menyusut */
                flex: 1 1 auto;
            }

            .breadcrumb-nav {
                gap: 5px;
            }

            .breadcrumb-link {
                font-size: 0.78rem;
            }

            .breadcrumb-home i {
                font-size: 0.85rem;
            }

            .breadcrumb-sep {
                font-size: 0.55rem;
            }

            .breadcrumb-current {
                font-size: 0.95rem;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .add-pop-subheading {
                font-size: 0.75rem !important;
                line-height: 1.4;
                margin-top: 2px;
            }

            /* --- Card --- */
            .add-pop-card {
                padding: 16px 14px !important;
            }

            /* --- Kotak template: susun vertikal --- */
            .template-info-box {
                flex-wrap: wrap;
                gap: 10px;
                padding: 12px 14px;
                margin-bottom: 18px;
            }

            .template-info-box i {
                font-size: 1.3rem;
            }

            .template-info-box .ti-text {
                flex: 1 1 calc(100% - 44px);
                min-width: 0;
            }

            .template-info-box .ti-text strong {
                font-size: 0.8rem;
            }

            .template-info-box .ti-text span {
                font-size: 0.72rem;
                line-height: 1.45;
                display: block;
            }

            .btn-download-template {
                width: 100%;
                justify-content: center;
                padding: 9px 14px;
                font-size: 0.78rem;
            }

            /* --- Dropzone --- */
            .only-desktop {
                display: none;
            }

            .only-mobile {
                display: inline;
            }

            .import-form {
                margin-top: 20px;
            }

            .import-dropzone {
                padding: 24px 14px;
                border-radius: 10px;
            }

            .import-dropzone i.drop-icon {
                font-size: 2.1rem;
                margin-bottom: 6px;
            }

            .import-dropzone .drop-title {
                font-size: 0.88rem;
            }

            .import-dropzone .drop-sub {
                font-size: 0.72rem;
                margin-bottom: 12px;
            }

            .btn-pilih-file {
                width: 100%;
                justify-content: center;
                padding: 10px 16px;
                font-size: 0.8rem;
            }

            /* --- Preview file --- */
            .file-selected-box {
                padding: 10px 12px;
                gap: 10px;
                font-size: 0.78rem;
            }

            .file-selected-box i {
                font-size: 1.15rem;
            }

            .file-selected-name {
                min-width: 0;
            }

            .btn-clear-file {
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .import-note {
                font-size: 0.7rem;
                line-height: 1.5;
            }

            .note-dot {
                display: none;
            }

            .import-note {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
            }

            /* --- Tombol aksi: Import di atas, Batal di bawah, lebar penuh --- */
            .import-actions {
                display: flex;
                flex-direction: column-reverse;
                gap: 10px;
                margin-top: 18px;
            }

            .import-actions .add-pop-btn-cancel,
            .import-actions .add-pop-btn-save {
                width: 100%;
                justify-content: center;
                text-align: center;
                padding: 11px 16px;
                font-size: 0.82rem;
            }

            /* --- Result box --- */
            .result-box {
                padding: 11px 13px;
                font-size: 0.78rem;
                border-radius: 8px;
            }

            .result-box ul {
                margin-left: 16px;
            }
        }

        /* ============ HP kecil (≤ 640px): tabel jadi kartu ============ */
        @media (max-width: 640px) {
            .col-info-scroll {
                overflow: visible;
            }

            .col-info-table,
            .col-info-table tbody,
            .col-info-table tr,
            .col-info-table td {
                display: block;
                width: 100%;
            }

            .col-info-table {
                min-width: 0;
                border: none;
            }

            .col-info-table thead {
                display: none;
            }

            .col-info-table tr {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }

            .col-info-table td {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 8px 12px;
                border: none;
                border-bottom: 1px solid #f1f5f9;
                font-size: 0.76rem;
                line-height: 1.5;
                overflow-wrap: anywhere;
            }

            .col-info-table td:last-child {
                border-bottom: none;
            }

            .col-info-table tr:nth-child(even) td {
                background: transparent;
            }

            .col-info-table td::before {
                content: attr(data-label);
                flex: 0 0 82px;
                font-size: 0.66rem;
                font-weight: 600;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                padding-top: 2px;
            }

            /* Baris pertama (header kolom) dibuat sebagai judul kartu */
            .col-info-table td:first-child {
                background: #f8fafc !important;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">

        {{-- SIDEBAR --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">
            <x-topbar />

            <div class="add-pop-content">
                {{-- HEADER BAR --}}
                <div class="add-pop-header-bar">
                    <a href="{{ route('pops.index') }}" class="add-pop-back" title="Kembali ke List POP">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="add-pop-header-text">
                        <x-breadcrumb :items="[['label' => 'POP', 'route' => 'pops.index'], ['label' => 'Import Excel POP']]" />
                        <p class="add-pop-subheading">Upload file Excel untuk menambah banyak data POP sekaligus</p>
                    </div>
                </div>

                {{-- HASIL IMPORT --}}
                @if (session('import_success'))
                    <div class="result-box result-success">
                        <strong><i class="bi bi-check-circle-fill"></i> Import Berhasil!</strong>
                        <p class="result-text">{{ session('import_success') }}</p>
                    </div>
                @endif

                @if (session('import_errors'))
                    <div class="result-box result-warning">
                        <strong><i class="bi bi-exclamation-triangle-fill"></i> Sebagian baris gagal diimport:</strong>
                        <ul>
                            @foreach (session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($errors->has('file'))
                    <div class="result-box result-error">
                        <strong><i class="bi bi-x-circle-fill"></i> File tidak valid:</strong>
                        <p style="margin: 4px 0 0;">{{ $errors->first('file') }}</p>
                    </div>
                @endif

                <div class="add-pop-card">

                    {{-- DOWNLOAD TEMPLATE --}}
                    <div class="template-info-box">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        <div class="ti-text">
                            <strong>Gunakan template yang sudah disediakan</strong>
                            <span>Pastikan header kolom di file Excel sesuai dengan template agar import
                                berhasil.</span>
                        </div>
                        <a href="{{ asset('templates/template_import_pop.xlsx') }}" class="btn-download-template"
                            download>
                            <i class="bi bi-download"></i> Download Template
                        </a>
                    </div>

                    {{-- INFO KOLOM --}}
                    <p class="col-info-title">
                        <i class="bi bi-table"></i> Kolom yang dibutuhkan di file Excel:
                    </p>

                    <div class="col-info-scroll">
                        <table class="col-info-table">
                            <thead>
                                <tr>
                                    <th>Header di Excel</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Nilai yang Diizinkan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Header"><code>Provinsi</code></td>
                                    <td data-label="Keterangan">Nama provinsi POP</td>
                                    <td data-label="Status"><span class="badge-required">Wajib</span></td>
                                    <td data-label="Nilai">Teks bebas</td>
                                </tr>
                                <tr>
                                    <td data-label="Header"><code>Kota/Kabupaten</code></td>
                                    <td data-label="Keterangan">Nama kota atau kabupaten</td>
                                    <td data-label="Status"><span class="badge-required">Wajib</span></td>
                                    <td data-label="Nilai">Teks bebas</td>
                                </tr>
                                <tr>
                                    <td data-label="Header"><code>ID POP</code></td>
                                    <td data-label="Keterangan">Kode unik POP (tidak boleh duplikat)</td>
                                    <td data-label="Status"><span class="badge-required">Wajib</span></td>
                                    <td data-label="Nilai">Teks bebas, unik</td>
                                </tr>
                                <tr>
                                    <td data-label="Header"><code>Nama POP</code></td>
                                    <td data-label="Keterangan">Nama lengkap POP</td>
                                    <td data-label="Status"><span class="badge-required">Wajib</span></td>
                                    <td data-label="Nilai">Teks bebas</td>
                                </tr>
                                <tr>
                                    <td data-label="Header"><code>Building</code></td>
                                    <td data-label="Keterangan">Jenis bangunan POP</td>
                                    <td data-label="Status"><span class="badge-optional">Opsional</span></td>
                                    <td data-label="Nilai">Shelter, Shelter CKD, Shelter Permanen, Mini Shelter, ODC,
                                        Mini POP, Mikro POP, OLT Gantung</td>
                                </tr>
                                <tr>
                                    <td data-label="Header"><code>Tipe POP</code></td>
                                    <td data-label="Keterangan">Tipe klasifikasi POP</td>
                                    <td data-label="Status"><span class="badge-optional">Opsional</span></td>
                                    <td data-label="Nilai">POP-SB, POP-A, POP-B, POP-D</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- FORM UPLOAD --}}
                    <form id="importForm" action="{{ route('pops.import.store') }}" method="POST"
                        enctype="multipart/form-data" class="import-form">
                        @csrf

                        <div class="import-dropzone" id="importDropzone"
                            onclick="document.getElementById('fileExcel').click()" ondragover="handleDragOver(event)"
                            ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                            <i class="bi bi-cloud-arrow-up-fill drop-icon"></i>
                            <div class="drop-title">
                                <span class="only-desktop">Seret &amp; Lepas file Excel di
                                    sini</span>
                                <span class="only-mobile">Pilih file Excel dari perangkat</span>
                            </div>
                            <div class="drop-sub">
                                <span class="only-desktop">atau klik untuk memilih file dari
                                    perangkat</span>
                                <span class="only-mobile">Ketuk tombol di bawah</span>
                            </div>
                            <button type="button" class="btn-pilih-file">
                                <i class="bi bi-folder2-open"></i> Pilih File
                            </button>
                            <input type="file" id="fileExcel" name="file" accept=".xlsx,.xls,.csv"
                                style="display: none;" onchange="handleFileSelect(this)">
                        </div>

                        {{-- Preview file terpilih --}}
                        <div class="file-selected-box" id="fileSelectedBox">
                            <i class="bi bi-file-earmark-excel-fill" style="color: #16a34a;"></i>
                            <span class="file-selected-name" id="fileSelectedName"></span>
                            <button type="button" class="btn-clear-file" onclick="clearFile()"
                                title="Hapus pilihan">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <p class="import-note">
                            <i class="bi bi-info-circle"></i> Format yang diterima: .xlsx, .xls, .csv <span
                                class="note-dot">·</span> Maksimal 5MB
                        </p>

                        {{-- ACTIONS --}}
                        <div class="add-pop-actions" style="margin-top: 24px;">
                            <a href="{{ route('pops.index') }}" class="add-pop-btn-cancel">Batal</a>
                            <button type="button" class="add-pop-btn-save" onclick="confirmImport()">
                                <i class="bi bi-upload"></i> Import Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>

    <script>
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                showFilePreview(input.files[0].name);
            }
        }

        function showFilePreview(name) {
            document.getElementById('fileSelectedBox').style.display = 'flex';
            document.getElementById('fileSelectedName').textContent = name;
        }

        function clearFile() {
            document.getElementById('fileExcel').value = '';
            document.getElementById('fileSelectedBox').style.display = 'none';
            document.getElementById('fileSelectedName').textContent = '';
        }

        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('importDropzone').classList.add('dragover');
        }

        function handleDragLeave(e) {
            document.getElementById('importDropzone').classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('importDropzone').classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = document.getElementById('fileExcel');
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                input.files = dt.files;
                showFilePreview(files[0].name);
            }
        }

        function confirmImport() {
            const fileInput = document.getElementById('fileExcel');
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Belum Dipilih',
                    text: 'Silakan pilih file Excel terlebih dahulu sebelum mengimport.',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            Swal.fire({
                title: 'Import Data POP?',
                html: `File <strong>${fileInput.files[0].name}</strong> akan diproses.<br>
                       <small style="color:#64748b;">Data yang sudah ada (ID POP duplikat) akan dilewati otomatis.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-upload"></i> Ya, Import!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('importForm').submit();
                }
            });
        }
    </script>
</body>

</html>
