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
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .edit-photo-entry { min-width:0; display:grid; gap:8px; align-content:start; }
        .edit-photo-caption { min-height:38px; resize:vertical; font-size:12px; }
        .edit-new-photo-row input[type=file] { min-width:0; font-size:12px; }
        .edit-photo-add { margin-top:10px; }

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
        .rma-brand-group > .edit-device-heading,
        .rma-brand-group .rma-type-group > .edit-device-heading { justify-content:flex-start; gap:0; }
        .rma-brand-group > .edit-device-heading > .btn-hapus,
        .rma-brand-group .rma-type-group > .edit-device-heading > .btn-hapus { margin-left:12px; }
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
        @media (max-width:640px) {
            .edit-serial-fields { align-items:stretch; }
            .edit-serial-fields .btn-hapus { flex:0 0 auto; }
            .edit-photo-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
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
                                <label for="judul_rma">Nama / Judul Dokumen <span style="color:#64748b;font-weight:400;">(opsional)</span></label>
                                <input type="text" id="judul_rma" name="judul_rma" class="form-control"
                                    placeholder="Kosongkan untuk otomatis: No. Project - Merk"
                                    value="{{ old('judul_rma', $rma->judul_rma ?? $rma->so_po) }}" maxlength="255">
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
                                <label for="customer_name">Customer Name (CPE) <span>*</span></label>
                                <div class="field-description">Isi tanda (-) jika data customer tidak tersedia.</div>
                                <input type="text" id="customer_name" name="customer_name" class="form-control" required
                                    value="{{ old('customer_name', $rma->customer_name ?? '-') }}" placeholder="Nama customer atau -">
                            </div>
                            <div class="form-group">
                                <label>Perangkat <span>*</span></label>
                                @php
                                    $flatTypes = $rma->types->values();
                                    $typeIndexById = $flatTypes->pluck('id')->flip();
                                    $brandGroups = $flatTypes->groupBy('merk');
                                @endphp
                                <div id="edit-type-groups">
                                    @foreach ($brandGroups as $brandIndex => $brandTypes)
                                        <div class="form-card rma-brand-group edit-device-card" data-brand-index="{{ $loop->index }}" style="margin-bottom:14px">
                                            <div class="edit-device-heading"><strong>Merk {{ $loop->iteration }}</strong>@if(!$loop->first)<button type="button" class="btn-hapus remove-brand">Hapus Merk</button>@endif</div>
                                            <div class="form-group"><label>Nama Merk *</label><input class="form-control brand-name" required value="{{ $brandIndex }}" placeholder="Masukkan nama merk"></div>
                                            <div class="brand-type-list">
                                                @foreach ($brandTypes as $type)
                                                    @php $ti = $typeIndexById->get($type->id); @endphp
                                                    <div class="rma-type-group edit-type-item" style="border-top:1px solid #e2e8f0;padding-top:14px;margin-top:14px" data-index="{{ $ti }}" data-next-serial="{{ $type->serials->count() }}">
                                                        <div class="edit-device-heading"><strong>Type {{ $loop->iteration }}</strong>@if($brandTypes->count() > 1)<button type="button" class="btn-hapus remove-brand-type">Hapus Type</button>@endif</div>
                                                        <input type="hidden" class="type-merk" name="types[{{ $ti }}][merk]" value="{{ $type->merk }}">
                                                        <input type="hidden" name="types[{{ $ti }}][id]" value="{{ $type->id }}">
                                                        <div class="form-group"><label>Type *</label><input class="form-control type-name" name="types[{{ $ti }}][type]" required value="{{ $type->type }}"></div>
                                                        <div class="form-group"><label>Material Number *</label><input class="form-control" name="types[{{ $ti }}][material_number]" required value="{{ $type->material_number }}"></div>
                                                        <div class="serial-list"><label class="edit-serial-label">Serial Number (SN) *</label>
                                                            @foreach ($type->serials as $si => $serial)
                                                                <div class="serial-row edit-serial-row" data-serial-index="{{ $si }}">
                                                                    <input type="hidden" name="types[{{ $ti }}][serial_numbers][{{ $si }}][id]" value="{{ $serial->id }}">
                                                                    <div class="edit-serial-fields"><input class="form-control serial-value" name="types[{{ $ti }}][serial_numbers][{{ $si }}][serial_number]" required value="{{ $serial->serial_number }}" aria-label="SN {{ $type->type }}"></div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="tambah-link add-edit-type">+ Tambah Type</button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="field-description">Setiap type memiliki material number dan serial number sendiri. Gunakan Hapus Type untuk menghapus type beserta SN, MN, dan foto terkait.</div>
                                <button type="button" class="tambah-link" id="add-edit-brand">+ Tambah Merk</button>
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
                            <div class="field-description" style="margin-bottom:14px;">Isi keterangan bila diperlukan. Gunakan tombol Hapus Foto untuk menandai foto yang akan dihapus saat disimpan.</div>

                            @if ($rma->materials->count() > 0)
                                <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">
                                    <i class="bi bi-images"></i> Foto Saat Ini ({{ $rma->materials->count() }} foto)
                                    &nbsp;·&nbsp; <span style="color:#dc2626;font-weight:400;">Gunakan tombol Hapus Foto untuk menandai foto</span>
                                </div>
                            @else
                                <p style="color:#94a3b8;font-size:13px;margin-bottom:12px;">Belum ada foto material.
                                </p>
                            @endif
                            <div id="edit-photo-groups">
                            @foreach ($rma->types as $ti => $type)
                                @foreach ($type->serials as $si => $serial)
                                    <div class="form-card edit-photo-group" data-device-index="{{ $ti }}" data-serial-index="{{ $si }}" data-serial-id="{{ $serial->id }}" style="margin:12px 0"><strong>{{ $type->type }} · SN {{ $serial->serial_number }}</strong>
                                        <div class="edit-photo-grid">
                                            @foreach ($serial->materials as $mat)
                                                <div class="edit-photo-entry">
                                                    <div class="edit-photo-item" id="photo-wrap-{{ $mat->id }}" onclick="toggleHapusFoto({{ $mat->id }})">
                                                        <img src="{{ Storage::url($mat->foto_path) }}" alt="Foto Material">
                                                        <div class="edit-photo-overlay"><i class="bi bi-trash3-fill overlay-icon"></i></div>
                                                        <span class="delete-tag">HAPUS</span>
                                                        <input type="checkbox" name="hapus_foto[]" value="{{ $mat->id }}" id="chk-hapus-{{ $mat->id }}" style="display:none;">
                                                    </div>
                                                    <input type="text" class="form-control edit-photo-caption" name="existing_photo_captions[{{ $mat->id }}]" value="{{ old('existing_photo_captions.' . $mat->id, $mat->keterangan) }}" maxlength="1000" placeholder="Keterangan foto (opsional)">
                                                    <button type="button" class="btn-hapus" onclick="event.stopPropagation();toggleHapusFoto({{ $mat->id }})">Hapus Foto</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="edit-new-photo-list"></div>
                                        <button type="button" class="tambah-link edit-photo-add">+ Tambah Foto</button>
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
                                    <label for="role_penanda_tangan">Jabatan Penanda Tangan <span>*</span></label>
                                    @php
                                        $signatureOptions = ['Engineer', 'CA Retail'];
                                        $currentSignature = preg_replace('/\s+Sign$/i', '', old('role_penanda_tangan', $rma->role_penanda_tangan ?: 'Engineer'));
                                        $isCustomSignature = !in_array($currentSignature, $signatureOptions, true);
                                    @endphp
                                    <select id="role_penanda_tangan" name="role_penanda_tangan" class="form-control" required>
                                        @foreach ($signatureOptions as $signatureOption)
                                            <option value="{{ $signatureOption }}" @selected(!$isCustomSignature && $currentSignature === $signatureOption)>{{ $signatureOption }}</option>
                                        @endforeach
                                        @foreach ($signatureRoles as $signatureRole)
                                            @if (!in_array($signatureRole, $signatureOptions, true))
                                                <option value="{{ $signatureRole }}" @selected(!$isCustomSignature && $currentSignature === $signatureRole)>{{ $signatureRole }}</option>
                                            @endif
                                        @endforeach
                                        <option value="Others" @selected($isCustomSignature)>Others</option>
                                    </select>
                                    <input type="text" id="role_penanda_tangan_other" name="role_penanda_tangan_other" class="form-control" style="{{ $isCustomSignature ? '' : 'display:none;' }}margin-top:8px" maxlength="100" value="{{ old('role_penanda_tangan_other', $isCustomSignature ? $currentSignature : '') }}" placeholder="Masukkan nama penanda tangan">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="nama_pemohon">Nama Pemohon <span>*</span></label>
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
                                        Dokumen PDF yang digenerate setelah edit akan menyertakan kolom tanda tangan basah.
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
                            <p>Kosongkan judul untuk membuat otomatis dari No. Project + Merk.</p>
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
        const editPhotoGroups = document.getElementById('edit-photo-groups');
        const signatureRoleSelect = document.getElementById('role_penanda_tangan');
        const signatureRoleOther = document.getElementById('role_penanda_tangan_other');
        function syncSignatureRole() {
            const isOther = signatureRoleSelect.value === 'Others';
            signatureRoleOther.style.display = isOther ? '' : 'none';
            signatureRoleOther.required = isOther;
            if (!isOther) signatureRoleOther.value = '';
        }
        signatureRoleSelect.addEventListener('change', syncSignatureRole);
        syncSignatureRole();
        let editTypeIndex = editGroups ? Math.max(-1, ...Array.from(editGroups.querySelectorAll('.rma-type-group'), group => Number(group.dataset.index))) + 1 : 0;
        function addEditBrand() {
            const index = editGroups.querySelectorAll('.rma-brand-group').length;
            const brand = document.createElement('div');
            brand.className = 'form-card rma-brand-group edit-device-card';
            brand.style.marginBottom = '14px';
            brand.innerHTML = `<div class="edit-device-heading"><strong>Merk ${index + 1}</strong><button type="button" class="btn-hapus remove-brand">Hapus Merk</button></div>
                <div class="form-group"><label>Nama Merk *</label><input class="form-control brand-name" required placeholder="Masukkan nama merk"></div>
                <div class="brand-type-list"></div><button type="button" class="tambah-link add-edit-type">+ Tambah Type</button>`;
            editGroups.appendChild(brand);
            addEditType(brand);
        }
        function addEditType(brand) {
            const ti = editTypeIndex++;
            const card = document.createElement('div');
            card.className = 'rma-type-group edit-type-item';
            card.style.cssText = 'border-top:1px solid #e2e8f0;padding-top:14px;margin-top:14px';
            card.dataset.index = ti;
            card.dataset.nextSerial = '0';
            card.innerHTML = `<div class="edit-device-heading"><strong>Type ${brand.querySelectorAll('.rma-type-group').length + 1}</strong>${brand.querySelectorAll('.rma-type-group').length ? '<button type="button" class="btn-hapus remove-brand-type">Hapus Type</button>' : ''}</div>
                <input type="hidden" class="type-merk" name="types[${ti}][merk]">
                <div class="form-group"><label>Type *</label><input class="form-control type-name" name="types[${ti}][type]" required placeholder="Masukkan type"></div>
                <div class="form-group"><label>Material Number *</label><input class="form-control" name="types[${ti}][material_number]" required placeholder="Masukkan material number"></div>
                <div class="serial-list"><label class="edit-serial-label">Serial Number (SN) *</label></div>`;
            brand.querySelector('.brand-type-list').appendChild(card);
            addEditSerial(card, ti, 0);
        }
        document.getElementById('add-edit-brand')?.addEventListener('click', addEditBrand);
        function addEditSerial(card, ti, si) {
            const row = document.createElement('div'); row.className = 'serial-row edit-serial-row';
            row.dataset.serialIndex = si;
            row.innerHTML = `<div class="edit-serial-fields"><input class="form-control serial-value" name="types[${ti}][serial_numbers][${si}][serial_number]" required placeholder="Masukkan Serial Number" aria-label="Serial Number perangkat ${ti + 1}"></div>`;
            card.querySelector('.serial-list').appendChild(row);
            card.dataset.nextSerial = String(Math.max(Number(card.dataset.nextSerial || 0), si + 1));
            addEditPhotoGroup(card, ti, si, row.querySelector('.serial-value'));
        }

        function renumberEditPhotoRows(group) {
            const serialId = group.dataset.serialId;
            const uploadRoot = serialId ? `new_photos[${serialId}]` : `photos[${group.dataset.deviceIndex}][${group.dataset.serialIndex}]`;
            const captionRoot = serialId ? `new_photo_captions[${serialId}]` : `photo_captions[${group.dataset.deviceIndex}][${group.dataset.serialIndex}]`;
            group.querySelectorAll('.edit-new-photo-row').forEach((row, index) => {
                row.dataset.photoIndex = index;
                row.querySelector('.edit-new-photo-input').name = `${uploadRoot}[${index}]`;
                row.querySelector('.edit-new-photo-caption').name = `${captionRoot}[${index}]`;
            });
        }

        function addEditPhotoRow(group) {
            const row = document.createElement('div');
            row.className = 'edit-new-photo-row';
            const inputId = `edit-rma-photo-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
            row.innerHTML = `<div class="rma-photo-file"><div class="upload-dropzone photo-upload-dropzone"><i class="bi bi-cloud-arrow-up dropzone-icon"></i><div class="dropzone-text">Unggah foto dokumentasi</div><label class="btn-browse" for="${inputId}">Pilih Foto</label><input id="${inputId}" class="edit-new-photo-input" type="file" accept="image/jpeg,image/png,image/jpg,image/webp"><span class="rma-photo-filename">Belum ada foto dipilih</span><img class="rma-photo-preview" alt="Pratinjau foto" hidden></div></div><input class="form-control edit-new-photo-caption" type="text" maxlength="1000" placeholder="Keterangan foto (opsional)"><button type="button" class="btn-hapus remove-edit-photo">Hapus Foto</button>`;
            group.querySelector('.edit-new-photo-list').appendChild(row);
            renumberEditPhotoRows(group);
        }

        function addEditPhotoGroup(card, ti, si, serialInput) {
            const group = document.createElement('div');
            group.className = 'form-card edit-photo-group';
            group.dataset.deviceIndex = ti;
            group.dataset.serialIndex = si;
            group.style.margin = '12px 0';
            group.innerHTML = `<strong></strong><div class="edit-photo-grid"></div><div class="edit-new-photo-list"></div><button type="button" class="tambah-link edit-photo-add">+ Tambah Foto</button>`;
            const updateTitle = () => { group.querySelector('strong').textContent = `${card.querySelector('.type-name').value || 'Tipe perangkat'} · SN ${serialInput.value || 'belum diisi'}`; };
            updateTitle();
            serialInput.addEventListener('input', updateTitle);
            card.querySelector('.type-name').addEventListener('input', updateTitle);
            editPhotoGroups.appendChild(group);
            addEditPhotoRow(group);
        }

        editPhotoGroups?.addEventListener('click', event => {
            const group = event.target.closest('.edit-photo-group');
            if (!group) return;
            if (event.target.closest('.edit-photo-add')) addEditPhotoRow(group);
            if (event.target.closest('.remove-edit-photo')) {
                const row = event.target.closest('.edit-new-photo-row');
                if (row?.querySelector('.edit-new-photo-input')?.dataset.previewUrl) URL.revokeObjectURL(row.querySelector('.edit-new-photo-input').dataset.previewUrl);
                row?.remove();
                renumberEditPhotoRows(group);
            }
        });
        editPhotoGroups?.addEventListener('change', event => {
            if (!event.target.matches('.edit-new-photo-input')) return;
            const row = event.target.closest('.edit-new-photo-row');
            const dropzone = event.target.closest('.photo-upload-dropzone');
            const preview = dropzone.querySelector('.rma-photo-preview');
            const file = event.target.files?.[0];
            if (event.target.dataset.previewUrl) URL.revokeObjectURL(event.target.dataset.previewUrl);
            row.querySelector('.rma-photo-filename').textContent = file?.name || 'Belum ada foto dipilih';
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
        editGroups?.addEventListener('click', e => {
            const card = e.target.closest('.rma-type-group');
            const brand = e.target.closest('.rma-brand-group');
            if (e.target.closest('.remove-brand')) {
                brand.querySelectorAll('.rma-type-group').forEach(type => document.querySelectorAll(`.edit-photo-group[data-device-index="${type.dataset.index}"]`).forEach(group => group.remove()));
                brand.remove(); refreshEditBrandLabels(); return;
            }
            if (e.target.closest('.add-edit-type')) { addEditType(brand); refreshEditBrandLabels(); return; }
            if (e.target.closest('.remove-brand-type')) {
                if (brand.querySelectorAll('.rma-type-group').length > 1) {
                    document.querySelectorAll(`.edit-photo-group[data-device-index="${card.dataset.index}"]`).forEach(group => group.remove());
                    card.remove(); refreshEditBrandLabels();
                }
                return;
            }
        });

        function refreshEditBrandLabels() {
            editGroups.querySelectorAll('.rma-brand-group').forEach((brand, index) => {
                brand.querySelector('.edit-device-heading strong').textContent = `Merk ${index + 1}`;
                brand.querySelectorAll('.rma-type-group').forEach((type, typeIndex) => {
                    type.querySelector('.edit-device-heading strong').textContent = `Type ${typeIndex + 1}`;
                    const removeButton = type.querySelector('.remove-brand-type');
                    if (brand.querySelectorAll('.rma-type-group').length > 1 && !removeButton) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'btn-hapus remove-brand-type';
                        button.textContent = 'Hapus Type';
                        type.querySelector('.edit-device-heading').appendChild(button);
                    } else if (brand.querySelectorAll('.rma-type-group').length === 1 && removeButton) {
                        removeButton.remove();
                    }
                });
                brand.querySelectorAll('.type-merk').forEach(input => input.value = brand.querySelector('.brand-name').value.trim());
            });
        }
        editGroups?.addEventListener('input', event => { if (event.target.matches('.brand-name')) refreshEditBrandLabels(); });
        function normalizeEditTypeNames() {
            refreshEditBrandLabels();
            let typeIndex = 0;
            editGroups.querySelectorAll('.rma-brand-group .rma-type-group').forEach(card => {
                const oldIndex = Number(card.dataset.index);
                const newIndex = typeIndex++;
                card.dataset.index = String(newIndex);
                card.querySelector('.type-merk').name = `types[${newIndex}][merk]`;
                card.querySelector('[name$="[id]"]')?.setAttribute('name', `types[${newIndex}][id]`);
                card.querySelector('.type-name').name = `types[${newIndex}][type]`;
                card.querySelector('[name$="[material_number]"]').name = `types[${newIndex}][material_number]`;
                card.querySelectorAll('.serial-row').forEach((row, serialIndex) => {
                    row.dataset.serialIndex = String(serialIndex);
                    row.querySelector('input[name$="[id]"]')?.setAttribute('name', `types[${newIndex}][serial_numbers][${serialIndex}][id]`);
                    row.querySelector('.serial-value').name = `types[${newIndex}][serial_numbers][${serialIndex}][serial_number]`;
                });
                document.querySelectorAll(`.edit-photo-group[data-device-index="${oldIndex}"]`).forEach(group => {
                    group.dataset.deviceIndex = String(newIndex);
                    if (!group.dataset.serialId) renumberEditPhotoRows(group);
                });
            });
        }
        editGroups?.closest('form')?.addEventListener('submit', normalizeEditTypeNames);

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
