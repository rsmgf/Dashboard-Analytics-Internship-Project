<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit POP - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/add-pop.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        /* Kustomisasi SweetAlert2 */
        .swal-popup-custom {
            font-family: 'Poppins', sans-serif;
            border-radius: 16px !important;
        }

        .swal-title-custom {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #111827 !important;
        }

        .swal-html-custom {
            font-size: 0.875rem !important;
            color: #6b7280 !important;
        }

        .swal-btn-confirm,
        .swal-btn-cancel {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }

        /* Error message di bawah input */
        .add-pop-error {
            display: block;
            color: #DC2626;
            font-size: 0.78rem;
            margin-top: 4px;
        }

        .add-pop-input.is-invalid,
        .add-pop-select.is-invalid {
            border-color: #DC2626 !important;
        }
    </style>
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
                <x-breadcrumb :items="[['label' => 'POP', 'route' => 'pops.index'], ['label' => 'Edit POP: ' . $pop->nama_pop]]" />
                <div class="add-pop-card">

                    <div class="add-pop-alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Perbarui informasi data POP pada form di bawah ini.</span>
                    </div>

                    <div class="add-pop-title">
                        <h1>Edit Point of Presence</h1>
                        <p>Ubah Data POP â€” {{ $pop->nama_pop }}</p>
                    </div>

                    {{-- Flash error dari validasi Laravel --}}
                    @if ($errors->any())
                        <div
                            style="background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:0.85rem; color:#B91C1C;">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali data
                                berikut:</strong>
                            <ul style="margin:6px 0 0 16px; padding:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="editPopForm" method="POST" action="{{ route('pops.update', $pop->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="add-pop-group">
                            <label for="provinsi">Provinsi <span class="add-pop-required">*</span></label>
                            <input type="text" id="provinsi" name="provinsi"
                                class="add-pop-input {{ $errors->has('provinsi') ? 'is-invalid' : '' }}"
                                value="{{ old('provinsi', $pop->provinsi) }}" placeholder="Contoh: Jambi" required>
                            @error('provinsi')
                                <span class="add-pop-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="add-pop-group">
                            <label for="kota_kabupaten">Kota/Kabupaten <span class="add-pop-required">*</span></label>
                            <input type="text" id="kota_kabupaten" name="kota_kabupaten"
                                class="add-pop-input {{ $errors->has('kota_kabupaten') ? 'is-invalid' : '' }}"
                                value="{{ old('kota_kabupaten', $pop->kota_kabupaten) }}"
                                placeholder="Contoh: Kota Jambi" required>
                            @error('kota_kabupaten')
                                <span class="add-pop-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="add-pop-group">
                            <label for="kode_pop">ID POP <span class="add-pop-required">*</span></label>
                            <input type="text" id="kode_pop" name="kode_pop"
                                class="add-pop-input {{ $errors->has('kode_pop') ? 'is-invalid' : '' }}"
                                value="{{ old('kode_pop', $pop->kode_pop) }}" placeholder="Contoh: POP-JMB-001"
                                required>
                            @error('kode_pop')
                                <span class="add-pop-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="add-pop-group">
                            <label for="nama_pop">Nama POP <span class="add-pop-required">*</span></label>
                            <input type="text" id="nama_pop" name="nama_pop"
                                class="add-pop-input {{ $errors->has('nama_pop') ? 'is-invalid' : '' }}"
                                value="{{ old('nama_pop', $pop->nama_pop) }}" placeholder="Contoh: POP Jambi Kota"
                                required>
                            @error('nama_pop')
                                <span class="add-pop-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="add-pop-group">
                            <label for="jenis_bangunan">Building</label>
                            <div class="add-pop-select-wrapper">
                                <select id="jenis_bangunan" name="jenis_bangunan"
                                    class="add-pop-select {{ $errors->has('jenis_bangunan') ? 'is-invalid' : '' }}">
                                    <option value="" disabled>Pilih Building</option>
                                    @foreach (['Shelter Permanent', 'Shelter Temporary', 'Building', 'Outdoor'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ old('jenis_bangunan', $pop->jenis_bangunan) == $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
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
                                    <option value="" disabled>Pilih Type POP</option>
                                    @foreach (['POP-SB', 'POP-DC', 'POP-ODC', 'POP-FO'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ old('tipe_pop', $pop->tipe_pop) == $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                            </div>
                            @error('tipe_pop')
                                <span class="add-pop-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="add-pop-actions">
                            <a href="{{ route('pops.index') }}" class="add-pop-btn-cancel">Batal</a>
                            <button type="submit" class="add-pop-btn-save">
                                <i class="bi bi-check-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>

    <script>
        // Konfirmasi SweetAlert2 sebelum form disubmit
        document.getElementById('editPopForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'Pastikan semua data sudah benar sebelum disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1688e8',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-popup-custom',
                    title: 'swal-title-custom',
                    htmlContainer: 'swal-html-custom',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form yang sesungguhnya
                }
            });
        });
    </script>
</body>

</html>
