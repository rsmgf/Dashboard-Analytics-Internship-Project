<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Air Conditioner - PLN Icon Plus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <div class="ac-page-info" style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="back-button" title="Kembali ke Detail AC">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => $pop->nama_pop_display . ': AC', 'route' => 'acs.index', 'params' => ['pop' => $pop->id]],
                            ['label' => $ac->nomor_ac, 'route' => 'acs.show', 'params' => [$pop->id, $ac->id]],
                            ['label' => 'Edit AC'],
                        ]" />
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

                <form action="{{ route('acs.update', [$pop->id, $ac->id]) }}" method="POST" id="acForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="form-card">
                        <h3 class="form-section-title">General Information</h3>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="pop">POP</label>
                                <input type="text" id="pop" class="form-control disabled-input" value="{{ $pop->nama_pop_display }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota / Kabupaten</label>
                                <input type="text" id="kota" class="form-control disabled-input" value="{{ $pop->kota_kabupaten }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tipe_pop">Tipe POP</label>
                                <input type="text" id="tipe_pop" class="form-control disabled-input" value="{{ $pop->tipe_pop ?? '-' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Air Conditioner -->
                    <div class="form-card">
                        <h3 class="form-section-title">Detail Air Conditioner</h3>
                        <div class="form-grid-2">

                            <!-- Nomor AC (readonly) -->
                            <div class="form-group">
                                <label for="nomor_ac">Nomor AC</label>
                                <input type="text" id="nomor_ac" class="form-control disabled-input" value="{{ $ac->nomor_ac }}" readonly>
                            </div>

                            <!-- Jenis Freon -->
                            @php
                                $knownFreons  = ['R134a', 'R22', 'R32', 'R410', 'R410A'];
                                $curFreon     = old('jenis_freon', $ac->jenis_freon);
                                $isOtherFreon = !in_array($curFreon, $knownFreons);
                                $selFreon     = $isOtherFreon ? 'Others' : $curFreon;
                            @endphp
                            <div class="form-group">
                                <label for="jenis_freon">Jenis Freon <span class="required">*</span></label>
                                <select id="jenis_freon" name="jenis_freon" class="form-control" required>
                                    <option value="" disabled>Pilih Jenis Freon</option>
                                    @foreach ($knownFreons as $freon)
                                        <option value="{{ $freon }}" {{ $selFreon === $freon ? 'selected' : '' }}>{{ $freon }}</option>
                                    @endforeach
                                    <option value="Others" {{ $selFreon === 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                <input type="text" id="jenis_freon_others" name="jenis_freon_others" class="form-control mt-2"
                                    placeholder="Masukkan jenis freon lainnya"
                                    value="{{ old('jenis_freon_others', $isOtherFreon ? $curFreon : '') }}"
                                    style="display: {{ $selFreon === 'Others' ? 'block' : 'none' }};">
                                @error('jenis_freon')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Merk AC -->
                            @php
                                $knownMerks  = ['Aqua', 'Kabinet', 'Daikin', 'DBS', 'Gree', 'Hopep', 'Huarui', 'LG', 'Midea', 'Panasonic', 'Samsung', 'Sharp', 'TCL'];
                                $curMerk     = old('merk_ac', $ac->merk_ac);
                                $isOtherMerk = !in_array($curMerk, $knownMerks);
                                $selMerk     = $isOtherMerk ? 'Others' : $curMerk;
                            @endphp
                            <div class="form-group">
                                <label for="merk_ac">Merk AC <span class="required">*</span></label>
                                <select id="merk_ac" name="merk_ac" class="form-control" required>
                                    <option value="" disabled>Pilih Merk AC</option>
                                    @foreach ($knownMerks as $merk)
                                        <option value="{{ $merk }}" {{ $selMerk === $merk ? 'selected' : '' }}>{{ $merk }}</option>
                                    @endforeach
                                    <option value="Others" {{ $selMerk === 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                <input type="text" id="merk_ac_others" name="merk_ac_others" class="form-control mt-2"
                                    placeholder="Masukkan merk AC lainnya"
                                    value="{{ old('merk_ac_others', $isOtherMerk ? $curMerk : '') }}"
                                    style="display: {{ $selMerk === 'Others' ? 'block' : 'none' }};">
                                @error('merk_ac')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tahun Manufaktur -->
                            <div class="form-group">
                                <label for="tahun_manufaktur">Tahun Manufaktur <span class="required">*</span></label>
                                <select id="tahun_manufaktur" name="tahun_manufaktur" class="form-control" required>
                                    <option value="" disabled>Pilih Tahun Manufaktur</option>
                                </select>
                                @error('tahun_manufaktur')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Type AC -->
                            @php
                                $knownTypes  = ['Inverter', 'Non Inverter'];
                                $curType     = old('type_ac', $ac->type_ac);
                                $isOtherType = !in_array($curType, $knownTypes);
                                $selType     = $isOtherType ? 'Others' : $curType;
                            @endphp
                            <div class="form-group">
                                <label for="type_ac">Type AC <span class="required">*</span></label>
                                <select id="type_ac" name="type_ac" class="form-control" required>
                                    <option value="" disabled>Pilih Type AC</option>
                                    @foreach ($knownTypes as $type)
                                        <option value="{{ $type }}" {{ $selType === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                    <option value="Others" {{ $selType === 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                <input type="text" id="type_ac_others" name="type_ac_others" class="form-control mt-2"
                                    placeholder="Masukkan type AC lainnya"
                                    value="{{ old('type_ac_others', $isOtherType ? $curType : '') }}"
                                    style="display: {{ $selType === 'Others' ? 'block' : 'none' }};">
                                @error('type_ac')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- PK -->
                            <div class="form-group">
                                <label for="pk">PK <span class="required">*</span></label>
                                <select id="pk" name="pk" class="form-control" required>
                                    <option value="" disabled>Pilih PK</option>
                                    @foreach (['0.5', '1', '1.5', '2', '2.5', '5'] as $pkVal)
                                        <option value="{{ $pkVal }}" {{ old('pk', $ac->pk) == $pkVal ? 'selected' : '' }}>{{ $pkVal }} PK</option>
                                    @endforeach
                                </select>
                                @error('pk')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tanggal Instalasi -->
                            <div class="form-group">
                                <label for="tanggal_instalasi">Tanggal Instalasi</label>
                                <input type="date" id="tanggal_instalasi" name="tanggal_instalasi"
                                    class="form-control" value="{{ old('tanggal_instalasi', $ac->tanggal_instalasi?->format('Y-m-d')) }}">
                                @error('tanggal_instalasi')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tanggal Terakhir PM -->
                            <div class="form-group">
                                <label for="tanggal_terakhir_pm">Tanggal Terakhir PM</label>
                                <input type="date" id="tanggal_terakhir_pm" name="tanggal_terakhir_pm"
                                    class="form-control" value="{{ old('tanggal_terakhir_pm', $ac->tanggal_terakhir_pm?->format('Y-m-d')) }}">
                                <small class="upload-info">Jadwal PM rutin disarankan setiap 6 bulan sekali.</small>
                                @error('tanggal_terakhir_pm')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>

                            <!-- Status PM (preview otomatis) -->
                            <div class="form-group">
                                <label>Status AC</label>
                                <div id="statusDisplay" class="form-control" style="display:flex; align-items:center; gap:10px; background:#f8fafc; cursor:default;">
                                    <span id="statusDot" style="width:10px; height:10px; border-radius:50%; background:#94a3b8; display:inline-block;"></span>
                                    <strong id="statusText">-</strong>
                                    <span style="font-size:0.78rem; color:#94a3b8; margin-left:auto;">Otomatis dari sistem</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Photo AC -->
                    <div class="form-card">
                        <h3 class="form-section-title">
                            <i class="bi bi-camera-fill"></i> Photo Air Conditioner
                        </h3>
                        <p style="font-size:0.8rem; color:#64748b; margin-top:-8px; margin-bottom:16px;">
                            Upload foto baru untuk mengganti foto yang ada
                        </p>

                        @php
                            $hasPhoto = !empty($ac->photo_ac) && file_exists(public_path('storage/' . $ac->photo_ac));
                        @endphp

                        <div class="rform-photo-grid">
                            <div class="rform-drop-zone" id="dropZone" onclick="document.getElementById('photo_ac').click()">
                                <div class="rform-drop-icon">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                </div>
                                <div class="rform-drop-text" id="dropText">{{ $hasPhoto ? 'Klik untuk ganti foto' : 'Masukkan file disini' }}</div>
                                <button type="button" class="rform-browse-btn">Browse</button>
                                <div class="rform-drop-hint">Format: JPG, JPEG, PNG • Maks. ukuran: 10 MB</div>
                                <input type="file" id="photo_ac" name="photo_ac" accept=".jpg,.jpeg,.png"
                                       style="display:none;" onchange="previewFotoAC(this)">
                            </div>

                            <div class="rform-photo-preview">
                                <span class="rform-preview-label">{{ $hasPhoto ? 'Foto saat ini' : 'Preview foto' }}</span>
                                <div class="rform-preview-box">
                                    @if($hasPhoto)
                                        <img id="fotoPreview" src="{{ asset('storage/' . $ac->photo_ac) }}" alt="Foto AC" style="display:block;">
                                        <div class="rform-preview-empty" id="fotoEmpty" style="display:none;"><i class="bi bi-image"></i><span>Belum ada foto</span></div>
                                    @else
                                        <img id="fotoPreview" src="" alt="Preview AC" style="display:none;">
                                        <div class="rform-preview-empty" id="fotoEmpty"><i class="bi bi-image"></i><span>Belum ada foto yang dipilih</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('photo_ac')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="keterangan_gambar_ac" style="font-weight: 500; font-size: 0.84rem; color: #475569; display: block; margin-bottom: 6px;">Keterangan Gambar</label>
                            <input type="text" id="keterangan_gambar_ac" name="keterangan_gambar_ac"
                                class="form-control" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-family: 'Poppins', sans-serif; font-size: 0.88rem;"
                                placeholder="Masukkan keterangan gambar"
                                value="{{ old('keterangan_gambar_ac', $ac->keterangan_gambar_ac) }}">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('acs.show', [$pop->id, $ac->id]) }}" class="btn-reset" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const oldTahun = @json(old('tahun_manufaktur', $ac->tahun_manufaktur));

        document.addEventListener('DOMContentLoaded', function () {
            // Populate Tahun Manufaktur (dari tahun ini mundur ke 2013)
            const tahunSelect  = document.getElementById('tahun_manufaktur');
            const currentYear  = new Date().getFullYear();
            for (let tahun = currentYear; tahun >= 2013; tahun--) {
                const opt = document.createElement('option');
                opt.value = tahun;
                opt.textContent = tahun;
                if (tahun.toString() === (oldTahun ? oldTahun.toString() : '')) opt.selected = true;
                tahunSelect.appendChild(opt);
            }

            // Others toggle
            function setupOthers(selectId, othersId) {
                const sel = document.getElementById(selectId);
                const inp = document.getElementById(othersId);
                sel.addEventListener('change', function () {
                    if (this.value === 'Others') {
                        inp.style.display = 'block';
                        inp.required = true;
                    } else {
                        inp.style.display = 'none';
                        inp.required = false;
                        inp.value = '';
                    }
                });
            }
            setupOthers('jenis_freon', 'jenis_freon_others');
            setupOthers('merk_ac', 'merk_ac_others');
            setupOthers('type_ac', 'type_ac_others');

            // Live status PM preview
            const tanggalPmInput = document.getElementById('tanggal_terakhir_pm');
            tanggalPmInput.addEventListener('change', updateStatus);
            updateStatus();

            function updateStatus() {
                const val  = tanggalPmInput.value;
                const dot  = document.getElementById('statusDot');
                const text = document.getElementById('statusText');
                if (!val) {
                    dot.style.background = '#94a3b8'; text.textContent = 'Belum PM'; return;
                }
                const months = (new Date() - new Date(val)) / (1000 * 60 * 60 * 24 * 30);
                if (months >= 6) {
                    dot.style.background = '#f59e0b'; text.textContent = 'Jadwal PM';
                } else {
                    dot.style.background = '#10b981'; text.textContent = 'Sudah PM';
                }
            }

            // Photo preview handler
            window.previewFotoAC = function (input) {
                const file = input.files ? input.files[0] : null;
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (event) {
                    const img = document.getElementById('fotoPreview');
                    const empty = document.getElementById('fotoEmpty');
                    if (img) {
                        img.src = event.target.result;
                        img.style.display = 'block';
                    }
                    if (empty) empty.style.display = 'none';
                    const dropText = document.getElementById('dropText');
                    if (dropText) dropText.textContent = 'Klik untuk ganti file';
                };
                reader.readAsDataURL(file);
            };

            const dropZone = document.getElementById('dropZone');
            if (dropZone) {
                dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.background = '#dbeafe'; });
                dropZone.addEventListener('dragleave', () => { dropZone.style.background = ''; });
                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    dropZone.style.background = '';
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        document.getElementById('photo_ac').files = e.dataTransfer.files;
                        previewFotoAC({ files: [file] });
                    }
                });
            }
        });
    </script>
</body>
</html>