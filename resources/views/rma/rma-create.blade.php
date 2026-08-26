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
    @vite(['resources/css/sidebar.css', 'resources/css/rma.css'])

    <style>
        /* Efek Hover untuk Tombol Tambah SFP */
        .tambah-link {
            background: none;
            border: none;
            color: #3b82f6;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .tambah-link:hover {
            color: #1d4ed8;
            transform: scale(1.02);
        }

        .tambah-link:active {
            transform: scale(0.95);
        }

        /* Desain SFP Item yang lebih bersih */
        .sfp-item {
            margin-top: 25px;
            margin-bottom: 15px;
        }

        /* Tombol hapus disesuaikan agar sejajar dengan judul */
        .btn-hapus {
            background: #ef4444;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-hapus:hover {
            background: #dc2626;
        }
    </style>
</head>

<body>

    <div class="app-container">

        <!-- SIDEBAR COMPONENT -->
        <x-sidebar active="rma" />

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOPBAR COMPONENT -->
            <x-topbar />

            <!-- CONTENT LAYOUT -->
            <div class="rma-layout">

                <div class="rma-form-area">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                        <a href="{{ route('rma') }}" class="btn-back" style="width:34px; height:34px; border-radius:50%; background:#f1f5f9; color:#64748b; display:inline-flex; align-items:center; justify-content:center; text-decoration:none;" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <x-breadcrumb :items="[['label' => 'RMA', 'route' => 'rma'], ['label' => 'Form Pengisian RMA']]" />
                            <p style="font-size:0.8rem; color:#64748b; margin:2px 0 0;">Return Material Authorization</p>
                        </div>
                    </div>

                    <form id="rmaForm" action="{{ route('rma.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="is_material_rusak" id="is_material_rusak" value="0">

                        <!-- ========================================== -->
                        <!-- STEP 1: DATA MATERIAL & KERUSAKAN          -->
                        <!-- ========================================== -->
                        <div id="step-1">
                            <div class="alert-box info-alert">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Isi Form berikut setiap melakukan pengembalian alat</span>
                                <button type="button" class="alert-close" aria-label="Tutup"><i
                                        class="bi bi-x"></i></button>
                            </div>

                            <div class="form-card">
                                <h2>Return Material Authorization (RMA)</h2>

                                <div class="form-group">
                                    <label for="so_po">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                                    <input type="text" id="so_po" name="so_po" class="form-control"
                                        placeholder="Masukkan nomor dokumen" required>
                                </div>

                                <div class="form-group">
                                    <label>Valuation Type <span>*</span></label>
                                    <div class="radio-group">
                                        <label class="radio-option"><input type="radio" name="valuation_type"
                                                value="ex-project" required> <span>Ex-Project</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type"
                                                value="dismantle"> <span>Dismantle</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type"
                                                value="rusak-L"> <span>Rusak-L</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type"
                                                value="rusak-TL"> <span>Rusak-TL</span></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal <span>*</span></label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_asal">Lokasi asal <span>*</span></label>
                                    <input type="text" id="lokasi_asal" name="lokasi_asal" class="form-control"
                                        placeholder="Masukkan lokasi asal" required>
                                </div>

                                <div class="form-group">
                                    <label for="nama_manager">Supervisor/Manager Name <span>*</span></label>
                                    <input type="text" id="nama_manager" name="nama_manager" class="form-control"
                                        placeholder="Nama Supervisor atau Manager" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_name">Customer Name (CPE)</label>
                                    <div class="field-description">Beri tanda (-) jika tidak ada</div>
                                    <input type="text" id="customer_name" class="form-control"
                                        placeholder="Nama Customer">
                                </div>

                                <div class="form-group">
                                    <label for="merk">Merk <span>*</span></label>
                                    <input type="text" id="merk" name="merk" class="form-control"
                                        placeholder="Merk perangkat" required>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type <span>*</span></label>
                                    <input type="text" id="type" name="type" class="form-control"
                                        placeholder="Tipe perangkat" required>
                                </div>

                                <div class="form-group">
                                    <label for="serial_number_primary">Serial Number (SN) / Batch <span>*</span></label>
                                    <input type="text" id="serial_number_primary" name="serial_number"
                                        class="form-control" placeholder="Contoh: SN-123456789" required>
                                </div>

                                <!-- Material Number dihapus atribut required-nya agar bebas diisi/tidak -->
                                <div class="form-group">
                                    <label for="material_number">Material Number</label>
                                    <input type="text" id="material_number" name="material_number"
                                        class="form-control" placeholder="Opsional">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description <span>*</span></label>
                                    <textarea id="description" name="description" class="form-control"
                                        placeholder="Deskripsikan kondisi secara singkat..." required></textarea>
                                </div>
                            </div>

                            <div class="form-card">
                                <div class="alert-box warning-alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>Beri tanda checklist pada kotak jika material rusak</span>
                                    <button type="button" class="alert-close" aria-label="Tutup"><i
                                            class="bi bi-x"></i></button>
                                </div>

                                <div class="checker-grid">
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Dead on Arrival"> Dead on Arrival</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Physical Damage"> Physical Damage</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Dead on Operational"> Dead on Operational</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Miscelaneous"> Miscelaneous</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="BER Indication"> BER Indication</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Intermittent"> Intermittent</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Software Error"> Software Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Rectifier faulty"> Rectifier/Inverter Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Channel Error"> Channel Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Charging switch"> Charging/Static Switch</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Port Error"> Port Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Battery faulty"> Battery Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Tx Laser Faulty"> Tx Laser Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]"
                                            value="Rx Laser Faulty"> Rx Laser Faulty</label>
                                </div>

                                <div class="form-group" style="margin-top: 24px;">
                                    <label for="alasan">Alasan Tambahan</label>
                                    <div class="field-description">Opsional</div>
                                    <textarea id="alasan" name="alasan" class="form-control" placeholder="Tuliskan alasan tambahan bila ada..."></textarea>
                                </div>
                            </div>

                            <div class="form-submit">
                                <button type="button" class="next-button" id="nextButton">Selanjutnya</button>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- STEP 2: UPLOAD FOTO & TANDA TANGAN         -->
                        <!-- ========================================== -->
                        <div id="step-2" style="display: none;">

                            <div class="alert-box info-alert"
                                style="background: #f8fafc; border: 1px solid #c7d2fe; color: #4f46e5;">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Isi halaman ini sebagai bukti pengembalian</span>
                                <button type="button" class="alert-close" aria-label="Tutup"><i
                                        class="bi bi-x"></i></button>
                            </div>

                            <div class="form-card" style="padding-bottom: 24px;">

                                <div class="upload-header"
                                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">
                                    <div class="upload-title-area">
                                        <label class="form-group" style="margin-bottom: 0; font-size: 16px;">Material
                                            Utama <span>*</span></label>
                                        <div class="field-description" style="margin-top: 2px;">Sesuai SN yang diinput
                                            pada langkah sebelumnya</div>
                                    </div>
                                    <button type="button" id="tambahSfp" class="tambah-link">
                                        Tambah SFP <i class="bi bi-plus-circle"></i>
                                    </button>
                                </div>

                                <div class="upload-dropzone" id="dropzone">
                                    <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                                    <div class="dropzone-text" id="dropzoneText">Masukkan file disini</div>
                                    <input type="file" id="fileInput" name="foto_material[]"
                                        accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;" required>
                                    <button type="button" class="btn-browse"
                                        onclick="document.getElementById('fileInput').click()">Browse</button>
                                </div>
                                <p style="font-size:0.75rem; color:#94a3b8; margin:6px 0 0;"><i class="bi bi-info-circle"></i> Maks. 2MB per foto, format JPG/PNG/WEBP</p>

                                <div id="sfp-container" style="margin-top: 15px;"></div>

                                <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e2e8f0;">

                                <div class="form-group">
                                    <label for="nama_pemohon">Nama Engineer / Pemohon <span>*</span></label>
                                    <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control"
                                        placeholder="Nama Terang Engineer" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Upload Tanda Tangan (Engineer Sign) <span>*</span></label>

                                    <!-- Container yang meniru bentuk form-control -->
                                    <div
                                        style="display: flex; align-items: center; gap: 15px; margin-top: 5px; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: #fff;">

                                        <!-- Tombol custom memicu input file hidden -->
                                        <button type="button" class="btn-browse"
                                            onclick="this.nextElementSibling.click()"
                                            style="padding: 8px 16px; font-size: 13px;">Pilih Foto</button>

                                        <!-- Input file hidden -->
                                        <input type="file" id="ttd_pemohon" name="ttd_pemohon"
                                            accept="image/png,image/jpeg,image/jpg,image/webp" required style="display: none;"
                                            onchange="this.nextElementSibling.innerText = this.files.length ? this.files[0].name : 'Belum ada file dipilih'; this.nextElementSibling.style.color = this.files.length ? '#10b981' : '#64748b';">

                                        <!-- Teks indikator nama file -->
                                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Belum ada file
                                            dipilih</span>

                                    </div>

                                    <div class="field-description" style="margin-top: 5px;">Unggah foto tanda tangan
                                        dengan latar belakang putih/transparan. <strong>Maks. 2MB, format JPG/PNG/WEBP.</strong></div>
                                </div>

                                <!-- FORM ACTIONS -->
                                <div class="form-actions"
                                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; gap: 15px;">
                                    <button type="button" class="btn-preview" id="prevButton">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </button>

                                    <button type="submit" class="btn-submit">
                                        <i class="bi bi-floppy"></i> Simpan RMA
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                <!-- NOTE ASIDE -->
                <aside class="note-card">
                    <h3>Note</h3>
                    <div class="note-list">
                        <div class="note-item"><span>Continue</span>
                            <p>Indikator error terjadi permanen terus menerus</p>
                        </div>
                        <div class="note-item"><span>Intermittent</span>
                            <p>Indikator error terjadi kadang-kadang sangat random</p>
                        </div>
                        <div class="note-item"><span>Dead on Arrival</span>
                            <p>Perangkat mati total rusak pada jangka waktu 24 jam setelah pemasangan</p>
                        </div>
                        <div class="note-item"><span>Dead on Operational</span>
                            <p>Perangkat mati total/rusak pada jangka waktu 24 jam setelah pemasangan</p>
                        </div>
                        <div class="note-item"><span>BER Indication</span>
                            <p>Indikator Error pada display modul/NMS/hasil disertakan no. thry error</p>
                        </div>
                        <div class="note-item"><span>Software Error</span>
                            <p>Gangguan yang disebabkan firmware/OS/Interface/Protocol</p>
                        </div>
                        <div class="note-item"><span>Tributary Error</span>
                            <p>Low Order Modul Error (PDH/SDH)</p>
                        </div>
                        <div class="note-item"><span>Channel Error</span>
                            <p>64K channelize &lt;2Mb Fault (for FEVM, V.24, Voice Ch)</p>
                        </div>
                        <div class="note-item"><span>Port Error</span>
                            <p>Port membangkitkan Error/mati total (IP Network Family, Converter)</p>
                        </div>
                        <div class="note-item"><span>Laser Tx Faulty</span>
                            <p>Only Optical Module TX Loss, No Signal, High Temp, Laser Bias</p>
                        </div>
                        <div class="note-item"><span>Laser Rx Faulty</span>
                            <p>Only Optical Module No.RX, Frame Error</p>
                        </div>
                        <div class="note-item"><span>Physical Damage</span>
                            <p>Rusak fisik perangkat (Benturan, Short Circuit, Liquid)</p>
                        </div>
                        <div class="note-item"><span>Miscellaneous</span>
                            <p>Sebab lain yang tidak tertulis diatas, mohon indikasi dijelaskan</p>
                        </div>
                    </div>
                </aside>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('rmaForm');
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const nextButton = document.getElementById('nextButton');
            const prevButton = document.getElementById('prevButton');
            const submitBtn = form.querySelector('[type="submit"]');

            // 1. PEMBERSIHAN KELAS IS-INVALID SAAT USER MULAI MENGISI
            form.addEventListener('input', function(e) {
                if (e.target.classList.contains('is-invalid')) {
                    e.target.classList.remove('is-invalid');
                }
                if (e.target.type === 'radio') {
                    const group = e.target.closest('.radio-group');
                    if (group) group.classList.remove('is-invalid');
                }
            });

            form.addEventListener('change', function(e) {
                if (e.target.classList.contains('is-invalid')) {
                    e.target.classList.remove('is-invalid');
                }
                const dropzone = e.target.closest('.upload-dropzone');
                if (dropzone && dropzone.classList.contains('is-invalid')) {
                    dropzone.classList.remove('is-invalid');
                }
                const ttdWrapper = e.target.parentElement;
                if (ttdWrapper && ttdWrapper.classList.contains('is-invalid')) {
                    ttdWrapper.classList.remove('is-invalid');
                }
            });

            // 2. VALIDASI LANGKAH 1 SEBELUM KE LANGKAH 2
            nextButton.addEventListener('click', function() {
                let isValid = true;
                const errors = [];

                const soPo = document.getElementById('so_po');
                if (!soPo.value.trim()) {
                    soPo.classList.add('is-invalid');
                    errors.push('No. IO.SP2K/SO/PO/ANDOP wajib diisi');
                    isValid = false;
                }

                const valuationType = document.querySelector('input[name="valuation_type"]:checked');
                if (!valuationType) {
                    const group = document.querySelector('.radio-group');
                    if (group) group.classList.add('is-invalid');
                    errors.push('Valuation Type wajib dipilih');
                    isValid = false;
                }

                const tanggal = document.getElementById('tanggal');
                if (!tanggal.value) {
                    tanggal.classList.add('is-invalid');
                    errors.push('Tanggal wajib diisi');
                    isValid = false;
                }

                const lokasiAsal = document.getElementById('lokasi_asal');
                if (!lokasiAsal.value.trim()) {
                    lokasiAsal.classList.add('is-invalid');
                    errors.push('Lokasi asal wajib diisi');
                    isValid = false;
                }

                const namaManager = document.getElementById('nama_manager');
                if (!namaManager.value.trim()) {
                    namaManager.classList.add('is-invalid');
                    errors.push('Supervisor / Manager Name wajib diisi');
                    isValid = false;
                }

                const merk = document.getElementById('merk');
                if (!merk.value.trim()) {
                    merk.classList.add('is-invalid');
                    errors.push('Merk perangkat wajib diisi');
                    isValid = false;
                }

                const type = document.getElementById('type');
                if (!type.value.trim()) {
                    type.classList.add('is-invalid');
                    errors.push('Tipe perangkat wajib diisi');
                    isValid = false;
                }

                const serialNumber = document.getElementById('serial_number_primary');
                if (!serialNumber.value.trim()) {
                    serialNumber.classList.add('is-invalid');
                    errors.push('Serial Number (SN) wajib diisi');
                    isValid = false;
                }

                const description = document.getElementById('description');
                if (!description.value.trim()) {
                    description.classList.add('is-invalid');
                    errors.push('Description wajib diisi');
                    isValid = false;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lengkapi Data Langkah 1',
                        html: `<div style="text-align:left; padding: 0 5px;"><ul style="margin:0; padding-left:18px; color:#475569; font-size:13px; line-height:1.7;">${errors.map(e => `<li>${e}</li>`).join('')}</ul></div>`,
                        confirmButtonColor: '#0066DC',
                        confirmButtonText: 'Mengerti'
                    });
                    const firstInvalid = step1.querySelector('.is-invalid');
                    if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                step1.style.display = 'none';
                step2.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            prevButton.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 3. SUBMIT FORM VIA AJAX DENGAN PENANGANAN ERROR LENGKAP
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validasi ukuran file client-side (maks 2MB per file)
                const MAX_FILE_SIZE = 2 * 1024 * 1024;
                const fileInputs = form.querySelectorAll('input[type="file"]');
                for (const input of fileInputs) {
                    for (const file of input.files) {
                        if (file.size > MAX_FILE_SIZE) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ukuran File Terlalu Besar',
                                text: `File "${file.name}" berukuran ${(file.size / (1024 * 1024)).toFixed(2)} MB. Maksimal ukuran file yang diizinkan adalah 2 MB.`,
                                confirmButtonColor: '#ef4444'
                            });
                            return;
                        }
                    }
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';

                // Bersihkan is-invalid lama
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData,
                    });

                    // Coba parse JSON
                    let responseData = null;
                    try {
                        responseData = await response.json();
                    } catch (jsonErr) {
                        // Response bukan JSON (misal HTML error page dari web server)
                    }

                    if (response.ok && responseData) {
                        // Buka PDF di tab baru
                        window.open(responseData.pdf_url, '_blank');
                        // Redirect halaman ini ke riwayat RMA
                        window.location.href = responseData.redirect_url;
                        return;
                    }

                    // Penanganan Error Berdasarkan Status Code
                    if (response.status === 422 && responseData) {
                        const errorKeys = Object.keys(responseData.errors || {});
                        const messages = Object.values(responseData.errors || {}).flat();

                        const fieldMap = {
                            'so_po': 'so_po',
                            'tanggal': 'tanggal',
                            'lokasi_asal': 'lokasi_asal',
                            'nama_manager': 'nama_manager',
                            'merk': 'merk',
                            'type': 'type',
                            'serial_number': 'serial_number_primary',
                            'description': 'description',
                            'nama_pemohon': 'nama_pemohon',
                        };

                        let hasStep1Error = false;
                        let hasStep2Error = false;

                        errorKeys.forEach(k => {
                            if (fieldMap[k]) {
                                const el = document.getElementById(fieldMap[k]);
                                if (el) el.classList.add('is-invalid');
                            }
                            if (k === 'valuation_type') {
                                document.querySelector('.radio-group')?.classList.add('is-invalid');
                            }
                            if (k.startsWith('foto_material')) {
                                document.getElementById('dropzone')?.classList.add('is-invalid');
                                hasStep2Error = true;
                            }
                            if (k === 'ttd_pemohon') {
                                document.getElementById('ttd_pemohon')?.parentElement?.classList.add('is-invalid');
                                hasStep2Error = true;
                            }
                            if (['so_po','valuation_type','tanggal','lokasi_asal','nama_manager','merk','type','serial_number','description'].includes(k)) {
                                hasStep1Error = true;
                            }
                            if (['nama_pemohon','ttd_pemohon'].includes(k)) {
                                hasStep2Error = true;
                            }
                        });

                        // Beralih ke step yang terdapat error
                        if (hasStep1Error && step1.style.display === 'none') {
                            step2.style.display = 'none';
                            step1.style.display = 'block';
                        } else if (!hasStep1Error && hasStep2Error && step2.style.display === 'none') {
                            step1.style.display = 'none';
                            step2.style.display = 'block';
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap / Tidak Sesuai',
                            html: `<div style="text-align: left; padding: 0 5px;"><ul style="margin: 0; padding-left: 18px; color: #475569; font-size: 13px; line-height: 1.7;">${messages.map(m => `<li>${m}</li>`).join('')}</ul></div>`,
                            confirmButtonColor: '#0066DC',
                            confirmButtonText: 'Perbaiki'
                        });

                        const firstInvalid = form.querySelector('.is-invalid');
                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else if (response.status === 413) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar (Error 413)',
                            text: 'Ukuran file yang Anda unggah melebihi kapasitas server. Pastikan setiap gambar berukuran di bawah 2 MB.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else if (response.status === 419) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesi Kedaluwarsa (Error 419)',
                            text: 'Sesi formulir Anda telah berakhir. Silakan refresh halaman dan coba kembali.',
                            confirmButtonColor: '#0066DC',
                            confirmButtonText: 'Refresh Halaman'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        const errMsg = (responseData && responseData.message) ? responseData.message : 'Terjadi kendala pada server saat menyimpan formulir.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan Data',
                            text: errMsg,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                } catch (err) {
                    console.error('Submit RMA error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memproses Permintaan',
                        text: 'Terjadi masalah saat mengirim data ke server. Pastikan koneksi dan file Anda sesuai lalu coba kembali.',
                        confirmButtonColor: '#ef4444'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-floppy"></i> Simpan RMA';
                }
            });

            // 4. LOGIKA STATUS KERUSAKAN
            const damageCheckboxes = document.querySelectorAll('input[name="kerusakan[]"]');
            const isMaterialRusakInput = document.getElementById('is_material_rusak');

            damageCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    const isDamaged = Array.from(damageCheckboxes).some(c => c.checked);
                    isMaterialRusakInput.value = isDamaged ? '1' : '0';
                });
            });

            // 5. LOGIKA DROPZONE MATERIAL UTAMA
            const fileInput = document.getElementById('fileInput');
            const dropzoneText = document.getElementById('dropzoneText');

            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    dropzoneText.innerText = e.target.files[0].name;
                    dropzoneText.style.color = '#10b981';
                } else {
                    dropzoneText.innerText = 'Masukkan file disini';
                    dropzoneText.style.color = '#64748b';
                }
            });

            // 6. TAMBAH SFP
            const tambahSfpBtn = document.getElementById('tambahSfp');
            const sfpContainer = document.getElementById('sfp-container');

            tambahSfpBtn.addEventListener('click', function() {
                const sfpBlock = document.createElement('div');
                sfpBlock.className = 'sfp-item';

                sfpBlock.innerHTML = `
                    <div class="form-group" style="margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="margin-bottom: 0; font-size: 16px;">Foto Material Tambahan (SFP) <span>*</span></label>
                            <button type="button" class="btn-hapus" onclick="this.closest('.sfp-item').remove()">Hapus <i class="bi bi-trash3-fill"></i></button>
                        </div>
                        
                        <div class="upload-dropzone">
                            <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                            <div class="dropzone-text">Masukkan file disini</div>
                            
                            <input type="file" name="foto_material[]" accept="image/jpeg,image/png,image/jpg,image/webp" required style="display: none;" 
                                onchange="this.previousElementSibling.innerText = this.files.length ? this.files[0].name : 'Masukkan file disini'; this.previousElementSibling.style.color = this.files.length ? '#10b981' : '#64748b';">
                            
                            <button type="button" class="btn-browse" onclick="this.previousElementSibling.click()">Browse</button>
                        </div>
                        <p style="font-size:0.75rem; color:#94a3b8; margin:6px 0 0;"><i class="bi bi-info-circle"></i> Maks. 2MB, format JPG/PNG/WEBP</p>
                    </div>
                `;
                sfpContainer.appendChild(sfpBlock);
            });

            // 7. ALERT CLOSE
            document.querySelectorAll('.alert-close').forEach(function(button) {
                button.addEventListener('click', function() {
                    const alert = button.closest('.alert-box');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 200);
                    }
                });
            });

        });
    </script>
</body>

</html>
