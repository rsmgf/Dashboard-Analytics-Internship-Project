<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Air Conditioner - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-detail.css'
    ])
</head>

<body>

    <div class="app-container">

        <x-sidebar />

        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            <x-topbar />

            <div class="ac-content">

                <div class="detail-page-header">
                    <div class="ac-page-info">
                        <a href="{{ url()->previous() }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="detail-container">

                        <div class="form-card">
                            <h3 class="form-section-title">
                                General Information
                            </h3>

                            <div class="detail-grid-3">
                                <div class="detail-item">
                                    <span class="detail-label">POP</span>
                                    <div style="padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.84rem; font-weight: 500; color: #334155;">
                                        POP_1MBN10004
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <label for="building" class="detail-label">Building</label>
                                    <input type="text" name="building" id="building" class="form-control-custom" value="Shelter Permanen" required>
                                </div>

                                <div class="detail-item">
                                    <label for="type_pop" class="detail-label">Type POP</label>
                                    <input type="text" name="type_pop" id="type_pop" class="form-control-custom" value="POP-SB" required>
                                </div>
                            </div>
                        </div>

                        <div class="detail-bottom-grid">

                            <div class="form-card mb-0">
                                <h3 class="form-section-title">
                                    Checklist Air Conditioner
                                </h3>

                                <div class="checklist-table-container">
                                    <div class="checklist-row">
                                        <div class="checklist-label">Nomor AC</div>
                                        <div class="checklist-field">
                                            <input type="text" name="nomor_ac" class="form-control-custom" value="AC-01" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Merk AC</div>
                                        <div class="checklist-field">
                                            <input type="text" name="merk_ac" class="form-control-custom" value="Daikin" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Type AC</div>
                                        <div class="checklist-field">
                                            <input type="text" name="type_ac" class="form-control-custom" value="Split" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">PK</div>
                                        <div class="checklist-field">
                                            <input type="text" name="pk" class="form-control-custom" value="1 PK" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Jenis Freon</div>
                                        <div class="checklist-field">
                                            <input type="text" name="jenis_freon" class="form-control-custom" value="R32" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tahun Manufaktur</div>
                                        <div class="checklist-field">
                                            <input type="number" name="tahun_manufaktur" class="form-control-custom" value="2023" required>
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tanggal Instalasi</div>
                                        <div class="checklist-field">
                                            <input type="date" name="tanggal_instalasi" class="form-control-custom" value="2024-01-12">
                                        </div>
                                    </div>
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tanggal Terakhir PM</div>
                                        <div class="checklist-field">
                                            <select name="status_pm" class="form-control-custom select-custom">
                                                <option value="ADA OK" selected>ADA OK</option>
                                                <option value="BAD">BAD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-card mb-0 photo-card-wrapper">
                                <h3 class="form-section-title">
                                    Photo Air Conditioner
                                </h3>

                                <div class="detail-photo-box" style="flex-direction: column; gap: 14px; padding: 16px; background: #ffffff; height: auto;">
                                    <div style="width: 100%; height: 250px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; position: relative;">
                                        <img src="" alt="Preview Foto" id="previewPhoto" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                        <div id="noDetailPhoto" class="no-preview">
                                            <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                            <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Belum ada foto baru yang dipilih</span>
                                        </div>
                                    </div>

                                    <div style="width: 100%; display: flex; flex-direction: column; gap: 6px;">
                                        <label for="photoInput" class="upload-btn-custom">
                                            <i class="bi bi-upload"></i> Unggah Foto Baru
                                        </label>
                                        <input type="file" name="photo_ac" id="photoInput" accept="image/*" style="display: none;">
                                        <span id="fileName" style="font-size: 0.78rem; color: #64748b; text-align: center;">Format: JPG, PNG, JPEG (Maks. 2MB)</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <a href="{{ url()->previous() }}" class="btn-action-cancel" style="padding: 10px 22px; border-radius: 8px; background: #e2e8f0; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem; transition: all 0.2s;">Batal</a>
                            <button type="submit" class="btn-action-save" style="padding: 10px 24px; border-radius: 8px; background: #0086ff; color: #ffffff; border: none; font-weight: 600; font-size: 0.88rem; cursor: pointer; box-shadow: 0 2px 4px rgba(0, 134, 255, 0.25); transition: all 0.2s;">Simpan Perubahan</button>
                        </div>

                    </div>
                </form>

            </div>

        </main>

    </div>

    <script>
        document.getElementById('photoInput').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('previewPhoto');
                const noPhoto = document.getElementById('noDetailPhoto');
                const fileNameSpan = document.getElementById('fileName');
                
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (noPhoto) {
                    noPhoto.style.display = 'none';
                }
                fileNameSpan.textContent = "File dipilih: " + file.name;
                fileNameSpan.style.color = "#059669";
                fileNameSpan.style.fontWeight = "600";
            }
        });
    </script>

</body>

</html>