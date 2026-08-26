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
        return view('rma.rma-create');
    }

    public function store(StoreRmaRequest $request)
    {
        $validatedData = $request->validated();

        // 1. Simpan tanda tangan
        $ttdPath = $request->file('ttd_pemohon')->store('signatures', 'public');

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
        return $pdf->stream('RMA_' . $data->id . '.pdf');
    }

    public function generatePdf($id)
    {
        $data = Rma::with('materials')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.rma', compact('data'));
        return $pdf->stream('RMA_' . $data->id . '.pdf');
    }

    public function downloadPdf($id)
    {
        $data = Rma::with('materials')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.rma', compact('data'));
        return $pdf->download('RMA_' . $data->id . '.pdf');
    }
}