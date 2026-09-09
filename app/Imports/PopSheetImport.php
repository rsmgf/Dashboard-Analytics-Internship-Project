<?php

namespace App\Imports;

use App\Models\Pop;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PopSheetImport implements ToCollection, WithStartRow, WithChunkReading
{
    // Nilai valid untuk jenis_bangunan
    const VALID_BANGUNAN = [
        'Shelter', 'Shelter CKD', 'Shelter Permanen',
        'Mini Shelter', 'ODC', 'Mini POP', 'Mikro POP', 'OLT Gantung',
    ];

    // Nilai valid untuk tipe_pop
    const VALID_TIPE = ['POP-SB', 'POP-A', 'POP-B', 'POP-D'];

    private array $errors = [];
    private int $importedCount = 0;

    // Mulai baca dari baris ke-2 (skip header)
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * Proses koleksi baris Excel.
     * Urutan kolom:
     *   A=0: Provinsi
     *   B=1: Kota/Kabupaten
     *   C=2: ID POP
     *   D=3: Nama POP
     *   E=4: Building
     *   F=5: Tipe POP
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $rowIndex => $row) {
            $actualRow = $rowIndex + 2; // +2 karena header di baris 1

            // Lewati baris yang sepenuhnya kosong
            $hasContent = false;
            foreach ($row as $cell) {
                if (!is_null($cell) && trim((string) $cell) !== '') {
                    $hasContent = true;
                    break;
                }
            }
            if (!$hasContent) continue;

            // Ambil nilai tiap kolom
            $provinsi      = trim((string) ($row[0] ?? ''));
            $kotaKabupaten = trim((string) ($row[1] ?? ''));
            $kodePop       = trim((string) ($row[2] ?? ''));
            $namaPop       = trim((string) ($row[3] ?? ''));
            $jenisBangunan = isset($row[4]) ? trim((string) $row[4]) : null;
            $tipePop       = isset($row[5]) ? trim((string) $row[5]) : null;

            // Validasi kolom wajib
            $rowErrors = [];
            if (empty($provinsi))      $rowErrors[] = 'Provinsi tidak boleh kosong';
            if (empty($kotaKabupaten)) $rowErrors[] = 'Kota/Kabupaten tidak boleh kosong';
            if (empty($kodePop))       $rowErrors[] = 'ID POP tidak boleh kosong';
            if (empty($namaPop))       $rowErrors[] = 'Nama POP tidak boleh kosong';

            if (!empty($rowErrors)) {
                $this->errors[] = "Baris {$actualRow}: " . implode(', ', $rowErrors);
                continue;
            }

            // Cek duplikat ID POP
            if (Pop::where('kode_pop', $kodePop)->exists()) {
                $this->errors[] = "Baris {$actualRow}: ID POP '{$kodePop}' sudah ada di database, dilewati.";
                continue;
            }

            // Validasi nilai opsional — jika tidak valid, set null
            if ($jenisBangunan && !in_array($jenisBangunan, self::VALID_BANGUNAN)) {
                $jenisBangunan = null;
            }
            if ($tipePop && !in_array($tipePop, self::VALID_TIPE)) {
                $tipePop = null;
            }

            // Simpan ke DB
            Pop::create([
                'provinsi'       => $provinsi,
                'kota_kabupaten' => $kotaKabupaten,
                'kode_pop'       => $kodePop,
                'nama_pop'       => $namaPop,
                'jenis_bangunan' => $jenisBangunan ?: null,
                'tipe_pop'       => $tipePop ?: null,
            ]);

            $this->importedCount++;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
