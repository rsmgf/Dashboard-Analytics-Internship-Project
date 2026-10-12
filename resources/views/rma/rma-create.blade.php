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
        /* Desain SFP Item yang lebih bersih */
        .sfp-item {
            margin-top: 25px;
            margin-bottom: 15px;
        }

        /* Tooltip Popover untuk info tombol Gunakan Data Terakhir */
        .tooltip-info-container {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .tooltip-trigger-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            border: none;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .tooltip-trigger-btn:hover, .tooltip-trigger-btn:focus {
            background: #0284c7;
            color: #ffffff;
            outline: none;
        }
        .tooltip-content-box {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 270px;
            background: #0f172a;
            color: #f8fafc;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.5;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-4px);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .tooltip-content-box::before {
            content: '';
            position: absolute;
            bottom: 100%;
            right: 8px;
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent #0f172a transparent;
        }
        .tooltip-info-container:hover .tooltip-content-box,
        .tooltip-info-container:focus-within .tooltip-content-box,
        .tooltip-info-container.active .tooltip-content-box {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
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
                            <div class="form-card">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
                                    <h2 style="margin: 0; font-size: 1.25rem;">Return Material Authorization (RMA)</h2>
                                    
                                    
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <button type="button" id="btnUseLastData" class="btn-use-last"
                                            style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 6px 13px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
                                            <i class="bi bi-clock-history"></i> Gunakan Data Terakhir
                                        </button>
                                        
                                        <div class="tooltip-info-container">
                                            <button type="button" class="tooltip-trigger-btn" aria-label="Penjelasan Gunakan Data Terakhir">
                                                ?
                                            </button>
                                            <div class="tooltip-content-box">
                                                <div style="font-weight: 600; color: #38bdf8; margin-bottom: 3px;">Fungsi Tombol:</div>
                                                Mengisi data umum dari RMA terakhir. Anda juga bisa memilih untuk mengisi merk perangkat; tipe, material number, dan SN tidak disalin.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="judul_rma">Nama / Judul Dokumen <span style="color:#64748b;font-weight:400;">(opsional)</span></label>
                                    <input type="text" id="judul_rma" name="judul_rma" class="form-control"
                                        placeholder="Kosongkan untuk otomatis: No. Project - Merk"
                                        value="{{ old('judul_rma') }}"
                                        maxlength="255">
                                </div>

                                <div class="form-group">
                                    <label for="so_po">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                                    <input type="text" id="so_po" name="so_po" class="form-control"
                                        placeholder="Masukkan nomor projek" required>
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
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_asal">Lokasi asal <span>*</span></label>
                                    <input type="text" id="lokasi_asal" name="lokasi_asal" class="form-control"
                                        placeholder="Masukkan lokasi asal" required>
                                </div>

                                <div class="form-group">
                                    <label for="customer_name">Customer Name (CPE) <span>*</span></label>
                                    <div class="field-description">Isi tanda (-) jika data customer tidak tersedia.</div>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" required
                                        placeholder="Masukkan nama customer atau -">
                                </div>

                                <div class="form-group">
                                    <label>Merk<span>*</span></label>
                                    <div id="type-groups"></div>
                                    <div class="field-description">Tambahkan beberapa type dalam satu merk, atau tambahkan merk lain.</div>
                                    <button type="button" class="tambah-link" id="add-type">+ Tambah Merk</button>
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
                                        <label class="form-group" style="margin-bottom: 0; font-size: 16px;">Foto Material
                                            <span>*</span></label>
                                        <div class="field-description" style="margin-top: 2px;">Pilih foto pada grup SN yang sesuai</div>
                                    </div>
                                </div>
                                <div id="photo-groups"></div>
                                <p style="font-size:0.75rem; color:#94a3b8; margin:6px 0 0;"><i class="bi bi-info-circle"></i> Maks. 2MB per foto, format JPG/PNG/WEBP</p>


                                <!-- PENGESAHAN: NAMA PEMOHON & SUPERVISOR/MANAGER -->
                                <div style="margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label for="role_penanda_tangan">Jabatan Penanda Tangan <span>*</span></label>
                                            <select id="role_penanda_tangan" name="role_penanda_tangan" class="form-control" required>
                                                <option value="Engineer">Engineer</option>
                                                <option value="CA Retail">CA Retail</option>
                                                @foreach ($signatureRoles as $signatureRole)
                                                    @if (!in_array($signatureRole, ['Engineer', 'CA Retail'], true))
                                                        <option value="{{ $signatureRole }}">{{ $signatureRole }}</option>
                                                    @endif
                                                @endforeach
                                                <option value="Others">Others</option>
                                            </select>
                                            <input type="text" id="role_penanda_tangan_other" name="role_penanda_tangan_other" class="form-control" style="display:none;margin-top:8px" maxlength="100" placeholder="Masukkan nama penanda tangan">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label for="nama_pemohon">Nama Pemohon <span>*</span></label>
                                            <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control"
                                                placeholder="Nama Terang Engineer" required>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label for="nama_manager">Supervisor / Manager Name <span>*</span></label>
                                            <input type="text" id="nama_manager" name="nama_manager" class="form-control"
                                                placeholder="Nama Terang Supervisor / Manager" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- INFO TANDA TANGAN BASAH -->
                                <div style="display: flex; align-items: flex-start; gap: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; margin-top: 18px;">
                                    <i class="bi bi-pen-fill" style="font-size: 1.3rem; color: #16a34a; margin-top: 2px;"></i>
                                    <div>
                                        <div style="font-weight: 600; color: #15803d; font-size: 13.5px;">Tanda Tangan Fisik (Basah)</div>
                                        <div style="font-size: 12px; color: #475569; margin-top: 2px; line-height: 1.5;">
                                            Dokumen PDF RMA resmi yang digenerate menyertakan kolom tanda tangan basah setelah dokumen dicetak.
                                        </div>
                                    </div>
                                </div>

                                <!-- FORM ACTIONS -->
                                <div class="form-actions"
                                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; gap: 12px; flex-wrap: wrap;">
                                    <button type="button" class="btn-preview" id="prevButton">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </button>

                                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <button type="button" id="btnSubmitNext" class="btn-submit-next"
                                            style="background: #0284c7; color: #ffffff; border: none; padding: 11px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s;"
                                            title="Simpan RMA ini, lalu form langsung siap untuk input unit berikutnya tanpa perlu mengetik ulang data header">
                                            <i class="bi bi-arrow-repeat" style="font-size: 1.1rem;"></i> Simpan & Input Unit Berikutnya
                                        </button>

                                        <button type="submit" id="btnSubmitFinish" class="btn-submit"
                                            style="display: inline-flex; align-items: center; gap: 7px;">
                                            <i class="bi bi-check2-circle" style="font-size: 1.1rem;"></i> Simpan & Selesai
                                        </button>
                                    </div>
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
            const btnSubmitFinish = document.getElementById('btnSubmitFinish');
            const btnSubmitNext = document.getElementById('btnSubmitNext');
            const signatureRoleSelect = document.getElementById('role_penanda_tangan');
            const signatureRoleOther = document.getElementById('role_penanda_tangan_other');
            const syncSignatureRole = () => {
                const isOther = signatureRoleSelect.value === 'Others';
                signatureRoleOther.style.display = isOther ? '' : 'none';
                signatureRoleOther.required = isOther;
                if (!isOther) signatureRoleOther.value = '';
            };
            signatureRoleSelect.addEventListener('change', syncSignatureRole);
            syncSignatureRole();
            const typeGroups = document.getElementById('type-groups');
            let typeCount = 0;
            let brandCount = 0;
            function addBrandGroup(brandValue = '') {
                const index = brandCount++;
                const group = document.createElement('div');
                group.className = 'form-card rma-brand-group';
                group.dataset.brandIndex = index;
                group.innerHTML = `<div class="edit-device-heading"><strong>Merk ${index + 1}</strong>${index ? '<button type="button" class="btn-hapus remove-brand">Hapus Merk</button>' : ''}</div>
                    <div class="form-group"><label>Nama Merk <span>*</span></label><input class="form-control brand-name" required placeholder="Masukkan nama merk" value=""></div>
                    <div class="brand-type-list"></div><button type="button" class="tambah-link add-brand-type">+ Tambah Type</button>`;
                group.querySelector('.brand-name').value = brandValue;
                typeGroups.appendChild(group);
                addBrandType(group);
                return group;
            }
            function addBrandType(brandGroup, values = {}) {
                const index = typeCount++;
                const card = document.createElement('div');
                card.className = 'rma-type-group';
                card.dataset.typeIndex = index;
                card.innerHTML = `<input type="hidden" class="type-merk" name="types[${index}][merk]">
                    <div class="edit-device-heading"><strong>Type ${brandGroup.querySelectorAll('.rma-type-group').length + 1}</strong>${brandGroup.querySelectorAll('.rma-type-group').length ? '<button type="button" class="btn-hapus remove-brand-type">Hapus Type</button>' : ''}</div>
                    <div class="form-group"><label>Type <span>*</span></label><input class="form-control type-name" name="types[${index}][type]" required placeholder="Masukkan type"></div>
                    <div class="form-group"><label>Material Number <span>*</span></label><input class="form-control" name="types[${index}][material_number]" required placeholder="Masukkan material number"></div>
                    <div class="form-group"><label>Serial Number (SN) <span>*</span></label><input class="form-control serial-value" name="types[${index}][serial_numbers][0]" required placeholder="Masukkan serial number"></div>`;
                card.querySelector('.type-name').value = values.type || '';
                card.querySelector('[name$="[material_number]"]').value = values.material_number || '';
                card.querySelector('.serial-value').value = values.serial_number || '';
                brandGroup.querySelector('.brand-type-list').appendChild(card);
                updateBrandLabels();
                return card;
            }
            document.getElementById('add-type').addEventListener('click', () => addBrandGroup());
            typeGroups.addEventListener('click', e => {
                if (e.target.closest('.remove-brand')) e.target.closest('.rma-brand-group')?.remove();
                if (e.target.closest('.add-brand-type')) addBrandType(e.target.closest('.rma-brand-group'));
                if (e.target.closest('.remove-brand-type')) {
                    const brand = e.target.closest('.rma-brand-group');
                    if (brand.querySelectorAll('.rma-type-group').length > 1) e.target.closest('.rma-type-group').remove();
                }
                saveHeaderCache();
                updateBrandLabels();
            });
            function updateBrandLabels() {
                typeGroups.querySelectorAll('.rma-brand-group').forEach((group, index) => {
                    group.querySelector('.edit-device-heading strong').textContent = `Merk ${index + 1}`;
                    const typeCards = group.querySelectorAll('.rma-type-group');
                    typeCards.forEach((card, typeIndex) => {
                        card.querySelector('.edit-device-heading strong').textContent = `Type ${typeIndex + 1}`;
                        const removeButton = card.querySelector('.remove-brand-type');
                        if (typeCards.length > 1 && !removeButton) {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'btn-hapus remove-brand-type';
                            button.textContent = 'Hapus Type';
                            card.querySelector('.edit-device-heading').appendChild(button);
                        } else if (typeCards.length === 1 && removeButton) {
                            removeButton.remove();
                        }
                    });
                    group.querySelectorAll('.rma-type-group .type-merk').forEach(input => input.value = group.querySelector('.brand-name').value.trim());
                });
            }
            function normalizeCreateTypeNames() {
                let index = 0;
                typeGroups.querySelectorAll('.rma-brand-group .rma-type-group').forEach(card => {
                    const typeIndex = index++;
                    card.dataset.typeIndex = String(typeIndex);
                    card.querySelector('.type-merk').name = `types[${typeIndex}][merk]`;
                    card.querySelector('.type-name').name = `types[${typeIndex}][type]`;
                    card.querySelector('[name$="[material_number]"]').name = `types[${typeIndex}][material_number]`;
                    card.querySelector('.serial-value').name = `types[${typeIndex}][serial_numbers][0]`;
                });
                updateBrandLabels();
            }
            typeGroups.addEventListener('input', event => {
                if (event.target.matches('.brand-name')) updateBrandLabels();
            });
            addBrandGroup();
            updateBrandLabels();
            const btnUseLastData = document.getElementById('btnUseLastData');

            // GUNAKAN DATA TERAKHIR
            const CACHE_KEY = 'rma_header_cache_v3';
            const headerFields = ['judul_rma', 'so_po', 'tanggal', 'lokasi_asal', 'nama_pemohon', 'nama_manager', 'customer_name', 'role_penanda_tangan'];

            function saveHeaderCache() {
                const data = {};
                headerFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) data[id] = el.value;
                });
                const valType = document.querySelector('input[name="valuation_type"]:checked');
                if (valType) data['valuation_type'] = valType.value;
                if (signatureRoleSelect) {
                    data['role_penanda_tangan'] = signatureRoleSelect.value === 'Others'
                        ? signatureRoleOther.value.trim()
                        : signatureRoleSelect.value;
                }
                data['merk_list'] = Array.from(typeGroups.querySelectorAll('.rma-brand-group .brand-name'), input => input.value.trim());
                while (data['merk_list'].length && !data['merk_list'][data['merk_list'].length - 1]) data['merk_list'].pop();
                try {
                    localStorage.setItem(CACHE_KEY, JSON.stringify(data));
                } catch (e) {}
            }

            async function applyLastData() {
                try {
                    const raw = localStorage.getItem(CACHE_KEY);
                    if (!raw) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Belum ada data RMA sebelumnya tersimpan',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        return;
                    }
                    const data = JSON.parse(raw);
                    const choice = await Swal.fire({
                        title: 'Gunakan Data Terakhir',
                        html: '<div style="text-align:left;line-height:1.6;color:#475569">Data umum, jabatan penanda tangan, dan semua merk dari RMA terakhir akan dimuat otomatis.<br><small style="color:#64748b">Type, material number, dan SN perlu diisi kembali untuk perangkat baru.</small></div>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Gunakan Data',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#1688e8',
                        cancelButtonColor: '#64748b',
                    });
                    if (!choice.isConfirmed) return;

                    let filledCount = 0;
                    headerFields.forEach(id => {
                        const el = document.getElementById(id);
                        if (el && data[id]) {
                            el.value = data[id];
                            el.classList.remove('is-invalid');
                            filledCount++;
                        }
                    });
                    if (data['valuation_type']) {
                        const radio = document.querySelector(`input[name="valuation_type"][value="${data['valuation_type']}"]`);
                        if (radio) {
                            radio.checked = true;
                            document.querySelector('.radio-group')?.classList.remove('is-invalid');
                            filledCount++;
                        }
                    }

                    if (data['role_penanda_tangan']) {
                        const roleOptionExists = Array.from(signatureRoleSelect.options).some(option => option.value === data['role_penanda_tangan']);
                        if (roleOptionExists) {
                            signatureRoleSelect.value = data['role_penanda_tangan'];
                            signatureRoleOther.value = '';
                        } else {
                            signatureRoleSelect.value = 'Others';
                            signatureRoleOther.value = data['role_penanda_tangan'];
                        }
                        syncSignatureRole();
                        filledCount++;
                    }

                    if (Array.isArray(data['merk_list'])) {
                        const brands = data['merk_list'].map(merk => String(merk || '').trim()).filter(Boolean);
                        if (brands.length) {
                            typeGroups.innerHTML = '';
                            brands.forEach(merk => addBrandGroup(merk));
                            filledCount += brands.length;
                        }
                        updateBrandLabels();
                    }

                    saveHeaderCache();

                    if (filledCount > 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Data unit terakhir berhasil dimuat! ✨',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                } catch (e) {
                    console.error('Apply last data error:', e);
                }
            }

            if (btnUseLastData) {
                btnUseLastData.addEventListener('click', applyLastData);
            }

            // Simpan cache otomatis saat user mengetik
            headerFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', saveHeaderCache);
                    el.addEventListener('change', saveHeaderCache);
                }
            });
            signatureRoleOther?.addEventListener('input', saveHeaderCache);
            signatureRoleOther?.addEventListener('change', saveHeaderCache);
            typeGroups.addEventListener('input', event => { if (event.target.matches('.brand-name')) saveHeaderCache(); });
            document.querySelectorAll('input[name="valuation_type"]').forEach(radio => {
                radio.addEventListener('change', saveHeaderCache);
            });

            // Toggle info popover pada mobile/klik
            const tooltipTrigger = document.querySelector('.tooltip-trigger-btn');
            const tooltipContainer = document.querySelector('.tooltip-info-container');
            if (tooltipTrigger && tooltipContainer) {
                tooltipTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    tooltipContainer.classList.toggle('active');
                });
                document.addEventListener('click', function(e) {
                    if (!tooltipContainer.contains(e.target)) {
                        tooltipContainer.classList.remove('active');
                    }
                });
            }

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

                const customerName = document.getElementById('customer_name');
                if (!customerName.value.trim()) {
                    customerName.classList.add('is-invalid');
                    errors.push('Customer Name (CPE) wajib diisi. Isi tanda (-) jika tidak ada');
                    isValid = false;
                }

                typeGroups.querySelectorAll('.rma-brand-group').forEach((brand, brandIndex) => {
                    const brandInput = brand.querySelector('.brand-name');
                    if (!brandInput.value.trim()) {
                        brandInput.classList.add('is-invalid');
                        errors.push(`Merk ${brandIndex + 1} wajib diisi`);
                        isValid = false;
                    }
                    brand.querySelectorAll('.rma-type-group').forEach((card, typeIndex) => {
                        [['.type-name', 'Type'], ['[name$="[material_number]"]', 'Material Number'], ['.serial-value', 'Serial Number']].forEach(([selector, label]) => {
                            const input = card.querySelector(selector);
                            if (!input.value.trim()) {
                                input.classList.add('is-invalid');
                                errors.push(`${label} pada Merk ${brandIndex + 1}, Type ${typeIndex + 1} wajib diisi`);
                                isValid = false;
                            }
                        });
                    });
                });
                if (typeGroups.querySelectorAll('.rma-type-group').length === 0) { errors.push('Tambahkan minimal satu type perangkat'); isValid = false; }

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

                normalizeCreateTypeNames();
                step1.style.display = 'none';
                step2.style.display = 'block';
                const photoGroups = document.getElementById('photo-groups');
                photoGroups.innerHTML = '';
                function renumberPhotoRows(group) {
                    const typeIndex = Number(group.dataset.typeIndex);
                    group.querySelectorAll('.rma-photo-row').forEach((row, photoIndex) => {
                        row.querySelector('.rma-photo-input').name = `photos[${typeIndex}][0][${photoIndex}]`;
                        row.querySelector('.rma-photo-caption').name = `photo_captions[${typeIndex}][0][${photoIndex}]`;
                    });
                }
                function addPhotoRow(group) {
                    const row = document.createElement('div');
                    row.className = 'rma-photo-row';
                    const inputId = `rma-photo-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
                    row.innerHTML = `<div class="rma-photo-file"><div class="upload-dropzone photo-upload-dropzone"><i class="bi bi-cloud-arrow-up dropzone-icon"></i><div class="dropzone-text">Unggah foto dokumentasi</div><label class="btn-browse" for="${inputId}">Pilih Foto</label><input id="${inputId}" class="rma-photo-input" type="file" accept="image/jpeg,image/png,image/jpg,image/webp"><span class="rma-photo-filename">Belum ada foto dipilih</span><img class="rma-photo-preview" alt="Pratinjau foto" hidden></div></div><input class="form-control rma-photo-caption" type="text" maxlength="1000" placeholder="Keterangan foto (opsional)"><button type="button" class="btn-hapus remove-rma-photo">Hapus Foto</button>`;
                    group.querySelector('.rma-photo-list').appendChild(row);
                    renumberPhotoRows(group);
                }
                if (photoGroups.dataset.eventsBound !== 'true') {
                    photoGroups.addEventListener('click', event => {
                        const group = event.target.closest('.rma-photo-group');
                        if (!group) return;
                        if (event.target.closest('.add-rma-photo')) addPhotoRow(group);
                        if (event.target.closest('.remove-rma-photo')) {
                            const row = event.target.closest('.rma-photo-row');
                            if (row?.querySelector('.rma-photo-input')?.dataset.previewUrl) URL.revokeObjectURL(row.querySelector('.rma-photo-input').dataset.previewUrl);
                            row?.remove();
                            renumberPhotoRows(group);
                        }
                    });
                    photoGroups.addEventListener('change', event => {
                        if (!event.target.matches('.rma-photo-input')) return;
                        const file = event.target.files?.[0];
                        const dropzone = event.target.closest('.photo-upload-dropzone');
                        const preview = dropzone.querySelector('.rma-photo-preview');
                        if (event.target.dataset.previewUrl) URL.revokeObjectURL(event.target.dataset.previewUrl);
                        dropzone.querySelector('.rma-photo-filename').textContent = file?.name || 'Belum ada foto dipilih';
                        if (file) {
                            event.target.dataset.previewUrl = URL.createObjectURL(file);
                            preview.src = event.target.dataset.previewUrl;
                            preview.hidden = false;
                            dropzone.classList.add('has-preview');
                        } else {
                            delete event.target.dataset.previewUrl;
                            preview.removeAttribute('src');
                            preview.hidden = true;
                            dropzone.classList.remove('has-preview');
                        }
                    });
                    photoGroups.dataset.eventsBound = 'true';
                }
                typeGroups.querySelectorAll('.rma-type-group').forEach(card => {
                    const ti = Number(card.dataset.typeIndex);
                    const typeName = card.querySelector('.type-name').value;
                    const sn = card.querySelector('.serial-value');
                    const group = document.createElement('div'); group.className = 'form-card rma-photo-group';
                    group.dataset.typeIndex = ti;
                    group.innerHTML = `<strong></strong><div class="rma-photo-list"></div><button type="button" class="tambah-link add-rma-photo">+ Tambah Foto</button>`;
                    group.querySelector('strong').textContent = `${card.querySelector('.type-merk').value} · ${typeName} · SN ${sn.value}`;
                    photoGroups.appendChild(group);
                    addPhotoRow(group);
                });
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            prevButton.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 3. LOGIKA SUBMIT (DUA MODE: 'finish' ATAU 'next')
            let currentSubmitMode = 'finish';

            btnSubmitNext.addEventListener('click', function() {
                currentSubmitMode = 'next';
                form.requestSubmit();
            });

            btnSubmitFinish.addEventListener('click', function() {
                currentSubmitMode = 'finish';
            });

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

                const missingPhotoGroups = Array.from(document.querySelectorAll('#photo-groups .rma-photo-group'))
                    .map((group, index) => Array.from(group.querySelectorAll('.rma-photo-input')).some(input => input.files.length) ? null : index + 1).filter(Boolean);
                if (missingPhotoGroups.length) {
                    const missingList = missingPhotoGroups.map(index => `<li>Foto untuk type ke-${index} wajib diunggah</li>`).join('');
                    Swal.fire({ icon: 'warning', title: 'Foto Material Belum Lengkap', html: `<ul style="text-align:left;line-height:1.7">${missingList}</ul>`, confirmButtonColor: '#0066DC' });
                    return;
                }


                // Cek Nama Engineer / Pemohon
                const namaPemohon = document.getElementById('nama_pemohon');
                if (!namaPemohon.value.trim()) {
                    namaPemohon.classList.add('is-invalid');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nama Engineer Belum Diisi',
                        text: 'Silakan isi Nama Engineer / Pemohon pada kolom pengesahan.',
                        confirmButtonColor: '#0066DC'
                    }).then(() => {
                        namaPemohon.focus();
                    });
                    return;
                }

                // Cek Supervisor / Manager Name
                const namaManager = document.getElementById('nama_manager');
                if (!namaManager.value.trim()) {
                    namaManager.classList.add('is-invalid');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nama Supervisor/Manager Belum Diisi',
                        text: 'Silakan isi Supervisor / Manager Name pada kolom pengesahan.',
                        confirmButtonColor: '#0066DC'
                    }).then(() => {
                        namaManager.focus();
                    });
                    return;
                }

                btnSubmitFinish.disabled = true;
                btnSubmitNext.disabled = true;
                const activeBtn = currentSubmitMode === 'next' ? btnSubmitNext : btnSubmitFinish;
                const originalText = activeBtn.innerHTML;
                activeBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';

                // Bersihkan is-invalid lama
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                // Simpan cache header terbaru
                saveHeaderCache();

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

                    let responseData = null;
                    try {
                        responseData = await response.json();
                    } catch (jsonErr) {}

                    if (response.ok && responseData) {
                        // Tangani berdasarkan mode (TIDAK ADA POP-UP TAB PDF LAGI)
                        if (currentSubmitMode === 'next') {
                            // Reset field khusus unit
                            ['description', 'alasan'].forEach(id => {
                                const el = document.getElementById(id);
                                if (el) el.value = '';
                            });
                            typeGroups.innerHTML = '';
                            typeCount = 0;
                            addTypeGroup();
                            
                            // Reset checkboxes kerusakan
                            if (typeof damageCheckboxes !== 'undefined' && damageCheckboxes) {
                                damageCheckboxes.forEach(cb => { cb.checked = false; });
                            }
                            if (isMaterialRusakInput) isMaterialRusakInput.value = '0';

                            // Reset file foto utama
                document.getElementById('photo-groups').innerHTML = '';

                            // Kembali ke Langkah 1 dengan halus
                            step2.style.display = 'none';
                            step1.style.display = 'block';
                            window.scrollTo({ top: 0, behavior: 'smooth' });

                            // Notifikasi toast sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'RMA Berhasil Disimpan! 🎉',
                                text: 'Data unit telah disimpan. Form langsung siap untuk unit berikutnya.',
                                confirmButtonColor: '#0284c7',
                                confirmButtonText: 'Lanjut Isi Unit Berikutnya'
                            }).then(() => {
                                typeGroups.querySelector('.serial-value')?.focus();
                            });
                            return;
                        } else {
                            // Mode Selesai -> Langsung redirect ke daftar riwayat RMA
                            window.location.href = responseData.redirect_url;
                            return;
                        }
                    }

                    // Penanganan Error Berdasarkan Status Code
                    if (response.status === 422 && responseData) {
                        const errorKeys = Object.keys(responseData.errors || {});
                        const messages = Object.values(responseData.errors || {}).flat();

                        const fieldMap = {
                            'judul_rma': 'judul_rma',
                            'so_po': 'so_po',
                            'tanggal': 'tanggal',
                            'lokasi_asal': 'lokasi_asal',
                            'customer_name': 'customer_name',
                            'nama_manager': 'nama_manager',
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
                            if (k.startsWith('photos')) {
                                document.getElementById('photo-groups')?.classList.add('is-invalid');
                                hasStep2Error = true;
                            }
                            if (['judul_rma','so_po','valuation_type','tanggal','lokasi_asal','customer_name','description'].includes(k) || k.startsWith('types')) {
                                hasStep1Error = true;
                            }
                            if (k.startsWith('photos') || k === 'nama_pemohon' || k === 'nama_manager') {
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
                    btnSubmitFinish.disabled = false;
                    btnSubmitNext.disabled = false;
                    activeBtn.innerHTML = originalText;
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
