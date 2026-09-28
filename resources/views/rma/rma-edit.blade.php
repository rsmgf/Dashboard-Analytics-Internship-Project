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
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .edit-photo-item.marked-delete {
            border-color: #dc2626;
            opacity: 0.5;
        }
        .edit-photo-overlay {
            position: absolute; inset: 0;
            background: rgba(220,38,38,0);
            transition: background 0.2s;
            display: flex; align-items: center; justify-content: center;
        }
        .edit-photo-item:hover .edit-photo-overlay { background: rgba(220,38,38,0.35); }
        .edit-photo-item.marked-delete .edit-photo-overlay { background: rgba(220,38,38,0.15); }
        .overlay-icon { font-size: 20px; color: white; opacity: 0; transition: opacity 0.2s; }
        .edit-photo-item:hover .overlay-icon { opacity: 1; }
        .delete-tag {
            position: absolute; top: 4px; left: 4px;
            background: #dc2626; color: white; font-size: 9px; font-weight: 700;
            padding: 1px 6px; border-radius: 4px; display: none;
        }
        .edit-photo-item.marked-delete .delete-tag { display: block; }
        .new-photos-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .new-photo-thumb {
            position: relative; width: 72px; height: 72px;
            border-radius: 6px; overflow: hidden; border: 2px solid #22c55e;
        }
        .new-photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .remove-new-btn {
            position: absolute; top: 2px; right: 2px;
            background: #dc2626; color: white; border: none;
            border-radius: 50%; width: 16px; height: 16px;
            font-size: 9px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
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
                    <a href="{{ route('rma') }}" style="width:34px;height:34px;border-radius:50%;background:#f1f5f9;color:#64748b;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <x-breadcrumb :items="[['label'=>'RMA','route'=>'rma'],['label'=>'Edit RMA #'.$rma->id]]" />
                        <p style="font-size:0.8rem;color:#64748b;margin:2px 0 0;">{{ $rma->judul_rma ?? 'Edit Dokumen RMA' }}</p>
                    </div>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
                        <ul style="margin:0;padding-left:18px;color:#dc2626;font-size:13px;">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('rma.update', $rma->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- DATA MATERIAL -->
                    <div class="form-card">
                        <h2 style="margin:0 0 20px;font-size:1.1rem;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                            <i class="bi bi-pencil-square" style="color:#2563eb;margin-right:8px;"></i>Edit Data Material
                        </h2>

                        <div class="form-group">
                            <label for="judul_rma">Nama / Judul Dokumen <span style="font-weight:400;color:#64748b;font-size:12px;">(opsional)</span></label>
                            <input type="text" id="judul_rma" name="judul_rma" class="form-control"
                                placeholder="Contoh: RMA Router Cisco - POP Jakarta Pusat"
                                value="{{ old('judul_rma', $rma->judul_rma) }}" maxlength="150">
                            <div class="field-description">Biarkan kosong untuk generate otomatis dari Merk + Lokasi</div>
                        </div>
                        <div class="form-group">
                            <label for="so_po">No. IO.SP2K/SO/PO/ANDOP <span>*</span></label>
                            <input type="text" id="so_po" name="so_po" class="form-control" required
                                value="{{ old('so_po', $rma->so_po) }}" placeholder="Masukkan nomor dokumen">
                        </div>
                        <div class="form-group">
                            <label>Valuation Type <span>*</span></label>
                            <div class="radio-group">
                                @foreach (['ex-project'=>'Ex-Project','dismantle'=>'Dismantle','rusak-L'=>'Rusak-L','rusak-TL'=>'Rusak-TL'] as $val=>$lbl)
                                    <label class="radio-option">
                                        <input type="radio" name="valuation_type" value="{{ $val }}" required
                                            {{ old('valuation_type',$rma->valuation_type)===$val ? 'checked' : '' }}>
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
                                value="{{ old('lokasi_asal', $rma->lokasi_asal) }}" placeholder="Masukkan lokasi asal">
                        </div>
                        <div class="form-group">
                            <label for="merk">Merk <span>*</span></label>
                            <input type="text" id="merk" name="merk" class="form-control" required
                                value="{{ old('merk', $rma->merk) }}" placeholder="Merk perangkat">
                        </div>
                        <div class="form-group">
                            <label for="type">Type <span>*</span></label>
                            <input type="text" id="type" name="type" class="form-control" required
                                value="{{ old('type', $rma->type) }}" placeholder="Tipe perangkat">
                        </div>
                        <div class="form-group">
                            <label for="serial_number_edit">Serial Number (SN) / Batch <span>*</span></label>
                            <input type="text" id="serial_number_edit" name="serial_number" class="form-control" required
                                placeholder="Contoh: SN-123456789"
                                value="{{ old('serial_number', $rma->materials->first()?->serial_number ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="material_number">Material Number</label>
                            <input type="text" id="material_number" name="material_number" class="form-control"
                                value="{{ old('material_number', $rma->material_number) }}" placeholder="Opsional">
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
                        <div class="alert-box warning-alert" style="margin-bottom:16px;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Beri tanda checklist pada kotak jika material rusak</span>
                        </div>
                        <div class="checker-grid">
                            @foreach (['Dead on Arrival','Physical Damage','Dead on Operational','Miscelaneous','BER Indication','Intermittent','Software Error','Rectifier faulty','Channel Error','Charging switch','Port Error','Battery faulty','Tx Laser Faulty','Rx Laser Faulty'] as $item)
                                <label class="checker-item">
                                    <input type="checkbox" name="kerusakan[]" value="{{ $item }}"
                                        {{ in_array($item, (array)$kerusakanLama) ? 'checked' : '' }}>
                                    {{ $item }}
                                </label>
                            @endforeach
                        </div>
                        <div class="form-group" style="margin-top:24px;">
                            <label for="alasan">Alasan Tambahan</label>
                            <div class="field-description">Opsional</div>
                            <textarea id="alasan" name="alasan" class="form-control"
                                placeholder="Tuliskan alasan tambahan bila ada...">{{ old('alasan', $rma->alasan) }}</textarea>
                        </div>
                    </div>

                    <!-- FOTO MATERIAL -->
                    <div class="form-card" style="padding-bottom:24px;">
                        <h3 style="font-size:1rem;margin:0 0 4px;color:#1e293b;">Foto Material</h3>
                        <div class="field-description" style="margin-bottom:14px;">Klik foto untuk menandai hapus. Foto ditandai akan dihapus saat disimpan.</div>

                        @if ($rma->materials->count() > 0)
                            <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">
                                <i class="bi bi-images"></i> Foto Saat Ini ({{ $rma->materials->count() }} foto)
                                &nbsp;·&nbsp; <span style="color:#dc2626;font-weight:400;">Klik foto untuk tandai hapus</span>
                            </div>
                            <div class="edit-photo-grid">
                                @foreach ($rma->materials as $mat)
                                    <div class="edit-photo-item" id="photo-wrap-{{ $mat->id }}" onclick="toggleHapusFoto({{ $mat->id }})">
                                        <img src="{{ Storage::url($mat->foto_path) }}" alt="Foto Material">
                                        <div class="edit-photo-overlay">
                                            <i class="bi bi-trash3-fill overlay-icon"></i>
                                        </div>
                                        <span class="delete-tag">HAPUS</span>
                                        <input type="checkbox" name="hapus_foto[]" value="{{ $mat->id }}"
                                            id="chk-hapus-{{ $mat->id }}" style="display:none;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color:#94a3b8;font-size:13px;margin-bottom:12px;">Belum ada foto material.</p>
                        @endif

                        <div style="margin-top:18px;border-top:1px dashed #e2e8f0;padding-top:16px;">
                            <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">
                                <i class="bi bi-plus-circle-fill" style="color:#22c55e;"></i> Tambah Foto Baru
                            </div>
                            <div class="upload-dropzone" id="dropzoneEdit"
                                onclick="document.getElementById('newPhotoInput').click()" style="cursor:pointer;">
                                <i class="bi bi-cloud-arrow-up dropzone-icon"></i>
                                <div class="dropzone-text">Klik atau drag foto baru di sini</div>
                                <input type="file" id="newPhotoInput" name="foto_material_baru[]"
                                    accept="image/jpeg,image/png,image/jpg,image/webp" multiple style="display:none;">
                                <button type="button" class="btn-browse"
                                    onclick="event.stopPropagation();document.getElementById('newPhotoInput').click()">Browse</button>
                            </div>
                            <p style="font-size:0.75rem;color:#94a3b8;margin:6px 0 0;">
                                <i class="bi bi-info-circle"></i> Maks. 2MB per foto, format JPG/PNG/WEBP
                            </p>
                            <div class="new-photos-preview" id="newPhotosPreview"></div>
                        </div>
                    </div>

                    <!-- PENGESAHAN -->
                    <div class="form-card">
                        <h3 style="font-size:1rem;margin:0 0 16px;color:#1e293b;">Data Pengesahan</h3>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label for="nama_pemohon">Nama Engineer / Pemohon <span>*</span></label>
                                <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control" required
                                    value="{{ old('nama_pemohon', $rma->nama_pemohon) }}" placeholder="Nama Terang Engineer">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label for="nama_manager">Supervisor / Manager Name <span>*</span></label>
                                <input type="text" id="nama_manager" name="nama_manager" class="form-control" required
                                    value="{{ old('nama_manager', $rma->nama_manager) }}"
                                    placeholder="Nama Terang Supervisor / Manager"
                                    list="manager-suggestions">
                                @if ($managers->count() > 0)
                                    <datalist id="manager-suggestions">
                                        @foreach ($managers as $mgr)<option value="{{ $mgr }}">@endforeach
                                    </datalist>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-top:18px;">
                            <i class="bi bi-pen-fill" style="font-size:1.3rem;color:#16a34a;margin-top:2px;"></i>
                            <div>
                                <div style="font-weight:600;color:#15803d;font-size:13.5px;">Tanda Tangan Fisik (Basah)</div>
                                <div style="font-size:12px;color:#475569;margin-top:2px;line-height:1.5;">
                                    Dokumen PDF yang digenerate setelah edit akan tetap menyertakan kolom tanda tangan basah.
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:24px;gap:12px;flex-wrap:wrap;">
                            <a href="{{ route('rma') }}"
                                style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;text-decoration:none;font-size:13px;font-weight:500;transition:0.2s;">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn-submit" style="display:inline-flex;align-items:center;gap:7px;">
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
                        <p>Klik pada foto yang ingin dihapus. Foto bertanda merah akan dihapus permanen saat disimpan.</p></div>
                    <div class="note-item"><span>Foto Baru</span>
                        <p>Upload foto tambahan via Browse. Foto lama yang tidak ditandai tetap tersimpan.</p></div>
                    <div class="note-item"><span>Judul Dokumen</span>
                        <p>Kosongkan judul untuk auto-generate dari Merk + Lokasi Asal.</p></div>
                    <div class="note-item"><span>PDF Diperbarui</span>
                        <p>Setelah disimpan, PDF yang didownload akan mencerminkan data terbaru.</p></div>
                </div>
            </aside>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleHapusFoto(matId) {
        const wrap = document.getElementById('photo-wrap-' + matId);
        const chk  = document.getElementById('chk-hapus-' + matId);
        if (!wrap || !chk) return;
        wrap.classList.toggle('marked-delete');
        chk.checked = wrap.classList.contains('marked-delete');
    }

    const newPhotoInput  = document.getElementById('newPhotoInput');
    const newPhotosPreview = document.getElementById('newPhotosPreview');

    if (newPhotoInput) {
        newPhotoInput.addEventListener('change', function () {
            Array.from(this.files).forEach(file => {
                if (!file.type.match('image.*')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const thumb = document.createElement('div');
                    thumb.className = 'new-photo-thumb';
                    thumb.dataset.name = file.name;
                    thumb.innerHTML = `<img src="${e.target.result}" alt=""><button type="button" class="remove-new-btn" onclick="this.closest('.new-photo-thumb').remove()"><i class="bi bi-x"></i></button>`;
                    newPhotosPreview.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    const dropzoneEdit = document.getElementById('dropzoneEdit');
    if (dropzoneEdit) {
        dropzoneEdit.addEventListener('dragover', e => { e.preventDefault(); dropzoneEdit.style.borderColor='#2563eb'; });
        dropzoneEdit.addEventListener('dragleave', () => { dropzoneEdit.style.borderColor=''; });
        dropzoneEdit.addEventListener('drop', e => {
            e.preventDefault(); dropzoneEdit.style.borderColor='';
            const dt = new DataTransfer();
            Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
            newPhotoInput.files = dt.files;
            newPhotoInput.dispatchEvent(new Event('change'));
        });
    }
</script>
</body>
</html>
