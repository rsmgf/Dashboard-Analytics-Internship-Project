<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Edit POP - PLN Icon Plus</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/sidebar.css', 'resources/css/add-pop.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body class="{{ (session('active_role') ?? (auth()->user()?->hasRole('manajer') ? 'manajer' : 'super_admin')) === 'manajer' ? 'manajer-mode' : '' }}">
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
                            ['label' => 'Edit POP (' . $pop->nama_pop_display . ')'],
                        ]" />
                        <p class="add-pop-subheading">Kode POP: <strong>{{ $pop->kode_pop }}</strong> &middot; {{ $pop->kota_kabupaten }}, {{ $pop->provinsi }}</p>
                    </div>
                </div>

                <div class="add-pop-card">

                    <div class="add-pop-alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Perbarui informasi data POP pada form di bawah ini.</span>
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

                    <form id="editPopForm" method="POST" action="{{ route('pops.update', $pop->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="add-pop-form-grid">

                            @php
                                $provinceOptions = ['Jambi', 'Sumsel'];
                                $jambiCities = ['Batanghari', 'Bungo', 'Jambi', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Sungai Penuh', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur', 'Tebo'];
                                $sumselCities = ['Lubuk Linggau', 'Musi Rawas'];
                                $oldProvince = old('provinsi', $pop->provinsi);
                                $provinceIsKnown = in_array($oldProvince, $provinceOptions, true);
                                $cityOptions = $oldProvince === 'Jambi' ? $jambiCities : ($oldProvince === 'Sumsel' ? $sumselCities : []);
                                $oldCity = old('kota_kabupaten', $pop->kota_kabupaten);
                                $cityIsKnown = in_array($oldCity, $cityOptions, true);
                            @endphp
                            <div class="add-pop-group">
                                <label for="provinsi">Provinsi <span class="add-pop-required">*</span></label>
                                <div class="add-pop-select-wrapper">
                                    <select id="provinsi" name="provinsi" class="add-pop-select {{ $errors->has('provinsi') ? 'is-invalid' : '' }}" required>
                                        <option value="" disabled {{ $oldProvince === '' ? 'selected' : '' }}>Pilih provinsi</option>
                                        @foreach ($provinceOptions as $option)
                                            <option value="{{ $option }}" {{ $oldProvince === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                        <option value="__other__" {{ !$provinceIsKnown && $oldProvince !== '' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                                </div>
                                <input type="text" id="provinsi_lainnya" class="add-pop-input add-pop-other-input" value="{{ !$provinceIsKnown ? $oldProvince : '' }}" placeholder="Masukkan nama provinsi" autocomplete="off" {{ !$provinceIsKnown && $oldProvince !== '' ? 'required' : 'hidden' }}>
                                @error('provinsi')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="kota_kabupaten">Kota/Kabupaten <span class="add-pop-required">*</span></label>
                                <div class="add-pop-select-wrapper">
                                    <select id="kota_kabupaten" name="kota_kabupaten" class="add-pop-select {{ $errors->has('kota_kabupaten') ? 'is-invalid' : '' }}" data-selected="{{ $oldCity === '' ? '' : ($cityIsKnown ? $oldCity : '__other__') }}" required>
                                        <option value="" disabled {{ $oldCity === '' ? 'selected' : '' }}>Pilih provinsi terlebih dahulu</option>
                                        @foreach ($cityOptions as $option)
                                            <option value="{{ $option }}" {{ $oldCity === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                        <option value="__other__" {{ !$cityIsKnown && $oldCity !== '' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <i class="bi bi-chevron-down add-pop-select-arrow"></i>
                                </div>
                                <input type="text" id="kota_kabupaten_lainnya" class="add-pop-input add-pop-other-input" value="{{ !$cityIsKnown ? $oldCity : '' }}" placeholder="Masukkan nama kota/kabupaten" autocomplete="off" {{ !$cityIsKnown && $oldCity !== '' ? 'required' : 'hidden' }}>
                                @error('kota_kabupaten')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="kode_pop">ID POP <span class="add-pop-required">*</span></label>
                                <input type="text" id="kode_pop" name="kode_pop"
                                    class="add-pop-input {{ $errors->has('kode_pop') ? 'is-invalid' : '' }}"
                                    value="{{ old('kode_pop', $pop->kode_pop) }}" placeholder="Contoh: POP-JMB-001"
                                    autocomplete="off" required>
                                @error('kode_pop')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="nama_pop">Nama POP <span class="add-pop-required">*</span></label>
                                <input type="text" id="nama_pop" name="nama_pop"
                                    class="add-pop-input {{ $errors->has('nama_pop') ? 'is-invalid' : '' }}"
                                    value="{{ old('nama_pop', $pop->nama_pop) }}" placeholder="Contoh: POP Jambi Kota"
                                    autocomplete="off" required>
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
                                        @foreach (['Shelter', 'Shelter CKD', 'Shelter Permanen', 'Mini Shelter', 'ODC', 'Mini POP', 'Mikro POP', 'OLT Gantung'] as $opt)
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
                                        <option value="" disabled>Pilih Tipe POP</option>
                                        @foreach (['POP-SB', 'POP-A', 'POP-B', 'POP-D'] as $opt)
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

                            <div class="add-pop-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" id="latitude" name="latitude" inputmode="decimal"
                                    class="add-pop-input {{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                                    value="{{ old('latitude', $pop->latitude) }}" placeholder="Contoh: -1.610122" autocomplete="off">
                                @error('latitude')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="add-pop-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" id="longitude" name="longitude" inputmode="decimal"
                                    class="add-pop-input {{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                                    value="{{ old('longitude', $pop->longitude) }}" placeholder="Contoh: 103.613120" autocomplete="off">
                                @error('longitude')
                                    <span class="add-pop-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>{{-- /.add-pop-form-grid --}}

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
        (() => {
            const provinces = document.getElementById('provinsi');
            const provinceOther = document.getElementById('provinsi_lainnya');
            const cities = document.getElementById('kota_kabupaten');
            const cityOther = document.getElementById('kota_kabupaten_lainnya');
            const cityLists = {
                Jambi: ['Batanghari', 'Bungo', 'Jambi', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Sungai Penuh', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur', 'Tebo'],
                Sumsel: ['Lubuk Linggau', 'Musi Rawas'],
            };

            const setOtherField = (select, input) => {
                const isOther = select.value === '__other__';
                input.hidden = !isOther;
                input.required = isOther;
                if (!isOther) input.value = '';
            };
            const populateCities = (selected = '') => {
                const choices = cityLists[provinces.value] || [];
                cities.disabled = !provinces.value;
                cities.replaceChildren(new Option(choices.length ? 'Pilih kota/kabupaten' : 'Pilih provinsi terlebih dahulu', '', true, !selected));
                cities.options[0].disabled = true;
                choices.forEach((city) => cities.add(new Option(city, city, false, city === selected)));
                cities.add(new Option('Lainnya', '__other__', false, selected === '__other__'));
                cities.value = selected && [...cities.options].some((option) => option.value === selected) ? selected : '';
                setOtherField(cities, cityOther);
            };

            provinces.addEventListener('change', () => {
                setOtherField(provinces, provinceOther);
                populateCities();
            });
            cities.addEventListener('change', () => setOtherField(cities, cityOther));
            const initialCity = cities.dataset.selected || cities.value;
            if (provinces.value === '__other__') setOtherField(provinces, provinceOther);
            populateCities(initialCity);

            document.getElementById('editPopForm').addEventListener('submit', function () {
                [[provinces, provinceOther], [cities, cityOther]].forEach(([select, input]) => {
                    if (select.value !== '__other__') return;
                    const value = input.value.trim();
                    let option = [...select.options].find((item) => item.value === value);
                    if (!option) option = select.add(new Option(value, value));
                    select.value = value;
                });
            }, true);
        })();

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
