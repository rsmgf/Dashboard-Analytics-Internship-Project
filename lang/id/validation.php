<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi Bahasa Indonesia (Indonesian Validation Language Lines)
    |--------------------------------------------------------------------------
    |
    | Baris-baris bahasa berikut memuat pesan kesalahan default yang digunakan
    | oleh kelas validator. Pesan-pesan ini dirancang agar sopan, ramah,
    | dan mudah dipahami oleh pengguna awam.
    |
    */

    'accepted'             => ':attribute harus disetujui.',
    'accepted_if'          => ':attribute harus disetujui jika :other adalah :value.',
    'active_url'           => ':attribute bukan tautan (URL) yang valid.',
    'after'                => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa daftar data (array).',
    'ascii'                => ':attribute hanya boleh berisi karakter alfanumerik dan simbol single-byte.',
    'before'               => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min sampai :max item.',
        'file'    => 'Ukuran berkas :attribute harus antara :min sampai :max KB.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus berisi antara :min sampai :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai benar atau salah.',
    'can'                  => ':attribute berisi nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Kata sandi saat ini salah.',
    'date'                 => ':attribute harus berupa tanggal yang valid.',
    'date_equals'          => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'decimal'              => ':attribute harus memiliki :decimal angka desimal.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak bila :other bernilai :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits digit angka.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max digit.',
    'dimensions'           => 'Dimensi gambar :attribute tidak valid.',
    'distinct'             => ':attribute memiliki nilai duplikat yang sama dengan data lainnya pada form ini.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                 => 'Pilihan :attribute yang dipilih tidak valid.',
    'exists'               => 'Pilihan :attribute tidak ditemukan di sistem.',
    'extensions'           => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file'                 => ':attribute harus berupa berkas file.',
    'filled'               => ':attribute wajib memiliki nilai.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => 'Ukuran berkas :attribute harus lebih besar dari :value KB.',
        'numeric' => ':attribute harus bernilai lebih dari :value.',
        'string'  => ':attribute harus berisi lebih dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus memiliki minimal :value item.',
        'file'    => 'Ukuran berkas :attribute harus lebih besar atau sama dengan :value KB.',
        'numeric' => ':attribute harus bernilai minimal :value.',
        'string'  => ':attribute harus berisi minimal :value karakter.',
    ],
    'hex_color'            => ':attribute harus berupa kode warna heksadesimal yang valid.',
    'image'                => ':attribute harus berupa berkas gambar (foto).',
    'in'                   => 'Pilihan :attribute tidak valid.',
    'in_array'             => ':attribute tidak ada di dalam :other.',
    'integer'              => ':attribute harus berupa angka / bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'list'                 => ':attribute harus berupa daftar.',
    'lowercase'            => ':attribute harus menggunakan huruf kecil.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => 'Ukuran berkas :attribute harus kurang dari :value KB.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => 'Ukuran berkas :attribute tidak boleh lebih dari :value KB.',
        'numeric' => ':attribute tidak boleh lebih dari :value.',
        'string'  => ':attribute tidak boleh lebih dari :value karakter.',
    ],
    'mac_address'          => ':attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => 'Ukuran berkas :attribute maksimal :max KB (2 MB).',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string'  => ':attribute maksimal berisi :max karakter.',
    ],
    'mimes'                => 'Format berkas :attribute harus berupa: :values.',
    'mimetypes'            => 'Format berkas :attribute harus berupa: :values.',
    'min'                  => [
        'array'   => ':attribute minimal harus memiliki :min item.',
        'file'    => 'Ukuran berkas :attribute minimal :min KB.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal berisi :min karakter.',
    ],
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => 'Pilihan :attribute tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah bocor dalam kebocoran data. Silakan pilih :attribute yang lain.',
    ],
    'present'              => ':attribute wajib ada.',
    'prohibited'           => ':attribute tidak diizinkan untuk diisi.',
    'prohibited_if'        => ':attribute tidak diizinkan diisi bila :other adalah :value.',
    'prohibited_unless'    => ':attribute tidak diizinkan diisi kecuali :other ada di dalam :values.',
    'prohibits'            => ':attribute melarang :other untuk ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus memuat entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi bila :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi bila :other disetujui.',
    'required_unless'      => ':attribute wajib diisi kecuali :other berada di dalam :values.',
    'required_with'        => ':attribute wajib diisi bila :values terisi.',
    'required_with_all'    => ':attribute wajib diisi bila seluruh :values terisi.',
    'required_without'     => ':attribute wajib diisi bila :values tidak terisi.',
    'required_without_all' => ':attribute wajib diisi bila seluruh :values tidak terisi.',
    'same'                 => ':attribute dan :other harus cocok.',
    'size'                 => [
        'array'   => ':attribute harus memuat :size item.',
        'file'    => 'Ukuran berkas :attribute harus tepat :size KB.',
        'numeric' => ':attribute harus berukuran :size.',
        'string'  => ':attribute harus berisi :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari: :values.',
    'string'               => ':attribute harus berupa teks.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah terdaftar di sistem. Mohon gunakan data yang berbeda.',
    'uploaded'             => 'Berkas :attribute gagal diunggah. Pastikan ukuran tidak melebihi batas maksimal.',
    'uppercase'            => ':attribute harus menggunakan huruf besar.',
    'url'                  => ':attribute harus berupa format URL yang valid.',
    'ulid'                 => ':attribute harus berupa format ULID yang valid.',
    'uuid'                 => ':attribute harus berupa format UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Pemetaan Atribut Khusus (Custom Attributes)
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk mengganti nama field teknis (misal 'sn_rectifier')
    | menjadi sebutan yang ramah dan mudah dipahami user (misal 'Serial Number Rectifier').
    |
    */

    'attributes' => [
        'nama_pop'                 => 'Nama POP',
        'id_pop'                   => 'ID POP',
        'kota_kabupaten'           => 'Kota / Kabupaten',
        'tipe_pop'                 => 'Tipe POP',
        'jenis_bangunan'           => 'Jenis Bangunan',
        'alamat'                   => 'Alamat',
        'latitude'                 => 'Latitude',
        'longitude'                => 'Longitude',

        // Rectifier
        'sn_rectifier'             => 'Serial Number Rectifier',
        'foto_rectifier'           => 'Foto Rectifier',
        'kapasitas_slot'           => 'Kapasitas Slot Modul',
        'type_modul_controller'    => 'Tipe Modul Controller',
        'type_modul_power'         => 'Tipe Modul Power',
        'kapasitas_rectifier'      => 'Kapasitas Rectifier',
        'sn_modul'                 => 'Serial Number Modul',

        // Genset
        'merk_genset'              => 'Merk Genset',
        'sn_genset'                => 'Serial Number Genset',
        'tipe_engine'              => 'Tipe Engine',
        'sn_engine'                => 'Serial Number Engine',
        'tahun_pasang'             => 'Tahun Pasang',
        'tanggal_pm'               => 'Tanggal PM',
        'kapasitas_kva'            => 'Kapasitas kVA',
        'photo_genset'             => 'Foto Genset',
        'photo_engine'             => 'Foto Engine',
        'keterangan_gambar_genset' => 'Keterangan Foto Genset',
        'keterangan_gambar_engine' => 'Keterangan Foto Engine',

        // Battery
        'nomor_bank'               => 'Nomor Bank Baterai',
        'merk_battery'             => 'Merk Baterai',
        'tipe_battery'             => 'Tipe Baterai',
        'jenis_battery'            => 'Jenis Baterai',
        'kapasitas_battery'        => 'Kapasitas Baterai',
        'tanggal_uji_terakhir'     => 'Tanggal Uji Terakhir',
        'tanggal_penggantian'      => 'Tanggal Penggantian',
        'photo_battery'            => 'Foto Baterai',
        'status_uji'               => 'Status Uji Baterai',
        'tegangan'                 => 'Tegangan',

        // AC
        'merk_ac'                  => 'Merk AC',
        'type_ac'                  => 'Tipe AC',
        'jenis_freon'              => 'Jenis Freon',
        'tahun_manufaktur'         => 'Tahun Manufaktur',
        'pk'                       => 'Kapasitas PK',
        'photo_ac'                 => 'Foto AC',
        'tanggal_instalasi'        => 'Tanggal Instalasi',
        'tanggal_terakhir_pm'      => 'Tanggal Terakhir PM',

        // kWh
        'id_pelanggan'             => 'ID Pelanggan kWh',
        'daya'                     => 'Daya',
        'mcb_utama'                => 'MCB Utama',
        'photos'                   => 'Foto',
        'photos.*'                 => 'Foto',
        'captions'                 => 'Keterangan Foto',
        'captions.*'               => 'Keterangan Foto',

        // Umum
        'pic'                      => 'PIC',
        'merk'                     => 'Merk',
        'type'                     => 'Tipe',
        'model'                    => 'Model',
        'deskripsi'                => 'Deskripsi',
        'bentuk_fisik'             => 'Bentuk Fisik',
        'performa_baterai'         => 'Performa Baterai',
        'kapasitas_uji'            => 'Kapasitas Uji',
        'utilisasi'                => 'Utilisasi',
        'beban'                    => 'Beban',
        'name'                     => 'Nama',
        'email'                    => 'Alamat Email',
        'password'                 => 'Kata Sandi',
        'password_confirmation'    => 'Konfirmasi Kata Sandi',
        'current_password'         => 'Kata Sandi Saat Ini',

        // RMA
        'judul_rma'                => 'Judul RMA',
        'nama_pemohon'             => 'Nama Pemohon / Engineer',
        'nama_manager'             => 'Nama Supervisor / Manager',
        'is_material_rusak'        => 'Status Material Rusak',
        'ttd_pemohon'              => 'Foto Tanda Tangan Pemohon',
        'so_po'                    => 'No. Dokumen (SO/PO/IO)',
        'valuation_type'           => 'Valuation Type',
        'lokasi_asal'              => 'Lokasi Asal',
        'serial_number'            => 'Serial Number (SN)',
        'material_number'          => 'Material Number',
        'description'              => 'Deskripsi Kondisi',
        'kerusakan'                => 'Daftar Kerusakan',
        'alasan'                   => 'Alasan Pengajuan',
        'foto_material'            => 'Foto Material Utama',
        'foto_material.*'          => 'Foto Material',
        'foto_material_baru'       => 'Foto Material Baru',
        'foto_material_baru.*'     => 'Foto Material Baru',
        'hapus_foto'               => 'Foto yang Dihapus',

        // Admin & Hak Akses
        'role'                     => 'Role Pengguna',
        'roles'                    => 'Daftar Role',
        'permissions'              => 'Hak Akses (Permissions)',
        'permissions.*'            => 'Hak Akses',
    ],

];
