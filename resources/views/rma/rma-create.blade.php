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
            background: #ef4444; color: white; border: none; 
            padding: 4px 12px; border-radius: 6px; cursor: pointer; 
            font-size: 12px; font-weight: 600; transition: 0.2s;
        }
        .btn-hapus:hover { background: #dc2626; }
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

                    <form id="rmaForm" action="{{ route('rma.store') }}" method="POST" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <input type="hidden" name="is_material_rusak" id="is_material_rusak" value="0">

                        <!-- ========================================== -->
                        <!-- STEP 1: DATA MATERIAL & KERUSAKAN          -->
                        <!-- ========================================== -->
                        <div id="step-1">
                            <div class="alert-box info-alert">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Isi Form berikut setiap melakukan pengembalian alat</span>
                                <button type="button" class="alert-close" aria-label="Tutup"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="form-card">
                                <h2>Return Material Authorization (RMA)</h2>

                                <div class="form-group">
                                    <label for="so_po">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                                    <input type="text" id="so_po" name="so_po" class="form-control" placeholder="Masukkan nomor dokumen" required>
                                </div>

                                <div class="form-group">
                                    <label>Valuation Type <span>*</span></label>
                                    <div class="radio-group">
                                        <label class="radio-option"><input type="radio" name="valuation_type" value="ex-project" required> <span>Ex-Project</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type" value="dismantle"> <span>Dismantle</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type" value="rusak-L"> <span>Rusak-L</span></label>
                                        <label class="radio-option"><input type="radio" name="valuation_type" value="rusak-TL"> <span>Rusak-TL</span></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal <span>*</span></label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_asal">Lokasi asal <span>*</span></label>
                                    <input type="text" id="lokasi_asal" name="lokasi_asal" class="form-control" placeholder="Masukkan lokasi asal" required>
                                </div>

                                <div class="form-group">
                                    <label for="nama_manager">Supervisor/Manager Name <span>*</span></label>
                                    <input type="text" id="nama_manager" name="nama_manager" class="form-control" placeholder="Nama Supervisor atau Manager" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_name">Customer Name (CPE)</label>
                                    <div class="field-description">Beri tanda (-) jika tidak ada</div>
                                    <input type="text" id="customer_name" class="form-control" placeholder="Nama Customer">
                                </div>

                                <div class="form-group">
                                    <label for="merk">Merk <span>*</span></label>
                                    <input type="text" id="merk" name="merk" class="form-control" placeholder="Merk perangkat" required>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type <span>*</span></label>
                                    <input type="text" id="type" name="type" class="form-control" placeholder="Tipe perangkat" required>
                                </div>

                                <div class="form-group">
                                    <label for="serial_number_primary">Serial Number (SN) / Batch <span>*</span></label>
                                    <input type="text" id="serial_number_primary" name="serial_number" class="form-control" placeholder="Contoh: SN-123456789" required>
                                </div>

                                <!-- Material Number dihapus atribut required-nya agar bebas diisi/tidak -->
                                <div class="form-group">
                                    <label for="material_number">Material Number</label>
                                    <input type="text" id="material_number" name="material_number" class="form-control" placeholder="Opsional">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description <span>*</span></label>
                                    <textarea id="description" name="description" class="form-control" placeholder="Deskripsikan kondisi secara singkat..." required></textarea>
                                </div>
                            </div>

                            <div class="form-card">
                                <div class="alert-box warning-alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>Beri tanda checklist pada kotak jika material rusak</span>
                                    <button type="button" class="alert-close" aria-label="Tutup"><i class="bi bi-x"></i></button>
                                </div>

                                <div class="checker-grid">
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Dead on Arrival"> Dead on Arrival</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Physical Damage"> Physical Damage</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Dead on Operational"> Dead on Operational</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Miscelaneous"> Miscelaneous</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="BER Indication"> BER Indication</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Intermittent"> Intermittent</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Software Error"> Software Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Rectifier faulty"> Rectifier/Inverter Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Channel Error"> Channel Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Charging switch"> Charging/Static Switch</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Port Error"> Port Error</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Battery faulty"> Battery Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Tx Laser Faulty"> Tx Laser Faulty</label>
                                    <label class="checker-item"><input type="checkbox" name="kerusakan[]" value="Rx Laser Faulty"> Rx Laser Faulty</label>
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

                            <div class="alert-box info-alert" style="background: #f8fafc; border: 1px solid #c7d2fe; color: #4f46e5;">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Isi halaman ini sebagai bukti pengembalian</span>
                                <button type="button" class="alert-close" aria-label="Tutup"><i class="bi bi-x"></i></button>
                            </div>

                            <div class="form-card" style="padding-bottom: 24px;">

                                <div class="upload-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">
                                    <div class="upload-title-area">
                                        <label class="form-group" style="margin-bottom: 0; font-size: 16px;">Material Utama <span>*</span></label>
                                        <div class="field-description" style="margin-top: 2px;">Sesuai SN yang diinput pada langkah sebelumnya</div>
                                    </div>
                                    <button type="button" id="tambahSfp" class="tambah-link">
                                        Tambah SFP <i class="bi bi-plus-circle"></i>
                                    </button>
                                </div>

                                <div class="upload-dropzone" id="dropzone">
                                    <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                                    <div class="dropzone-text" id="dropzoneText">Masukkan file disini</div>
                                    <input type="file" id="fileInput" name="foto_material[]" accept="image/*, .pdf" style="display: none;" required>
                                    <button type="button" class="btn-browse" onclick="document.getElementById('fileInput').click()">Browse</button>
                                </div>

                                <div id="sfp-container" style="margin-top: 15px;"></div>

                                <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e2e8f0;">

                                <div class="form-group">
                                    <label for="nama_pemohon">Nama Engineer / Pemohon <span>*</span></label>
                                    <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control" placeholder="Nama Terang Engineer" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Upload Tanda Tangan (Engineer Sign) <span>*</span></label>

                                    <!-- Container yang meniru bentuk form-control -->
                                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 5px; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: #fff;">

                                        <!-- Tombol custom memicu input file hidden -->
                                        <button type="button" class="btn-browse" onclick="this.nextElementSibling.click()" style="padding: 8px 16px; font-size: 13px;">Pilih Foto</button>

                                        <!-- Input file hidden -->
                                        <input type="file" id="ttd_pemohon" name="ttd_pemohon" accept="image/png, image/jpeg, image/jpg" required style="display: none;" onchange="this.nextElementSibling.innerText = this.files.length ? this.files[0].name : 'Belum ada file dipilih'; this.nextElementSibling.style.color = this.files.length ? '#10b981' : '#64748b';">

                                        <!-- Teks indikator nama file -->
                                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Belum ada file dipilih</span>

                                    </div>

                                    <div class="field-description" style="margin-top: 5px;">Unggah foto tanda tangan dengan latar belakang putih/transparan</div>
                                </div>

                                <!-- FORM ACTIONS -->
                                <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; gap: 15px;">
                                    <button type="button" class="btn-preview" id="prevButton" style="background: #e2e8f0; color: #475569; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </button>

                                    <button type="submit" class="btn-submit" style="background: #0284c7; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorList = `{!! implode('<br>', $errors->all()) !!}`;
                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    html: errorList,
                    confirmButtonColor: '#ef4444'
                });

                if (errorList.toLowerCase().includes('foto') || errorList.toLowerCase().includes('tanda tangan')) {
                    document.getElementById('step-1').style.display = 'none';
                    document.getElementById('step-2').style.display = 'block';
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. LOGIKA STATUS KERUSAKAN
            const damageCheckboxes = document.querySelectorAll('input[name="kerusakan[]"]');
            const isMaterialRusakInput = document.getElementById('is_material_rusak');

            damageCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    const isDamaged = Array.from(damageCheckboxes).some(c => c.checked);
                    isMaterialRusakInput.value = isDamaged ? '1' : '0';
                });
            });

            // 2. NAVIGASI STEP
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const nextButton = document.getElementById('nextButton');
            const prevButton = document.getElementById('prevButton');

            nextButton.addEventListener('click', function() {
                step1.style.display = 'none';
                step2.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            prevButton.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 3. LOGIKA DROPZONE
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

            // 4. TAMBAH SFP
            const tambahSfpBtn = document.getElementById('tambahSfp');
            const sfpContainer = document.getElementById('sfp-container');

            tambahSfpBtn.addEventListener('click', function () {
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
                            
                            <input type="file" name="foto_material[]" accept="image/*" required style="display: none;" 
                                onchange="this.previousElementSibling.innerText = this.files.length ? this.files[0].name : 'Masukkan file disini'; this.previousElementSibling.style.color = this.files.length ? '#10b981' : '#64748b';">
                            
                            <button type="button" class="btn-browse" onclick="this.previousElementSibling.click()">Browse</button>
                        </div>
                    </div>
                `;
                sfpContainer.appendChild(sfpBlock);
            });

            // 5. ALERT CLOSE
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