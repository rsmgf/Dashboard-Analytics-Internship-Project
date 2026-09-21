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
                        <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="back-button" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div style="margin-bottom: 16px; padding: 12px 16px; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
                        <ul style="margin: 0; padding-left: 16px; color: #b91c1c; font-size: 0.875rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('acs.update', [$pop->id, $ac->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="detail-container">

                        <!-- General Information (readonly) -->
                        <div class="form-card">
                            <h3 class="form-section-title">General Information</h3>
                            <div class="detail-grid-3">
                                <div class="detail-item">
                                    <span class="detail-label">POP</span>
                                    <div style="padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.84rem; font-weight: 500; color: #334155;">
                                        {{ $pop->kode_pop }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Kota / Kabupaten</span>
                                    <div style="padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.84rem; font-weight: 500; color: #334155;">
                                        {{ $pop->kota_kabupaten }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tipe POP</span>
                                    <div style="padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.84rem; font-weight: 500; color: #334155;">
                                        {{ $pop->tipe_pop ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-bottom-grid">

                            <!-- Checklist AC -->
                            <div class="form-card mb-0">
                                <h3 class="form-section-title">Checklist Air Conditioner</h3>

                                <div class="checklist-table-container">

                                    <!-- Nomor AC (readonly — auto-generated) -->
                                    <div class="checklist-row">
                                        <div class="checklist-label">Nomor AC</div>
                                        <div class="checklist-field">
                                            <div style="padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.84rem; color: #334155;">
                                                {{ $ac->nomor_ac }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jenis Freon -->
                                    @php
                                        $knownFreons   = ['R134a', 'R22', 'R32', 'R410', 'R410A'];
                                        $curFreon      = old('jenis_freon', $ac->jenis_freon);
                                        $isOtherFreon  = !in_array($curFreon, $knownFreons);
                                        $selFreon      = $isOtherFreon ? 'Others' : $curFreon;
                                    @endphp
                                    <div class="checklist-row">
                                        <div class="checklist-label">Jenis Freon</div>
                                        <div class="checklist-field">
                                            <select name="jenis_freon" id="jenis_freon" class="form-control-custom" required>
                                                @foreach ($knownFreons as $f)
                                                    <option value="{{ $f }}" {{ $selFreon === $f ? 'selected' : '' }}>{{ $f }}</option>
                                                @endforeach
                                                <option value="Others" {{ $selFreon === 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                            <input type="text" name="jenis_freon_others" id="jenis_freon_others" class="form-control-custom mt-2"
                                                placeholder="Masukkan jenis freon lainnya"
                                                value="{{ $isOtherFreon ? $curFreon : '' }}"
                                                style="display: {{ $isOtherFreon ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <!-- Merk AC -->
                                    @php
                                        $knownMerks = ['Aqua', 'Kabinet', 'Daikin', 'DBS', 'Gree', 'Hopep', 'Huarui', 'LG', 'Midea', 'Panasonic', 'Samsung', 'Sharp', 'TCL'];
                                        $curMerk    = old('merk_ac', $ac->merk_ac);
                                        $isOtherMerk = !in_array($curMerk, $knownMerks);
                                        $selMerk    = $isOtherMerk ? 'Others' : $curMerk;
                                    @endphp
                                    <div class="checklist-row">
                                        <div class="checklist-label">Merk AC</div>
                                        <div class="checklist-field">
                                            <select name="merk_ac" id="merk_ac" class="form-control-custom" required>
                                                @foreach ($knownMerks as $m)
                                                    <option value="{{ $m }}" {{ $selMerk === $m ? 'selected' : '' }}>{{ $m }}</option>
                                                @endforeach
                                                <option value="Others" {{ $selMerk === 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                            <input type="text" name="merk_ac_others" id="merk_ac_others" class="form-control-custom mt-2"
                                                placeholder="Masukkan merk AC lainnya"
                                                value="{{ $isOtherMerk ? $curMerk : '' }}"
                                                style="display: {{ $isOtherMerk ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <!-- Type AC -->
                                    @php
                                        $knownTypes = ['Inverter', 'Non Inverter'];
                                        $curType    = old('type_ac', $ac->type_ac);
                                        $isOtherType = !in_array($curType, $knownTypes);
                                        $selType    = $isOtherType ? 'Others' : $curType;
                                    @endphp
                                    <div class="checklist-row">
                                        <div class="checklist-label">Type AC</div>
                                        <div class="checklist-field">
                                            <select name="type_ac" id="type_ac" class="form-control-custom" required>
                                                @foreach ($knownTypes as $t)
                                                    <option value="{{ $t }}" {{ $selType === $t ? 'selected' : '' }}>{{ $t }}</option>
                                                @endforeach
                                                <option value="Others" {{ $selType === 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                            <input type="text" name="type_ac_others" id="type_ac_others" class="form-control-custom mt-2"
                                                placeholder="Masukkan type AC lainnya"
                                                value="{{ $isOtherType ? $curType : '' }}"
                                                style="display: {{ $isOtherType ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <!-- PK -->
                                    <div class="checklist-row">
                                        <div class="checklist-label">PK</div>
                                        <div class="checklist-field">
                                            <select name="pk" class="form-control-custom select-custom" required>
                                                @foreach (['0.5', '1', '1.5', '2', '2.5', '5'] as $pkVal)
                                                    <option value="{{ $pkVal }}" {{ old('pk', $ac->pk) === $pkVal ? 'selected' : '' }}>{{ $pkVal }} PK</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Tahun Manufaktur -->
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tahun Manufaktur</div>
                                        <div class="checklist-field">
                                            <select name="tahun_manufaktur" id="tahun_manufaktur" class="form-control-custom" required>
                                                <option value="" disabled>Pilih Tahun</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Tanggal Instalasi -->
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tanggal Instalasi</div>
                                        <div class="checklist-field">
                                            <input type="date" name="tanggal_instalasi" class="form-control-custom"
                                                value="{{ old('tanggal_instalasi', $ac->tanggal_instalasi?->format('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    <!-- Tanggal Terakhir PM -->
                                    <div class="checklist-row">
                                        <div class="checklist-label">Tanggal Terakhir PM</div>
                                        <div class="checklist-field">
                                            <input type="date" name="tanggal_terakhir_pm" id="tanggal_terakhir_pm" class="form-control-custom"
                                                value="{{ old('tanggal_terakhir_pm', $ac->tanggal_terakhir_pm?->format('Y-m-d')) }}">
                                            <div id="statusPreview" style="margin-top:8px; display:flex; align-items:center; gap:8px; font-size:0.82rem;">
                                                <span id="statusDot" style="width:8px;height:8px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>
                                                <span id="statusText" style="color:#64748b;font-weight:500;">-</span>
                                                <span style="color:#94a3b8; font-size:0.76rem;">(otomatis)</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Photo AC -->
                            <div class="form-card mb-0 photo-card-wrapper">
                                <h3 class="form-section-title">Photo Air Conditioner</h3>
                                <div class="detail-photo-box" style="flex-direction: column; gap: 14px; padding: 16px; background: #ffffff; height: auto;">
                                    <div style="width: 100%; height: 250px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; position: relative;">
                                        @if ($ac->photo_ac)
                                            <img src="{{ asset('storage/' . $ac->photo_ac) }}" alt="Foto AC" id="previewPhoto" style="width:100%; height:100%; object-fit:cover;">
                                            <div id="noDetailPhoto" class="no-preview" style="display:none;">
                                        @else
                                            <img src="" alt="Foto AC" id="previewPhoto" style="width:100%; height:100%; object-fit:cover; display:none;">
                                            <div id="noDetailPhoto" class="no-preview">
                                        @endif
                                                <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Belum ada foto baru yang dipilih</span>
                                            </div>
                                    </div>
                                    <div style="width: 100%; display: flex; flex-direction: column; gap: 6px;">
                                        <label for="photoInput" class="upload-btn-custom">
                                            <i class="bi bi-upload"></i> Unggah Foto Baru
                                        </label>
                                        <input type="file" name="photo_ac" id="photoInput" accept="image/*" style="display: none;">
                                        <span id="fileName" style="font-size: 0.78rem; color: #64748b; text-align: center;">Format: JPG, PNG, JPEG (Maks. 10MB)</span>
                                    </div>
                                    <div style="width:100%;">
                                        <label style="font-size:0.82rem; color:#475569; font-weight:500;">Keterangan Gambar</label>
                                        <input type="text" name="keterangan_gambar_ac" class="form-control-custom" style="margin-top:6px;"
                                            placeholder="Masukkan keterangan gambar"
                                            value="{{ old('keterangan_gambar_ac', $ac->keterangan_gambar_ac) }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                            <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="btn-action-cancel"
                                style="padding: 10px 22px; border-radius: 8px; background: #e2e8f0; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.88rem;">Batal</a>
                            <button type="submit" class="btn-action-save"
                                style="padding: 10px 24px; border-radius: 8px; background: #0086ff; color: #ffffff; border: none; font-weight: 600; font-size: 0.88rem; cursor: pointer;">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const selectedTahun = @json(old('tahun_manufaktur', $ac->tahun_manufaktur));

        document.addEventListener('DOMContentLoaded', function () {
            // Populate Tahun Manufaktur
            const tahunSel   = document.getElementById('tahun_manufaktur');
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 2013; y--) {
                const opt = document.createElement('option');
                opt.value = y; opt.textContent = y;
                if (y.toString() === selectedTahun.toString()) opt.selected = true;
                tahunSel.appendChild(opt);
            }

            // Others toggle helper
            function setupOthers(selId, inputId) {
                const sel = document.getElementById(selId);
                const inp = document.getElementById(inputId);
                if (!sel || !inp) return;
                sel.addEventListener('change', function () {
                    if (this.value === 'Others') {
                        inp.style.display = 'block'; inp.required = true;
                    } else {
                        inp.style.display = 'none'; inp.required = false; inp.value = '';
                    }
                });
            }
            setupOthers('jenis_freon', 'jenis_freon_others');
            setupOthers('merk_ac', 'merk_ac_others');
            setupOthers('type_ac', 'type_ac_others');

            // Status preview
            const pmInput = document.getElementById('tanggal_terakhir_pm');
            pmInput.addEventListener('change', updateStatus);
            updateStatus();

            function updateStatus() {
                const val  = pmInput.value;
                const dot  = document.getElementById('statusDot');
                const text = document.getElementById('statusText');
                if (!val) { dot.style.background = '#94a3b8'; text.textContent = 'Belum PM'; return; }
                const months = (new Date() - new Date(val)) / (1000 * 60 * 60 * 24 * 30);
                if (months >= 6) { dot.style.background = '#f59e0b'; text.textContent = 'Jadwal PM'; }
                else             { dot.style.background = '#10b981'; text.textContent = 'Sudah PM'; }
            }

            // Photo preview
            document.getElementById('photoInput').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;
                const preview = document.getElementById('previewPhoto');
                const noPhoto = document.getElementById('noDetailPhoto');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (noPhoto) noPhoto.style.display = 'none';
                document.getElementById('fileName').textContent = 'File dipilih: ' + file.name;
                document.getElementById('fileName').style.color = '#059669';
            });
        });
    </script>
</body>
</html>