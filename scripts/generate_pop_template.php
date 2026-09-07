<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Template Import POP');

// ── Headers ──────────────────────────────────────
$headers = ['Provinsi', 'Kota/Kabupaten', 'ID POP', 'Nama POP', 'Building', 'Tipe POP'];
$colWidths = [20, 22, 20, 28, 20, 15];

foreach ($headers as $i => $header) {
    $col = chr(65 + $i);
    $sheet->setCellValue($col . '1', $header);
    $sheet->getColumnDimension($col)->setWidth($colWidths[$i]);
}

// ── Header Styling ────────────────────────────────
$sheet->getStyle('A1:F1')->applyFromArray([
    'font' => [
        'bold'  => true,
        'size'  => 11,
        'color' => ['argb' => 'FFFFFFFF'],
        'name'  => 'Calibri',
    ],
    'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF0056D2'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical'   => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color'       => ['argb' => 'FFBFDBFE'],
        ],
    ],
]);

$sheet->getRowDimension(1)->setRowHeight(24);

// ── Contoh Baris 1 ───────────────────────────────
$sheet->setCellValue('A2', 'Jambi');
$sheet->setCellValue('B2', 'Kota Jambi');
$sheet->setCellValue('C2', 'POP_1MBN10004');
$sheet->setCellValue('D2', 'POP Jambi Kota');
$sheet->setCellValue('E2', 'Shelter');
$sheet->setCellValue('F2', 'POP-SB');

// ── Contoh Baris 2 ───────────────────────────────
$sheet->setCellValue('A3', 'Jambi');
$sheet->setCellValue('B3', 'Kabupaten Batanghari');
$sheet->setCellValue('C3', 'POP_1MBN10005');
$sheet->setCellValue('D3', 'POP Muara Bulian');
$sheet->setCellValue('E3', 'ODC');
$sheet->setCellValue('F3', 'POP-A');

// ── Data Row Styling ─────────────────────────────
$sheet->getStyle('A2:F3')->applyFromArray([
    'font' => ['size' => 10, 'name' => 'Calibri'],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color'       => ['argb' => 'FFE2E8F0'],
        ],
    ],
    'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFF8FAFC'],
    ],
]);

// ── Freeze header row ────────────────────────────
$sheet->freezePane('A2');

// ── Keterangan sheet (sheet kedua) ───────────────
$infoSheet = $spreadsheet->createSheet();
$infoSheet->setTitle('Panduan & Nilai Valid');

$infoSheet->setCellValue('A1', 'PANDUAN PENGISIAN TEMPLATE IMPORT POP');
$infoSheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF0056D2']],
]);
$infoSheet->getRowDimension(1)->setRowHeight(22);

$guide = [
    ['Kolom', 'Keterangan', 'Status', 'Nilai yang Diizinkan'],
    ['Provinsi', 'Nama provinsi POP', 'WAJIB', 'Teks bebas'],
    ['Kota/Kabupaten', 'Nama kota atau kabupaten', 'WAJIB', 'Teks bebas'],
    ['ID POP', 'Kode unik POP (tidak boleh duplikat)', 'WAJIB', 'Teks bebas, unik'],
    ['Nama POP', 'Nama lengkap POP', 'WAJIB', 'Teks bebas'],
    ['Building', 'Jenis bangunan POP', 'Opsional', 'Shelter | Shelter CKD | Shelter Permanen | Mini Shelter | ODC | Mini POP | Mikro POP | OLT Gantung'],
    ['Tipe POP', 'Tipe klasifikasi POP', 'Opsional', 'POP-SB | POP-A | POP-B | POP-D'],
];

foreach ($guide as $r => $row) {
    foreach ($row as $c => $val) {
        $infoSheet->setCellValue(chr(65 + $c) . ($r + 3), $val);
    }
}

$infoSheet->getStyle('A3:D3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E293B']],
]);

$infoSheet->getColumnDimension('A')->setWidth(20);
$infoSheet->getColumnDimension('B')->setWidth(35);
$infoSheet->getColumnDimension('C')->setWidth(12);
$infoSheet->getColumnDimension('D')->setWidth(85);

// ── Simpan ───────────────────────────────────────
$spreadsheet->setActiveSheetIndex(0);

$dir = __DIR__ . '/../public/templates';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$writer = new Xlsx($spreadsheet);
$writer->save($dir . '/template_import_pop.xlsx');

echo "Template Excel berhasil dibuat: public/templates/template_import_pop.xlsx\n";
