<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit RMA #{{ $rma->id }} - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/sidebar.css', 'resources/css/rma.css'])
    <style>
        .edit-photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .edit-photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            aspect-ratio: 1;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .edit-photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .edit-photo-item.marked-delete {
            border-color: #dc2626;
            opacity: 0.5;
        }

        .edit-photo-overlay {
            position: absolute;
            inset: 0;
            background: rgba(220, 38, 38, 0);
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-photo-item:hover .edit-photo-overlay {
            background: rgba(220, 38, 38, 0.35);
        }

        .edit-photo-item.marked-delete .edit-photo-overlay {
            background: rgba(220, 38, 38, 0.15);
        }

        .overlay-icon {
            font-size: 20px;
            color: white;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .edit-photo-item:hover .overlay-icon {
            opacity: 1;
        }

        .delete-tag {
            position: absolute;
            top: 4px;
            left: 4px;
            background: #dc2626;
            color: white;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            display: none;
        }

        .edit-photo-item.marked-delete .delete-tag {
            display: block;
        }

        .new-photos-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .new-photo-thumb {
            position: relative;
            width: 72px;
            height: 72px;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid #22c55e;
        }

        .new-photo-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-new-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 9px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-device-card { padding: 22px; border: 1px solid #dbeafe; background: #fbfdff; }
        .edit-device-heading { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px; }
        .edit-device-heading strong { color:#1e3a8a; font-size:15px; }
        .edit-serial-row { padding:14px; margin:12px 0; border:1px solid #e2e8f0; border-radius:10px; background:#fff; }
        .edit-serial-fields { display:flex; align-items:center; gap:10px; }
        .edit-serial-fields .form-control { flex:1; min-width:0; }
        .edit-serial-upload { margin:12px 0 0; padding:16px; cursor:pointer; }
        .edit-serial-upload .dropzone-icon { font-size:24px; margin-bottom:4px; }
        .edit-serial-upload .dropzone-text { margin-bottom:8px; font-size:12px; }
        .edit-serial-upload .btn-browse { padding:6px 16px; font-size:12px; }
        .edit-serial-fields .btn-hapus { white-space:nowrap; }
        .edit-serial-label { display:block; margin:0 0 8px; color:#334155; font-size:13px; font-weight:600; }
        @media (max-width:640px) { .edit-serial-fields { align-items:stretch; } .edit-serial-fields .btn-hapus { flex:0 0 auto; } }
    </style>
</head>

<body>
    <div class="app-container">
        <x-sidebar active="rma" />
        <main class="main-content">
            <x-topbar />
            <div class="rma-layout">
                <div class="rma-form-area">

                    <!-- Header -->
                    <div style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
                        <a href="{{ route('rma') }}"
                            style="width:34px;height:34px;border-radius:50%;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <x-breadcrumb :items="[['label' => 'RMA', 'route' => 'rma'], ['label' => 'Edit RMA #' . $rma->id]]" />
                            <p style="font-size:0.8rem;color:#64748b;margin:2px 0 0;">
                                {{ $rma->judul_rma ?? 'Edit Dokumen RMA' }}</p>
                        </div>
                    </div>

                    <!-- Errors -->
                    @if ($errors->any())
                        <div
                            style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
                            <ul style="margin:0;padding-left:18px;color:#dc2626;font-size:13px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rma.update', $rma->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- DATA MATERIAL -->
                        <div class="form-card">
                            <h2
                                style="margin:0 0 20px;font-size:1.1rem;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                                <i class="bi bi-pencil-square" style="color:#2563eb;margin-right:8px;"></i>Edit Data
                                Material
                            </h2>

                            <div class="form-group">
                                <label for="judul_rma">Nama / Judul Dokumen <span
                                        style="font-weight:400;color:#64748b;font-size:12px;">(opsional)</span></label>
                                <input type="text" id="judul_rma" name="judul_rma" class="form-control"
                                    placeholder="Contoh: RMA PO-12345 - POP Jakarta Pusat"
                                    value="{{ old('judul_rma', $rma->judul_rma) }}" maxlength="150">
                                <div class="field-description">Biarkan kosong untuk menggunakan No. IO.SP2K/SO/PO/ANDOP sebagai nama dokumen.</div>
                            </div>
                            <div class="form-group">
                                <label for="so_po">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                                <input type="text" id="so_po" name="so_po" class="form-control" required
                                    value="{{ old('so_po', $rma->so_po) }}" placeholder="Masukkan nomor dokumen">
                            </div>
                            <div class="form-group">
                                <label>Valuation Type <span>*</span></label>
                                <div class="radio-group">
                                    @foreach (['ex-project' => 'Ex-Project', 'dismantle' => 'Dismantle', 'rusak-L' => 'Rusak-L', 'rusak-TL' => 'Rusak-TL'] as $val => $lbl)
                                        <label class="radio-option">
                                            <input type="radio" name="valuation_type" value="{{ $val }}"
                                                required
                                                {{ old('valuation_type', $rma->valuation_type) === $val ? 'checked' : '' }}>
                                            <span>{{ $lbl }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal <span>*</span></label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control" required
                                    value="{{ old('tanggal', $rma->tanggal ? $rma->tanggal->format('Y-m-d') : '') }}">
                            </div>
                            <div class="form-group">
                                <label for="lokasi_asal">Lokasi asal <span>*</span></label>
                                <input type="text" id="lokasi_asal" name="lokasi_asal" class="form-control" required
                                    value="{{ old('lokasi_asal', $rma->lokasi_asal) }}"
                                    placeholder="Masukkan lokasi asal">
                            </div>
                            <div class="form-group">
                                <label>Perangkat <span>*</span></label>
                                <div id="edit-type-groups">
                                    @foreach ($rma->types as $ti => $type)
                                        <div class="form-card rma-type-group edit-device-card" style="margin-bottom:14px" data-next-serial="{{ $type->serials->count() }}" data-index="{{ $ti }}">
                                            <div class="edit-device-heading"><strong><i class="bi bi-hdd-stack" style="margin-right:6px"></i>Perangkat {{ $ti + 1 }}</strong>@if($ti > 0)<button type="button" class="btn-hapus remove-type">Hapus perangkat</button>@endif</div>
                                            <input type="hidden" name="types[{{ $ti }}][id]" value="{{ $type->id }}">
                                            <div class="form-group"><label>Merk *</label><input class="form-control" name="types[{{ $ti }}][merk]" required value="{{ $type->merk }}"></div>
                                            <div class="form-group"><label>Tipe *</label><input class="form-control type-name" name="types[{{ $ti }}][type]" required value="{{ $type->type }}"></div>
                                            <div class="form-group"><label>Material Number *</label><input class="form-control" name="types[{{ $ti }}][material_number]" required value="{{ $type->material_number }}"></div>
                                            <div class="serial-list">
                                                <label class="edit-serial-label">Serial Number (SN) <span>*</span></label>
                                                @foreach ($type->serials as $si => $serial)
                                                    <div class="serial-row edit-serial-row" data-serial-index="{{ $si }}">
                                                        <input type="hidden" name="types[{{ $ti }}][serial_numbers][{{ $si }}][id]" value="{{ $serial->id }}">
                                                        <div class="edit-serial-fields"><input class="form-control" name="types[{{ $ti }}][serial_numbers][{{ $si }}][serial_number]" required value="{{ $serial->serial_number }}" aria-label="Serial Number perangkat {{ $ti + 1 }}"><button type="button" class="btn-hapus remove-serial">Hapus SN</button></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="tambah-link add-serial">+ Tambah SN</button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="field-description">Satu perangkat mewakili satu merk, tipe, dan material number. Tambahkan SN jika unitnya lebih dari satu. Untuk tipe berbeda, tambahkan perangkat baru; merk boleh sama.</div>
                                <button type="button" class="tambah-link" id="add-edit-type">+ Tambah perangkat</button>
                            </div>
                            <div class="form-group">
                                <label for="description">Description <span>*</span></label>
                                <textarea id="description" name="description" class="form-control" required
                                    placeholder="Deskripsikan kondisi secara singkat...">{{ old('description', $rma->description) }}</textarea>
                            </div>
                        </div>

                        <!-- KERUSAKAN -->
                        <div class="form-card">
                            @php $kerusakanLama = old('kerusakan', $rma->kerusakan ?? []); @endphp
                            <input type="hidden" name="is_material_rusak" id="is_material_rusak"
                                value="{{ old('is_material_rusak', $rma->is_material_rusak ? '1' : '0') }}">
                            <div class="alert-box warning-alert" style="margin-bottom:16px;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Beri tanda checklist pada kotak jika material rusak</span>
                            </div>
                            <div class="checker-grid">
                                @foreach (['Dead on Arrival', 'Physical Damage', 'Dead on Operational', 'Miscelaneous', 'BER Indication', 'Intermittent', 'Software Error', 'Rectifier faulty', 'Channel Error', 'Charging switch', 'Port Error', 'Battery faulty', 'Tx Laser Faulty', 'Rx Laser Faulty'] as $item)
                                    <label class="checker-item">
                                        <input type="checkbox" name="kerusakan[]" value="{{ $item }}"
                                            {{ in_array($item, (array) $kerusakanLama) ? 'checked' : '' }}>
                                        {{ $item }}
                                    </label>
                                @endforeach
                            </div>
                            <div class="form-group" style="margin-top:24px;">
                                <label for="alasan">Alasan Tambahan</label>
                                <div class="field-description">Opsional</div>
                                <textarea id="alasan" name="alasan" class="form-control" placeholder="Tuliskan alasan tambahan bila ada...">{{ old('alasan', $rma->alasan) }}</textarea>
                            </div>
                        </div>

                        <!-- FOTO MATERIAL -->
                        <div class="form-card" style="padding-bottom:24px;">
                            <h3 style="font-size:1rem;margin:0 0 4px;color:#1e293b;">Foto Material</h3>
                            <div class="field-description" style="margin-bottom:14px;">Klik foto untuk menandai hapus.
                                Foto ditandai akan dihapus saat disimpan.</div>

                            @if ($rma->materials->count() > 0)
                                <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">
                                    <i class="bi bi-images"></i> Foto Saat Ini ({{ $rma->materials->count() }} foto)
                                    &nbsp;·&nbsp; <span style="color:#dc2626;font-weight:400;">Klik foto untuk tandai
                                        hapus</span>
                                </div>
                            @else
                                <p style="color:#94a3b8;font-size:13px;margin-bottom:12px;">Belum ada foto material.
                                </p>
                            @endif
                            <div id="edit-photo-groups">
                            @foreach ($rma->types as $ti => $type)
                                @foreach ($type->serials as $si => $serial)
                                    <div class="form-card edit-photo-group" data-device-index="{{ $ti }}" data-serial-index="{{ $si }}" style="margin:12px 0"><strong>{{ $type->type }} · SN {{ $serial->serial_number }}</strong>
                                        <div class="edit-photo-grid">
                                            @foreach ($serial->materials as $mat)
                                                <div class="edit-photo-item" id="photo-wrap-{{ $mat->id }}" onclick="toggleHapusFoto({{ $mat->id }})">
                                                    <img src="{{ Storage::url($mat->foto_path) }}" alt="Foto Material">
                                                    <div class="edit-photo-overlay"><i class="bi bi-trash3-fill overlay-icon"></i></div>
                                                    <span class="delete-tag">HAPUS</span>
                                                    <input type="checkbox" name="hapus_foto[]" value="{{ $mat->id }}" id="chk-hapus-{{ $mat->id }}" style="display:none;">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="upload-dropzone edit-serial-upload" style="margin-top:12px;margin-bottom:0" onclick="if(event.target===this||event.target.closest('.dropzone-icon,.dropzone-text'))this.querySelector('input[type=file]').click()"><i class="bi bi-cloud-arrow-up dropzone-icon"></i><div class="dropzone-text">Tambah foto material (opsional)</div><input type="file" name="new_photos[{{ $serial->id }}][]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none"><button type="button" class="btn-browse" onclick="event.stopPropagation();this.parentElement.querySelector('input[type=file]').click()">Pilih foto</button></div>
                                    </div>
                                @endforeach
                            @endforeach
                            </div>

                        </div>

                        <!-- PENGESAHAN -->
                        <div class="form-card">
                            <h3 style="font-size:1rem;margin:0 0 16px;color:#1e293b;">Data Pengesahan</h3>
                            <div
                                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="nama_pemohon">Nama Engineer / Pemohon <span>*</span></label>
                                    <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control"
                                        required value="{{ old('nama_pemohon', $rma->nama_pemohon) }}"
                                        placeholder="Nama Terang Engineer">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="nama_manager">Supervisor / Manager Name <span>*</span></label>
                                    <input type="text" id="nama_manager" name="nama_manager" class="form-control"
                                        required value="{{ old('nama_manager', $rma->nama_manager) }}"
                                        placeholder="Nama Supervisor / Manager">
                                </div>
                            </div>

                            <div
                                style="display:flex;align-items:flex-start;gap:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-top:18px;">
                                <i class="bi bi-pen-fill" style="font-size:1.3rem;color:#16a34a;margin-top:2px;"></i>
                                <div>
                                    <div style="font-weight:600;color:#15803d;font-size:13.5px;">Tanda Tangan Fisik
                                        (Basah)</div>
                                    <div style="font-size:12px;color:#475569;margin-top:2px;line-height:1.5;">
                                        Dokumen PDF yang digenerate setelah edit akan tetap menyertakan kolom tanda
                                        tangan basah.
                                    </div>
                                </div>
                            </div>

                            <div
                                style="display:flex;justify-content:space-between;align-items:center;margin-top:24px;gap:12px;flex-wrap:wrap;">
                                <a href="{{ route('rma') }}"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;text-decoration:none;font-size:13px;font-weight:500;transition:0.2s;">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn-submit"
                                    style="display:inline-flex;align-items:center;gap:7px;">
                                    <i class="bi bi-check2-circle" style="font-size:1.1rem;"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- NOTE ASIDE -->
                <aside class="note-card">
                    <h3>Catatan Edit</h3>
                    <div class="note-list">
                        <div class="note-item"><span>Foto Dihapus</span>
                            <p>Klik pada foto yang ingin dihapus. Foto bertanda merah akan dihapus permanen saat
                                disimpan.</p>
                        </div>
                        <div class="note-item"><span>Foto Baru</span>
                            <p>Upload foto tambahan via Browse. Foto lama yang tidak ditandai tetap tersimpan.</p>
                        </div>
                        <div class="note-item"><span>Judul Dokumen</span>
                            <p>Kosongkan judul untuk auto-generate dari Merk + Lokasi Asal.</p>
                        </div>
                        <div class="note-item"><span>PDF Diperbarui</span>
                            <p>Setelah disimpan, PDF yang didownload akan mencerminkan data terbaru.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const editGroups = document.getElementById('edit-type-groups');
        let editTypeIndex = editGroups ? Math.max(-1, ...Array.from(editGroups.querySelectorAll('.rma-type-group'), group => Number(group.dataset.index))) + 1 : 0;
        document.getElementById('add-edit-type')?.addEventListener('click', () => {
            const ti = editTypeIndex++;
            const card = document.createElement('div'); card.className = 'form-card rma-type-group edit-device-card'; card.style.marginBottom = '14px';
            card.dataset.index = ti; card.dataset.nextSerial = '0';
            card.innerHTML = `<div class="edit-device-heading"><strong><i class="bi bi-hdd-stack" style="margin-right:6px"></i>Perangkat ${ti + 1}</strong><button type="button" class="btn-hapus remove-type">Hapus perangkat</button></div>
                <div class="form-group"><label>Merk *</label><input class="form-control" name="types[${ti}][merk]" required></div>
                <div class="form-group"><label>Tipe *</label><input class="form-control type-name" name="types[${ti}][type]" required></div>
                <div class="form-group"><label>Material Number *</label><input class="form-control" name="types[${ti}][material_number]" required></div>
                <div class="serial-list"><label class="edit-serial-label">Serial Number (SN) <span>*</span></label></div><button type="button" class="tambah-link add-serial">+ Tambah SN</button>`;
            editGroups.appendChild(card); addEditSerial(card, ti, 0);
        });
        function addEditSerial(card, ti, si) {
            const row = document.createElement('div'); row.className = 'serial-row edit-serial-row';
            row.dataset.serialIndex = si;
            row.innerHTML = `<div class="edit-serial-fields"><input class="form-control serial-value" name="types[${ti}][serial_numbers][${si}][serial_number]" required placeholder="Masukkan Serial Number" aria-label="Serial Number perangkat ${ti + 1}"><button type="button" class="btn-hapus remove-serial">Hapus SN</button></div>`;
            card.querySelector('.serial-list').appendChild(row);
            card.dataset.nextSerial = String(Math.max(Number(card.dataset.nextSerial || 0), si + 1));
            addEditPhotoGroup(card, ti, si, row.querySelector('.serial-value'));
        }
        function addEditPhotoGroup(card, ti, si, serialInput) {
            const group = document.createElement('div');
            group.className = 'form-card edit-photo-group';
            group.dataset.deviceIndex = ti;
            group.dataset.serialIndex = si;
            group.style.margin = '12px 0';
            group.innerHTML = `<strong></strong><div class="edit-photo-grid"></div><div class="upload-dropzone edit-serial-upload" style="margin-top:12px;margin-bottom:0" onclick="if(event.target===this||event.target.closest('.dropzone-icon,.dropzone-text'))this.querySelector('input[type=file]').click()"><i class="bi bi-cloud-arrow-up dropzone-icon"></i><div class="dropzone-text">Tambah foto material (opsional)</div><input type="file" name="photos[${ti}][${si}][]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none"><button type="button" class="btn-browse" onclick="event.stopPropagation();this.parentElement.querySelector('input[type=file]').click()">Pilih foto</button></div>`;
            const updateTitle = () => { group.querySelector('strong').textContent = `${card.querySelector('.type-name').value || 'Tipe perangkat'} · SN ${serialInput.value || 'belum diisi'}`; };
            updateTitle();
            serialInput.addEventListener('input', updateTitle);
            card.querySelector('.type-name').addEventListener('input', updateTitle);
            document.getElementById('edit-photo-groups').appendChild(group);
        }
        editGroups?.addEventListener('click', e => {
            const card = e.target.closest('.rma-type-group');
            if (e.target.closest('.remove-type')) {
                document.querySelectorAll(`.edit-photo-group[data-device-index="${card.dataset.index}"]`).forEach(group => group.remove());
                card.remove(); return;
            }
            if (e.target.closest('.add-serial')) {
                const ti = Number(card.dataset.index);
                addEditSerial(card, ti, Number(card.dataset.nextSerial || 0));
            }
            if (e.target.closest('.remove-serial') && card.querySelectorAll('.serial-row').length > 1) {
                const row = e.target.closest('.serial-row');
                document.querySelector(`.edit-photo-group[data-device-index="${card.dataset.index}"][data-serial-index="${row.dataset.serialIndex}"]`)?.remove();
                row.remove();
            }
        });

        function toggleHapusFoto(matId) {
            const wrap = document.getElementById('photo-wrap-' + matId);
            const chk = document.getElementById('chk-hapus-' + matId);
            if (!wrap || !chk) return;
            wrap.classList.toggle('marked-delete');
            chk.checked = wrap.classList.contains('marked-delete');
        }

        document.addEventListener('change', event => {
            const input = event.target.closest('.upload-dropzone input[type="file"]');
            if (input) {
                const dropzone = input.closest('.upload-dropzone');
                const label = dropzone?.querySelector('.dropzone-text');
                const hasFiles = input.files.length > 0;
                if (label && hasFiles) label.textContent = input.files.length + ' foto dipilih';
                if (dropzone) {
                    dropzone.style.borderColor = hasFiles ? '#16a34a' : '';
                    dropzone.style.backgroundColor = hasFiles ? '#f0fdf4' : '';
                }
            }
        });

        // Auto-sync is_material_rusak berdasarkan checkbox kerusakan
        const isMaterialRusakInput = document.getElementById('is_material_rusak');
        const kerusakanCheckboxes = document.querySelectorAll('input[name="kerusakan[]"]');

        function syncMaterialRusak() {
            if (!isMaterialRusakInput) return;
            const anyChecked = Array.from(kerusakanCheckboxes).some(cb => cb.checked);
            isMaterialRusakInput.value = anyChecked ? '1' : '0';
        }

        kerusakanCheckboxes.forEach(cb => cb.addEventListener('change', syncMaterialRusak));
        syncMaterialRusak();
    </script>
</body>

</html>
