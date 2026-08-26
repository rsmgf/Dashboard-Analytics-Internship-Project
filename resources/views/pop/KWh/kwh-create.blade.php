<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah KWH - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    @vite([
        'resources/css/sidebar.css',
        'resources/css/kwh-create.css'
    ])
</head>
<body>

<div class="app-container">
    <x-sidebar />
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="main-content">
        <x-topbar />

        <div class="content">
            <div class="detail-top">
                <a href="#" class="back-button" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- GENERAL INFORMATION -->
                <section class="detail-card">
                    <div class="detail-card-title">
                        General Information
                    </div>

                    <!-- Menggunakan general-form-grid khusus agar tidak bentrok dengan tabel -->
                    <div class="general-form-grid">
                        <div class="general-item">
                            <span class="general-label">POP</span>
                            <input type="text" class="form-input" value="POP_1MBN10004" disabled>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Building <span class="text-danger">*</span></span>
                            <input type="text" class="form-input" placeholder="Masukkan nama building" required>
                        </div>

                        <div class="general-item">
                            <span class="general-label">PIC <span class="text-danger">*</span></span>
                            <input type="text" class="form-input" placeholder="Masukkan nama PIC" required>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Type POP <span class="text-danger">*</span></span>
                            <input type="text" class="form-input" placeholder="Masukkan type POP" required>
                        </div>

                        <div class="general-item">
                            <span class="general-label">ID Customer <span class="text-danger">*</span></span>
                            <input type="text" class="form-input" placeholder="Masukkan ID Customer" required>
                        </div>

                        <div class="general-item">
                            <span class="general-label">Tanggal Pemeriksaan <span class="text-danger">*</span></span>
                            <input type="date" class="form-input" required>
                        </div>
                    </div>
                </section>

                <!-- CHECKLIST KWH -->
                <section class="detail-card checklist-card">
                    <div class="detail-card-title">
                        Checklist KWh
                    </div>

                    <div class="table-wrapper">
                        <table class="kwh-table">
                            <tbody>
                                <tr>
                                    <th class="label-column">Daya (PS GI) <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Contoh: 33.000 VA" required></td>
                                </tr>
                                <tr>
                                    <th class="label-column">MCB <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Contoh: 50A X 3 (150 A) / ABB" required></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Jumlah Phasa <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Contoh: 3 Phasa" required></td>
                                </tr>
                                <tr>
                                    <th rowspan="7" class="label-column">Pengukuran Phasa <span class="text-danger">*</span></th>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>Tegangan R - N (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>R (A)</span>
                                            <input type="text" class="form-input" placeholder="Ampera">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>Tegangan S - N (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>S (A)</span>
                                            <input type="text" class="form-input" placeholder="Ampera">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>Tegangan T - N (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group-phasa">
                                            <span>T (A)</span>
                                            <input type="text" class="form-input" placeholder="Ampera">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group-phasa">
                                            <span>Tegangan R - S (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group-phasa">
                                            <span>Tegangan S - T (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group-phasa">
                                            <span>Tegangan R - T (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="input-group-phasa">
                                            <span>Tegangan N - G (Vac)</span>
                                            <input type="text" class="form-input" placeholder="Vac">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Arester <span class="text-danger">*</span></th>
                                    <td><input type="text" class="form-input" placeholder="Kondisi Arrester"></td>
                                    <td><input type="text" class="form-input" placeholder="Merk/Type"></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Kabel Output KWh <span class="text-danger">*</span></th>
                                    <td>
                                        <div class="cable-info">
                                            <strong>WARNA :</strong>
                                            <div class="cable-row"><span>R</span><input type="text" class="form-input" placeholder="Warna"></div>
                                            <div class="cable-row"><span>S</span><input type="text" class="form-input" placeholder="Warna"></div>
                                            <div class="cable-row"><span>T</span><input type="text" class="form-input" placeholder="Warna"></div>
                                            <div class="cable-row"><span>N</span><input type="text" class="form-input" placeholder="Warna"></div>
                                            <div class="cable-row"><span>Grounding</span><input type="text" class="form-input" placeholder="Warna"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cable-info">
                                            <strong>UKURAN KABEL (mm) :</strong>
                                            <div class="cable-row"><span>R (mm)</span><input type="text" class="form-input" placeholder="Ukuran"></div>
                                            <div class="cable-row"><span>S (mm)</span><input type="text" class="form-input" placeholder="Ukuran"></div>
                                            <div class="cable-row"><span>T (mm)</span><input type="text" class="form-input" placeholder="Ukuran"></div>
                                            <div class="cable-row"><span>N (mm)</span><input type="text" class="form-input" placeholder="Ukuran"></div>
                                            <div class="cable-row"><span>G (mm)</span><input type="text" class="form-input" placeholder="Ukuran"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label-column">Total Daya Terpakai (VA) <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Total Daya" required></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Total Beban (A) <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Total Beban" required></td>
                                </tr>
                                <tr>
                                    <th class="label-column">Status <span class="text-danger">*</span></th>
                                    <td colspan="2"><input type="text" class="form-input" placeholder="Status (OK / Memadai)" required></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- PHOTO KWH -->
                <section class="detail-card photo-card">
                    <div class="photo-title">
                        <i class="bi bi-camera-fill"></i>
                        <span>Photo KWH</span>
                    </div>

                    <div class="upload-container">
                        <div class="upload-box">
                            <div class="upload-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <span>Masukkan file disini</span>
                                <label for="fileUpload" class="btn-browse">Browse</label>
                                <input type="file" id="fileUpload" hidden>
                            </div>
                            <small class="upload-hint">Format: JPG, JPEG, PNG • Maks. ukuran: 10 MB</small>
                        </div>
                        <div class="preview-box">
                            <span class="preview-title">Preview foto</span>
                            <div class="preview-placeholder">
                                <i class="bi bi-image"></i>
                                <small>Belum ada foto yang dipilih</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="general-label">Tuliskan keterangan gambar <span class="text-danger">*</span></label>
                        <input type="text" class="form-input" placeholder="Keterangan gambar..." required>
                    </div>
                </section>

                <!-- NOTE -->
                <section class="detail-card note-card">
                    <div class="detail-card-title text-primary">Note</div>
                    <div class="note-content">
                        <strong>Tindakan yang harus dilakukan:</strong>
                        <ol>
                            <li>Pastikan setiap tegangan setiap phasa berkisar antara 210 - 225 Vac, jika di bawah itu atau di atas bisa di infokan ke tim NOC atau tim internal ICON.</li>
                            <li>Pastikan semua kabel yang terpasang dalam keadaan kencang terpasang ke baut/MCB.</li>
                            <li>Perhatikan arrester dalam keadaan baik/hijau jika di temukan dalam keadaan merah infokan ke NOC atau tim internal ICON.</li>
                            <li>Pastikan box kWh tidak dalam berkarat. Jika berkarat lakukan pengecetan dengan cat anti karat.</li>
                        </ol>
                    </div>
                </section>

                <div class="form-actions">
                    <button type="reset" class="btn-secondary">Reset</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>