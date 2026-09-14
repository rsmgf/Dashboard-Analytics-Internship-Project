<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Air Conditioner - PLN Icon Plus</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    @vite([
        'resources/css/sidebar.css',
        'resources/css/ac-create.css'
    ])
</head>

<body>

    <div class="app-container">

        <x-sidebar />

        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            <x-topbar />

            <div class="ac-content">

                <div class="ac-page-header">
                    <div class="ac-page-info">
                        <a href="{{ url()->previous() }}"
                            class="back-button"
                            title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <form action="#" method="POST" id="acForm" enctype="multipart/form-data">

                    @csrf

                    <div class="form-card">

                        <h3 class="form-section-title">
                            General Information
                        </h3>

                        <div class="form-grid-3">

                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text"
                                    id="pop"
                                    name="pop"
                                    class="form-control disabled-input"
                                    value="POP_1MBN10004"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label for="building">Building <span class="required">*</span></label>
                                <input type="text"
                                    id="building"
                                    name="building"
                                    class="form-control"
                                    placeholder="Masukkan building"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="type_pop">Type POP <span class="required">*</span></label>
                                <input type="text"
                                    id="type_pop"
                                    name="type_pop"
                                    class="form-control"
                                    placeholder="Masukkan type POP"
                                    required>
                            </div>

                        </div>
                    </div>

                    <div class="form-card">

                        <h3 class="form-section-title">
                            Detail Air Conditioner
                        </h3>

                        <div class="form-grid-2">

                            <div class="form-group">
                                <label for="nomor_ac">Nomor AC <span class="required">*</span></label>
                                <input type="text"
                                    id="nomor_ac"
                                    name="nomor_ac"
                                    class="form-control"
                                    placeholder="Masukkan nomor AC"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_freon">Jenis Freon <span class="required">*</span></label>
                                <select id="jenis_freon"
                                    name="jenis_freon"
                                    class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih Jenis Freon</option>
                                    <option value="R134a">R134a</option>
                                    <option value="R22">R22</option>
                                    <option value="R32">R32</option>
                                    <option value="R410">R410</option>
                                    <option value="R410A">R410A</option>
                                    <option value="R407C">R407C</option>
                                    <option value="Others">Others</option>
                                </select>
                                <input type="text"
                                    id="jenis_freon_others"
                                    name="jenis_freon_others"
                                    class="form-control mt-2"
                                    placeholder="Masukkan jenis freon lainnya"
                                    style="display: none;">
                            </div>

                            <div class="form-group">
                                <label for="merk_ac">Merk AC <span class="required">*</span></label>
                                <select id="merk_ac"
                                    name="merk_ac"
                                    class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih Merk AC</option>
                                    <option value="Aqua">Aqua</option>
                                    <option value="Kabinet">Kabinet</option>
                                    <option value="Daikin">Daikin</option>
                                    <option value="DBS">DBS</option>
                                    <option value="Gree">Gree</option>
                                    <option value="Hopep">Hopep</option>
                                    <option value="Huarui">Huarui</option>
                                    <option value="LG">LG</option>
                                    <option value="Midea">Midea</option>
                                    <option value="Panasonic">Panasonic</option>
                                    <option value="Samsung">Samsung</option>
                                    <option value="Sharp">Sharp</option>
                                    <option value="TCL">TCL</option>
                                    <option value="Others">Others</option>
                                </select>
                                <input type="text"
                                    id="merk_ac_others"
                                    name="merk_ac_others"
                                    class="form-control mt-2"
                                    placeholder="Masukkan merk AC lainnya"
                                    style="display: none;">
                            </div>

                            <div class="form-group">
                                <label for="tahun_manufaktur">Tahun Manufaktur <span class="required">*</span></label>
                                <select id="tahun_manufaktur"
                                    name="tahun_manufaktur"
                                    class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih Tahun Manufaktur</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type_ac">Type AC <span class="required">*</span></label>
                                <select id="type_ac"
                                    name="type_ac"
                                    class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih Type AC</option>
                                    <option value="Split">Split</option>
                                    <option value="Cassette">Cassette</option>
                                    <option value="Standing">Standing</option>
                                    <option value="Central">Central</option>
                                    <option value="Floor Standing">Floor Standing</option>
                                    <option value="Others">Others</option>
                                </select>
                                <input type="text"
                                    id="type_ac_others"
                                    name="type_ac_others"
                                    class="form-control mt-2"
                                    placeholder="Masukkan type AC lainnya"
                                    style="display: none;">
                            </div>

                            <div class="form-group">
                                <label for="pk">PK <span class="required">*</span></label>
                                <select id="pk"
                                    name="pk"
                                    class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih PK</option>
                                    <option value="0,5">0,5 PK</option>
                                    <option value="1">1 PK</option>
                                    <option value="1,5">1,5 PK</option>
                                    <option value="2">2 PK</option>
                                    <option value="2,5">2,5 PK</option>
                                    <option value="5">5 PK</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_instalasi">Tanggal Instalasi <span class="required">*</span></label>
                                <input type="date"
                                    id="tanggal_instalasi"
                                    name="tanggal_instalasi"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_terakhir_pm">Tanggal Terakhir PM <span class="required">*</span></label>
                                <input type="date"
                                    id="tanggal_terakhir_pm"
                                    name="tanggal_terakhir_pm"
                                    class="form-control"
                                    required>
                            </div>

                        </div>
                    </div>

                    <div class="form-card">

                        <h3 class="form-section-title">
                            Photo Air Conditioner
                        </h3>

                        <div class="form-group">
                            <label>Upload foto kondisi AC di lokasi</label>

                            <div class="upload-container">
                                <div class="upload-dropzone" id="dropzoneAC">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <span class="upload-text">Drag & drop file disini atau</span>
                                    <label for="photo_ac" class="btn-browse">Browse</label>
                                    <input type="file"
                                        id="photo_ac"
                                        name="photo_ac"
                                        accept="image/jpeg,image/png,image/jpg"
                                        hidden>
                                </div>

                                <div class="preview-container">
                                    <span class="preview-title">Preview foto</span>
                                    <div class="preview-box">
                                        <img id="previewAC"
                                            src=""
                                            alt="Preview AC"
                                            style="display: none;">
                                        <div id="noPreviewAC" class="no-preview">
                                            <i class="bi bi-image"></i>
                                            <span>Belum ada foto yang dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="upload-info">Format: JPG, JPEG, PNG + Maks. ukuran : 10 MB</small>
                        </div>

                        <div class="form-group photo-description">
                            <label for="keterangan_gambar_ac">
                                Tuliskan keterangan gambar <span class="required">*</span>
                            </label>
                            <input type="text"
                                id="keterangan_gambar_ac"
                                name="keterangan_gambar_ac"
                                class="form-control"
                                placeholder="Masukkan keterangan gambar"
                                required>
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

            const tahunManufaktur = document.getElementById('tahun_manufaktur');
            const currentYear = new Date().getFullYear();

            for (let tahun = currentYear; tahun >= 2013; tahun--) {
                const option = document.createElement('option');
                option.value = tahun;
                option.textContent = tahun;
                tahunManufaktur.appendChild(option);
            }

            const jenisFreon = document.getElementById('jenis_freon');
            const freonOthers = document.getElementById('jenis_freon_others');

            jenisFreon.addEventListener('change', function () {
                if (this.value === 'Others') {
                    freonOthers.style.display = 'block';
                    freonOthers.required = true;
                } else {
                    freonOthers.style.display = 'none';
                    freonOthers.required = false;
                    freonOthers.value = '';
                }
            });

            const merkAC = document.getElementById('merk_ac');
            const merkOthers = document.getElementById('merk_ac_others');

            merkAC.addEventListener('change', function () {
                if (this.value === 'Others') {
                    merkOthers.style.display = 'block';
                    merkOthers.required = true;
                } else {
                    merkOthers.style.display = 'none';
                    merkOthers.required = false;
                    merkOthers.value = '';
                }
            });

            const typeAC = document.getElementById('type_ac');
            const typeOthers = document.getElementById('type_ac_others');

            typeAC.addEventListener('change', function () {
                if (this.value === 'Others') {
                    typeOthers.style.display = 'block';
                    typeOthers.required = true;
                } else {
                    typeOthers.style.display = 'none';
                    typeOthers.required = false;
                    typeOthers.value = '';
                }
            });

            const photoAC = document.getElementById('photo_ac');
            const previewAC = document.getElementById('previewAC');
            const noPreviewAC = document.getElementById('noPreviewAC');

            photoAC.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file maksimal 10 MB.');
                    this.value = '';
                    previewAC.style.display = 'none';
                    noPreviewAC.style.display = 'flex';
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, atau PNG.');
                    this.value = '';
                    previewAC.style.display = 'none';
                    noPreviewAC.style.display = 'flex';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewAC.src = e.target.result;
                    previewAC.style.display = 'block';
                    noPreviewAC.style.display = 'none';
                };
                reader.readAsDataURL(file);
            });

            const form = document.getElementById('acForm');
            form.addEventListener('reset', function () {
                setTimeout(function () {
                    previewAC.src = '';
                    previewAC.style.display = 'none';
                    noPreviewAC.style.display = 'flex';

                    freonOthers.style.display = 'none';
                    freonOthers.required = false;

                    merkOthers.style.display = 'none';
                    merkOthers.required = false;

                    typeOthers.style.display = 'none';
                    typeOthers.required = false;
                }, 10);
            });

        });
    </script>

</body>

</html>