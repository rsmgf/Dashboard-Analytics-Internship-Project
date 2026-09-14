<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>RMA - {{ $data->serial_number }}</title>
    <style>
        /* Margin halaman diperkecil dari 2cm menjadi 1.2cm */
        @page {
            size: A4 portrait;
            margin: 1.2cm;
        }

        /* Ukuran font dan jarak antar baris (line-height) diperkecil */
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.2;
        }

        h2 {
            text-align: center;
            text-decoration: underline;
            margin-bottom: 15px;
            font-size: 14pt;
        }

        /* Padding tabel */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            font-size: 10pt;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
        }

        .box {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            text-align: center;
            line-height: 14px;
            vertical-align: middle;
            margin-right: 3px;
        }

        .text-red {
            color: red;
        }

        /* Lebar tabel checkbox 95% agar lebih merapat */
        .wrapper-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .wrapper-table>tbody>tr>td {
            vertical-align: top;
        }

        .damage-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .damage-table td {
            padding: 3px 2px;
            vertical-align: top;
        }

        .col-check {
            width: 6%;
        }

        .col-text {
            width: 94%;
        }

        .alasan-box {
            border: 1px solid #000;
            padding: 12px;
            /* Memberi ruang napas di dalam kotak */
            margin-top: 15px;
            /* Jarak antara kotak dan checkbox di atasnya */
            background-color: #fafafa;
            /* (Opsional) warna latar sangat tipis agar beda */
        }

        .page-break {
            page-break-before: always;
        }

        /* Area TTD */
        .ttd-area {
            width: 100%;
            text-align: center;
            margin-top: 60px;
            page-break-inside: avoid;
        }

        .ttd-space {
            height: 60px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
        <tr>
            <td style="width: 140px; vertical-align: middle;">
                <!-- Spacer kiri agar judul tepat berada di tengah -->
            </td>
            <td style="vertical-align: middle; text-align: center;">
                <h2 style="margin: 0; text-decoration: underline; font-size: 14pt; text-align: center;">Return Material Authorization</h2>
            </td>
            <td style="width: 140px; vertical-align: middle; text-align: right;">
                @if (file_exists(public_path('images/logo-iconplus-dark.png')))
                    <img src="{{ public_path('images/logo-iconplus-dark.png') }}" style="height: 36px; max-width: 140px; object-fit: contain;">
                @elseif (file_exists(public_path('images/logo-iconplus.png')))
                    <img src="{{ public_path('images/logo-iconplus.png') }}" style="height: 36px; max-width: 140px; object-fit: contain;">
                @endif
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 25%;">Nomor SO/PO</td>
            <td>: {{ $data->so_po }}</td>
        </tr>
        <tr>
            <td>Valuation Type</td>
            <td>: {{ $data->valuation_type }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $data->tanggal->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lokasi Asal</td>
            <td>: {{ $data->lokasi_asal }}</td>
        </tr>
        <tr>
            <td>Merk</td>
            <td>: {{ $data->merk }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>: {{ $data->type }}</td>
        </tr>
        <tr>
            <td>Material Number</td>
            <td>: {{ $data->material_number }}</td>
        </tr>
        <tr>
            <td>Description</td>
            <td>: {{ $data->description }}</td>
        </tr>
    </table>

    @php
        $checkImg = '<img src="' . public_path('images/check_black.png') . '" width="11" height="11" style="vertical-align: 1px;">';
        $kerusakan = $data->is_material_rusak ? $data->kerusakan ?? [] : [];
    @endphp

    <p style="margin-bottom: 10px; font-size: 9.5pt;">
        Beri Tanda Checker Pada Kotak Jika Material Rusak
        <span class="box" style="margin-left: 15px;">{!! $data->is_material_rusak ? $checkImg : '' !!}</span>
    </p>

    <table class="wrapper-table">
        <tr>
            <!-- KOLOM KIRI: Berisi 12 Checkbox berderet rapi -->
            <td style="width: 38%; padding-right: 2px;">
                <table class="damage-table">
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Continue', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Continue</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Dead on Arrival', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Dead on Arrival</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Dead on Operational', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Dead on Operational</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('BER Indication', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">BER Indication*)</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Software Error', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Software Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Tributary Error', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Tributary Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Channel Error', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Channel Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Port Error', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Port Error</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Tx Laser Faulty', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Tx Laser Faulty</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Rx Laser Faulty', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Rx Laser Faulty</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Physical Damage', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Physical Damage</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Miscelaneous', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Miscelaneous</span></td>
                    </tr>
                </table>
            </td>
            {{-- ALASAN --}}
            <td style="width: 62%; padding-left: 5px;">
                <table class="damage-table">
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Intermittent', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Intermittent</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Rectifier faulty', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Rectifier/Inverter faulty (Input/Output Voltage/Current Fault)</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Charging switch', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Charging/ static switch (Pengisian/Switch Rusak)</span></td>
                    </tr>
                    <tr>
                        <td class="col-check"><span class="box">{!! in_array('Battery faulty', $kerusakan) ? $checkImg : '' !!}</span></td>
                        <td class="col-text"><span class="text-red">Battery faulty (Battery Rusak/Drop)</span></td>
                    </tr>
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

    <!-- TTD AREA (TANDA TANGAN BASAH FISIK) -->
    <!-- Menggunakan CSS page-break-inside: avoid agar tidak terpotong setengah halaman -->
    <table class="ttd-area" style="width: 100%; margin-top: 50px;">
        <!-- BARIS 1: JUDUL JABATAN -->
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <p style="margin: 0;">Engineer Sign,</p>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <p style="margin: 0;">Manager on Duty / Local Manager / Supervisor Sign,</p>
            </td>
        </tr>

        <!-- BARIS 2: RUANG TANDA TANGAN BASAH (TINGGI 75px) -->
        <tr>
            <td style="width: 50%; vertical-align: bottom; height: 75px;">
                <!-- Ruang tanda tangan basah Engineer -->
            </td>
            
            <td style="width: 50%; vertical-align: bottom; height: 75px;">
                <!-- Ruang tanda tangan basah Manager -->
            </td>
        </tr>

        <!-- BARIS 3: NAMA JELAS -->
        <tr>
            <td style="width: 50%; vertical-align: bottom;">
                <p style="margin: 0; margin-top: 5px; text-decoration: underline;">( {{ $data->nama_pemohon }} )</p>
            </td>
            
            <td style="width: 50%; vertical-align: bottom;">
                <p style="margin: 0; margin-top: 5px; text-decoration: underline;">( {{ $data->nama_manager }} )</p>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: FOTO -->
    <div class="page-break"></div>
    <h2 style="margin-bottom: 20px;">Lampiran Dokumentasi Material</h2>

    <table style="border: 1px solid #000; width: 100%; border-collapse: collapse;">
        @foreach ($data->materials->chunk(2) as $chunk)
            <tr>
                @foreach ($chunk as $material)
                    <td style="width: 50%; border: 1px solid #000; text-align: center; padding: 10px;">
                        <img src="{{ public_path('storage/' . $material->foto_path) }}"
                            style="max-width: 90%; max-height: 220px; display: block; margin: 0 auto 8px;">
                        <p style="margin: 0; font-weight: bold; font-size: 10pt;">+{{ $material->serial_number }}</p>
                        <p style="margin: 0; font-size: 9pt;">Material SFP</p>
                    </td>
                @endforeach

                @if ($chunk->count() == 1)
                    <td style="width: 50%; border: 1px solid #000;"></td>
                @endif
            </tr>
        @endforeach
    </table>

</body>

</html>
