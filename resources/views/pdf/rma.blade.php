<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>RMA - {{ $data->so_po }}</title>
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

        .info-label {
            width: 34%;
        }

        .info-colon {
            width: 14px;
            text-align: center;
        }

        .info-value {
            width: auto;
        }

        .device-line {
            line-height: 1.35;
            white-space: normal;
        }

        .device-index {
            margin-right: 5px;
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

        .damage-signature-block {
            page-break-inside: avoid;
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

    @php
        $types = $data->types->values();
        $brands = $types->pluck('merk')->unique()->values();
        $serials = $types->flatMap(fn($type) => $type->serials)->values();
        $numberedTypes = $types->count() > 1;
        $typeLines = $types->map(fn($type) => $type->type)->values();
        $materialLines = $types->map(fn($type) => $type->material_number ?: '-')->values();
        $serialLines = $serials->map(fn($serial) => $serial->serial_number)->values();
    @endphp
    <table class="info-table">
        <tr>
            <td class="info-label">Nomor SO/PO</td><td class="info-colon">:</td><td class="info-value">{{ $data->so_po }}</td>
        </tr>
        <tr>
            <td class="info-label">Valuation Type</td><td class="info-colon">:</td><td class="info-value">{{ $data->valuation_type }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal</td><td class="info-colon">:</td><td class="info-value">{{ $data->tanggal->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Lokasi Asal</td><td class="info-colon">:</td><td class="info-value">{{ $data->lokasi_asal }}</td>
        </tr>
        <tr>
            <td class="info-label">Merk</td><td class="info-colon">:</td><td class="info-value">
                @foreach ($brands as $index => $brand)
                    <div class="device-line">@if ($brands->count() > 1)<span class="device-index">{{ $index + 1 }}.</span>@endif{{ $brand }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="info-label">Type</td><td class="info-colon">:</td><td class="info-value">
                @foreach ($typeLines as $index => $typeLine)
                    <div class="device-line">@if ($numberedTypes)<span class="device-index">{{ $index + 1 }}.</span>@endif{{ $typeLine }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="info-label">Serial Number</td><td class="info-colon">:</td><td class="info-value">
                @forelse ($serialLines as $index => $serialLine)
                    <div class="device-line">@if ($serials->count() > 1)<span class="device-index">{{ $index + 1 }}.</span>@endif{{ $serialLine }}</div>
                @empty
                    {{ $data->serial_number ?? '-' }}
                @endforelse
            </td>
        </tr>
        <tr>
            <td class="info-label">Material Number</td><td class="info-colon">:</td><td class="info-value">
                @foreach ($materialLines as $index => $materialLine)
                    <div class="device-line">@if ($numberedTypes)<span class="device-index">{{ $index + 1 }}.</span>@endif{{ $materialLine }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="info-label">Description</td><td class="info-colon">:</td><td class="info-value">{{ $data->description }}</td>
        </tr>
    </table>

    @php
        $checkImg = '<img src="' . public_path('images/check_black.png') . '" width="11" height="11" style="vertical-align: 1px;">';
        $kerusakan = $data->is_material_rusak ? $data->kerusakan ?? [] : [];
    @endphp

    <div class="damage-signature-block">
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
    </div>

    <!-- HALAMAN 2: FOTO -->
    <div class="page-break"></div>
    <h2 style="margin-bottom: 20px;">Lampiran Dokumentasi Material</h2>

    <table style="border: 1px solid #000; width: 100%; border-collapse: collapse;">
        @php $photoMaterials = $types->flatMap(fn($type) => $type->serials->flatMap(fn($serial) => $serial->materials))->values(); @endphp
        @foreach ($photoMaterials->chunk(2) as $chunk)
            <tr>
                @foreach ($chunk as $material)
                    <td style="width: 50%; border: 1px solid #000; text-align: center; padding: 10px;">
                        <img src="{{ public_path('storage/' . $material->foto_path) }}"
                            style="max-width: 90%; max-height: 220px; display: block; margin: 0 auto 8px;">
                        <p style="margin: 0; font-weight: bold; font-size: 10pt;">+{{ $material->serial_number }}</p>
                        <p style="margin: 0; font-size: 9pt;">{{ $material->serial?->type?->type ?? '-' }}</p>
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
