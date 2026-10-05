<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tambah POP - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/add-pop.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <div class="app-container">

        {{-- SIDEBAR COMPONENT --}}
        <x-sidebar />
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <main class="main-content">

            {{-- TOPBAR COMPONENT --}}
            <x-topbar />

            <div class="add-pop-content">
                {{-- HEADER BAR: Back + Breadcrumb As Title --}}
                <div class="add-pop-header-bar">
                    <a href="{{ route('pops.index') }}" class="add-pop-back" title="Kembali ke List POP" aria-label="Kembali ke List POP">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="add-pop-header-text">
                        <x-breadcrumb :items="[
                            ['label' => 'POP', 'route' => 'pops.index'],
                            ['label' => 'Tambah POP'],
                        ]" />
                        <p class="add-pop-subheading">Form Registrasi Data Point of Presence Baru</p>
                    </div>
                </div>

                <div class="add-pop-card">

                    <div class="add-pop-alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Isi form di bawah ini untuk menambahkan data POP baru.</span>
                    </div>

                    {{-- Flash error dari validasi Laravel --}}
                    @if ($errors->any())
                        <div class="add-pop-error-box" role="alert">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali data berikut:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="addPopForm" method="POST" action="{{ route('pops.store') }}">
                        @csrf

                        <div class="add-pop-form-grid">

                            <div class="add-pop-group">
                                <label for="provinsi">Provinsi <span class="add-pop-required">*</span></label>
                                <input type="text" id="provinsi" name="provinsi"
                                    class="add-pop-input {{ $errors->has('provinsi') ? 'is-invalid' : '' }}"
                                    value="{{ old('provinsi') }}" placeholder="Contoh: Jambi" autocomplete="off" required>
                                @error('provinsi')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="kota_kabupaten">Kota/Kabupaten <span class="add-pop-required">*</span></label>
                                <input type="text" id="kota_kabupaten" name="kota_kabupaten"
                                    class="add-pop-input {{ $errors->has('kota_kabupaten') ? 'is-invalid' : '' }}"
                                    value="{{ old('kota_kabupaten') }}" placeholder="Contoh: Kota Jambi" autocomplete="off" required>
                                @error('kota_kabupaten')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="kode_pop">ID POP <span class="add-pop-required">*</span></label>
                                <input type="text" id="kode_pop" name="kode_pop"
                                    class="add-pop-input {{ $errors->has('kode_pop') ? 'is-invalid' : '' }}"
                                    value="{{ old('kode_pop') }}" placeholder="Contoh: POP-JMB-001" autocomplete="off" required>
                                @error('kode_pop')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="nama_pop">Nama POP <span class="add-pop-required">*</span></label>
                                <input type="text" id="nama_pop" name="nama_pop"
                                    class="add-pop-input {{ $errors->has('nama_pop') ? 'is-invalid' : '' }}"
                                    value="{{ old('nama_pop') }}" placeholder="Contoh: POP Jambi Kota" autocomplete="off" required>
                                @error('nama_pop')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="jenis_bangunan">Building</label>
                                <div class="add-pop-select-wrapper">
                                    <select id="jenis_bangunan" name="jenis_bangunan"
                                        class="add-pop-select {{ $errors->has('jenis_bangunan') ? 'is-invalid' : '' }}">
                                        <option value="" disabled {{ old('jenis_bangunan') ? '' : 'selected' }}>Pilih Building</option>
                                        @foreach (['Shelter', 'Shelter CKD', 'Shelter Permanen', 'Mini Shelter', 'ODC', 'Mini POP', 'Mikro POP', 'OLT Gantung'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_bangunan') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                                </div>
                                @error('jenis_bangunan')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="tipe_pop">Type POP</label>
                                <div class="add-pop-select-wrapper">
                                    <select id="tipe_pop" name="tipe_pop"
                                        class="add-pop-select {{ $errors->has('tipe_pop') ? 'is-invalid' : '' }}">
                                        <option value="" disabled {{ old('tipe_pop') ? '' : 'selected' }}>Pilih Tipe POP</option>
                                        @foreach (['POP-SB', 'POP-A', 'POP-B', 'POP-D'] as $opt)
                                            <option value="{{ $opt }}" {{ old('tipe_pop') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                                </div>
                                @error('tipe_pop')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" id="latitude" name="latitude" inputmode="decimal"
                                    class="add-pop-input {{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                                    value="{{ old('latitude') }}" placeholder="Contoh: -1.610122" autocomplete="off">
                                @error('latitude')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" id="longitude" name="longitude" inputmode="decimal"
                                    class="add-pop-input {{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                                    value="{{ old('longitude') }}" placeholder="Contoh: 103.613120" autocomplete="off">
                                @error('longitude')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>{{-- /.add-pop-form-grid --}}

                        <div class="add-pop-actions">
                            <a href="{{ route('pops.index') }}" class="add-pop-btn-cancel">Batal</a>
                            <button type="submit" class="add-pop-btn-save">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>

    <script>
        // Konfirmasi SweetAlert2 sebelum form disubmit
        document.getElementById('addPopForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Simpan Data POP?',
                text: 'Pastikan semua data sudah benar sebelum disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1688e8',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                heightAuto: false, // hindari layout loncat di mobile
                customClass: {
                    popup: 'swal-popup-custom',
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-custom',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>