<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PopImport implements WithMultipleSheets, Import
{
    private PopSheetImport $sheetImport;

    public function __construct()
    {
        $this->sheetImport = new PopSheetImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->sheetImport, // Hanya proses Sheet ke-1 (index 0), sheet panduan diabaikan
        ];
    }

    public function getErrors(): array
    {
        return $this->sheetImport->getErrors();
    }

    public function getImportedCount(): int
    {
        return $this->sheetImport->getImportedCount();
    }
}
