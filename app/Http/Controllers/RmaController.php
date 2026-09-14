<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRmaRequest;
use App\Models\Rma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RmaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'id');
        $direction = strtolower($request->query('direction')) === 'desc' ? 'desc' : 'asc';

        $query = Rma::with('materials')
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('so_po', 'like', "%{$search}%")
                        ->orWhere('lokasi_asal', 'like', "%{$search}%");
                });
            });

        // Jika sort tanggal dipilih, urutkan berdasarkan kolom tanggal DAN jam pembuatan (created_at)
        if ($sort === 'tanggal') {
            $query->orderBy('tanggal', $direction)->orderBy('created_at', $direction);
        } else {
            $query->orderBy('id', $direction);
        }

        $rmas = $query->paginate(8)->withQueryString();

        return view('rma.rma', compact('rmas', 'sort', 'direction'));
    }

    public function create()
    {
        $managers = Rma::select('nama_manager')
            ->whereNotNull('nama_manager')
            ->where('nama_manager', '!=', '')
            ->distinct()
            ->orderBy('nama_manager')
            ->pluck('nama_manager');

        return view('rma.rma-create', compact('managers'));
    }

    public function store(StoreRmaRequest $request)
    {
        $validatedData = $request->validated();

        // 1. Simpan tanda tangan jika diunggah (opsional karena tanda tangan basah fisik)
        $ttdPath = $request->hasFile('ttd_pemohon')
            ? $request->file('ttd_pemohon')->store('signatures', 'local')
            : null;

        // 2. Simpan data utama
        $rma = Rma::create([
            'nama_pemohon'      => $validatedData['nama_pemohon'],
            'nama_manager'      => $validatedData['nama_manager'],
            'is_material_rusak' => $validatedData['is_material_rusak'],
            'ttd_pemohon'       => $ttdPath,
            'so_po'             => $validatedData['so_po'],
            'valuation_type'    => $validatedData['valuation_type'],
            'tanggal'           => $validatedData['tanggal'],
            'lokasi_asal'       => $validatedData['lokasi_asal'],
            'merk'              => $validatedData['merk'],
            'type'              => $validatedData['type'],
            'material_number'   => $validatedData['material_number'] ?? null,
            'description'       => $validatedData['description'] ?? null,
            'kerusakan'         => $validatedData['kerusakan'] ?? null,
            'alasan'            => $validatedData['alasan'] ?? null,
        ]);

        // 3. Simpan foto material
        foreach ($request->file('foto_material') as $file) {
            $path = $file->store('material_images', 'public');

            $rma->materials()->create([
                'serial_number' => $validatedData['serial_number'],
                'foto_path'     => $path,
            ]);
        }

        // Ambil data lengkap & generate PDF
        $data = Rma::with('materials')->findOrFail($rma->id);

        // Jika request dari JS fetch (form submit via AJAX), kembalikan URL PDF
        if ($request->expectsJson()) {
            return response()->json([
                'pdf_url'      => route('rma.pdf', $rma->id),
                'redirect_url' => route('rma'),
            ]);
        }

        // Fallback: stream PDF langsung
        $pdf = Pdf::loadView('pdf.rma', compact('data'));
        return $pdf->stream($this->formatRmaPdfFilename($data));
    }

    public function generatePdf($id)
    {
        $data = Rma::with('materials')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.rma', compact('data'));
        return $pdf->stream($this->formatRmaPdfFilename($data));
    }

    public function downloadPdf($id)
    {
        $data = Rma::with('materials')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.rma', compact('data'));
        return $pdf->download($this->formatRmaPdfFilename($data));
    }

    public function downloadBatch(Request $request)
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $ids = array_filter(array_map('intval', (array) $ids));

        if (empty($ids)) {
            return redirect()->route('rma')->with('error', 'Pilih minimal satu data RMA untuk didownload.');
        }

        $rmas = Rma::with('materials')->whereIn('id', $ids)->get();
        if ($rmas->isEmpty()) {
            return redirect()->route('rma')->with('error', 'Data RMA tidak ditemukan.');
        }

        // Jika hanya 1 data yang dipilih, download langsung sebagai file PDF tunggal
        if ($rmas->count() === 1) {
            $rma = $rmas->first();
            $pdf = Pdf::loadView('pdf.rma', ['data' => $rma]);
            $filename = $this->formatRmaPdfFilename($rma);
            return $pdf->download($filename);
        }

        // Jika lebih dari 1 data, kemas ke dalam file ZIP
        $zipFileName = 'RMA_Batch_' . date('Ymd_His') . '.zip';
        $zipDirectory = storage_path('app/temp_zip');
        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0755, true);
        }
        $zipFilePath = $zipDirectory . '/' . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $usedNames = [];
            foreach ($rmas as $rma) {
                $pdf = Pdf::loadView('pdf.rma', ['data' => $rma]);
                $pdfContent = $pdf->output();
                $baseName = $this->formatRmaPdfFilename($rma);

                // Cegah bentrok nama jika ada SO/PO dan SN identik di dalam file ZIP
                $pdfName = $baseName;
                $counter = 1;
                while (isset($usedNames[$pdfName])) {
                    $info = pathinfo($baseName);
                    $pdfName = $info['filename'] . "_{$counter}." . $info['extension'];
                    $counter++;
                }
                $usedNames[$pdfName] = true;

                $zip->addFromString($pdfName, $pdfContent);
            }
            $zip->close();

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }

        return redirect()->route('rma')->with('error', 'Gagal membuat file arsip ZIP.');
    }

    /**
     * Format nama file PDF RMA berdasarkan Nomor SO/PO dan Serial Number
     */
    private function formatRmaPdfFilename($rma): string
    {
        $soPo = preg_replace('/[^a-zA-Z0-9_-]/', '-', trim($rma->so_po ?? ''));
        $sn   = preg_replace('/[^a-zA-Z0-9_-]/', '-', trim($rma->serial_number ?? ''));

        // Bersihkan multiple dash berturut-turut (misal 'SP2K---001' jadi 'SP2K-001')
        $soPo = trim(preg_replace('/-+/', '-', $soPo), '-');
        $sn   = trim(preg_replace('/-+/', '-', $sn), '-');

        $parts = ['RMA'];
        if (!empty($soPo)) {
            $parts[] = $soPo;
        }
        if (!empty($sn)) {
            $parts[] = $sn;
        } else {
            $parts[] = 'ID' . $rma->id;
        }

        return implode('_', $parts) . '.pdf';
    }
}