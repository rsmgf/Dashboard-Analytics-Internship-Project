<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RMA - {{ $data->serial_number }}</title>
    <style>
        /* Margin halaman diperkecil dari 2cm menjadi 1.2cm */
        @page { size: A4 portrait; margin: 1.2cm; }
        
        /* Ukuran font dan jarak antar baris (line-height) diperkecil */
        body { font-family: "DejaVu Sans", sans-serif; font-size: 9pt; color: #333; line-height: 1.2; }
        
        h2 { text-align: center; text-decoration: underline; margin-bottom: 15px; font-size: 14pt;}
        
        /* Padding tabel */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 35px; font-size: 10pt; }
        .info-table td { padding: 4px; vertical-align: top; }
        
        .box { 
            display: inline-block; 
            width: 12px; 
            height: 12px; 
            border: 1px solid #000; 
            text-align: center; 
            line-height: 12px; 
            font-weight: bold; 
            margin-right: 2px; 
            font-size: 9pt;
        }
        .text-red { color: red; }
        
        /* Lebar tabel checkbox 95% agar lebih merapat */
        .wrapper-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .wrapper-table > tbody > tr > td { vertical-align: top; }
        
        .damage-table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        .damage-table td { padding: 3px 2px; vertical-align: top; } 
        
        .col-check { width: 6%; }
        .col-text { width: 94%; }

        .alasan-box {
            border: 1px solid #000;
            padding: 12px; /* Memberi ruang napas di dalam kotak */
            margin-top: 15px; /* Jarak antara kotak dan checkbox di atasnya */
            background-color: #fafafa; /* (Opsional) warna latar sangat tipis agar beda */
        }
        
        .page-break { page-break-before: always; }
        
        /* Area TTD */
        .ttd-area { width: 100%; text-align: center; margin-top: 20px; page-break-inside: avoid; }
        .ttd-space { height: 60px; } 
    </style>
</head>
<body>

    <h2>Return Material Authorization</h2>

    <table class="info-table">
        <tr><td style="width: 25%;">Nomor SO/PO</td><td>: {{ $data->so_po }}</td></tr>
        <tr><td>Valuation Type</td><td>: {{ $data->valuation_type }}</td></tr>
        <tr><td>Tanggal</td><td>: {{ $data->tanggal->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Lokasi Asal</td><td>: {{ $data->lokasi_asal }}</td></tr>
        <tr><td>Merk</td><td>: {{ $data->merk }}</td></tr>
        <tr><td>Type</td><td>: {{ $data->type }}</td></tr>
        <tr><td>Material Number</td><td>: {{ $data->material_number }}</td></tr>
        <tr><td>Description</td><td>: {{ $data->description }}</td></tr>
    </table>

    <p style="font-weight: bold; margin-bottom: 10px; font-size: 9.5pt;">
        Beri Tanda Checker Pada Kotak Jika Material Rusak 
        <span class="box" style="margin-left: 15px;">{{ $data->is_material_rusak ? '✓' : '' }}</span>
    </p>
    
    @php
        $kerusakan = $data->is_material_rusak ? ($data->kerusakan ?? []) : [];
    @endphp

<table class="wrapper-table">
        <tr>
            <!-- KOLOM KIRI: Berisi 12 Checkbox berderet rapi -->
            <td style="width: 38%; padding-right: 2px;">
                <table class="damage-table">
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Continue', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Continue</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Dead on Arrival', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Dead on Arrival</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Dead on Operational', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Dead on Operational</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('BER Indication', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">BER Indication*)</span></td><
                        /tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Software Error', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Software Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Tributary Error', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Tributary Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Channel Error', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Channel Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Port Error', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Port Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Tx Laser Faulty', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Tx Laser Faulty</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Rx Laser Faulty', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Rx Laser Faulty</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Physical Damage', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Physical Damage</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{{ in_array('Miscelaneous', $kerusakan) ? '✓' : '' }}</span></td>
                        <td class="col-text"><span class="text-red">Miscelaneous</span></td>
                    </tr>
                </table>
            </td>
        {{-- ALASAN --}}
        <td style="width: 62%; padding-left: 5px;">
                <table class="damage-table">
                    <tr><td class="col-check"><span class="box">{{ in_array('Intermittent', $kerusakan) ? '✓' : '' }}</span></td><td class="col-text"><span class="text-red">Intermittent</span></td></tr>
                    <tr><td class="col-check"><span class="box">{{ in_array('Rectifier faulty', $kerusakan) ? '✓' : '' }}</span></td><td class="col-text"><span class="text-red">Rectifier/Inverter faulty (Input/Output Voltage/Current Fault)</span></td></tr>
                    <tr><td class="col-check"><span class="box">{{ in_array('Charging switch', $kerusakan) ? '✓' : '' }}</span></td><td class="col-text"><span class="text-red">Charging/ static switch (Pengisian/Switch Rusak)</span></td></tr>
                    <tr><td class="col-check"><span class="box">{{ in_array('Battery faulty', $kerusakan) ? '✓' : '' }}</span></td><td class="col-text"><span class="text-red">Battery faulty (Battery Rusak/Drop)</span></td></tr>
                </table>

                <!-- Kotak Alasan ditaruh di luar tabel kerusakan kanan, tapi masih di dalam kolom kanan -->
                <div class="alasan-box">
                    <p style="margin-top: 0; margin-bottom: 8px; font-weight: bold;">Alasan / Keterangan Kerusakan:</p>
                    <p style="margin: 0; font-size: 9pt; color: #333; text-align: justify; line-height: 1.4;">
                        {{ $data->alasan ?? '-' }}
                    </p>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- TTD AREA -->
    <!-- Menggunakan CSS page-break-inside: avoid agar tidak terpotong setengah halaman -->
    <table class="ttd-area">
        <tr>
            <td style="width: 50%;">
                <p>Engineer Sign,</p>
                
                <!-- Menampilkan Gambar TTD Pemohon -->
                <div style="height: 60px; margin-top: 10px; margin-bottom: 5px;">
                    <img src="{{ public_path('storage/' . $data->ttd_pemohon) }}" style="max-height: 60px; max-width: 150px;">
                </div>
                
                <p style="text-decoration: underline; font-weight: bold;">{{ $data->nama_pemohon }}</p>
            </td>
            <td style="width: 50%;">
                <p>Manager on Duty/Local Manager/Supervisor Sign,</p>
                
                <!-- Untuk TTD Basah Atasan -->
                <div class="ttd-space"></div>
                
                <p style="text-decoration: underline; font-weight: bold;">{{ $data->nama_manager }}</p>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: FOTO -->
    <div class="page-break"></div>
    <h2 style="margin-bottom: 20px;">Lampiran Dokumentasi Material</h2>

    <table style="border: 1px solid #000; width: 100%; border-collapse: collapse;">
        @foreach($data->materials->chunk(2) as $chunk)
            <tr>
                @foreach($chunk as $material)
                    <td style="width: 50%; border: 1px solid #000; text-align: center; padding: 10px;">
                        <img src="{{ public_path('storage/' . $material->foto_path) }}" style="max-width: 90%; max-height: 220px; display: block; margin: 0 auto 8px;">
                        <p style="margin: 0; font-weight: bold; font-size: 10pt;">+{{ $material->serial_number }}</p>
                        <p style="margin: 0; font-size: 9pt;">Material SFP</p>
                    </td>
                @endforeach
                
                @if($chunk->count() == 1)
                    <td style="width: 50%; border: 1px solid #000;"></td>
                @endif
            </tr>
        @endforeach
    </table>

</body>
</html>