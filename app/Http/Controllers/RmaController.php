<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rma;
use Barryvdh\DomPDF\Facade\Pdf;

class RmaController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pemohon'      => 'required|string',
            'nama_manager'      => 'required|string', 
            'is_material_rusak' => 'required|boolean', // (0 atau 1)
            'ttd_pemohon'       => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'so_po'             => 'required|string',
            'valuation_type'    => 'required|in:ex-project,dismantle,rusak-L,rusak-TL',
            'tanggal'           => 'required|date',
            'lokasi_asal'       => 'required|string',
            'merk'              => 'required|string',
            'type'              => 'required|string',
            'material_number'   => 'nullable|string',
            'description'       => 'nullable|string',
            'kerusakan'         => 'nullable|array', // Boleh kosong jika tidak rusak
            'alasan'            => 'nullable|string',
            'serial_number'     => 'required|array',
            'serial_number.*'   => 'required|string',
            'foto_material'     => 'required|array',
            'foto_material.*'   => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $ttdPath = $request->file('ttd_pemohon')->store('signatures', 'public');

        // 1. Simpan data utama ke tabel rmas
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
            'material_number'   => $validatedData['material_number'],
            'description'       => $validatedData['description'],
            'kerusakan'         => $validatedData['kerusakan'] ?? null,  // ?? null untuk jaga-jaga jika kosong
            'alasan'            => $validatedData['alasan'] ?? null,
        ]);

        // 2. Looping array foto dan serial number untuk disimpan ke rma_materials
        foreach ($request->file('foto_material') as $index => $file) {
            $path = $file->store('material_images', 'public');
            
            $rma->materials()->create([
                'serial_number' => $validatedData['serial_number'][$index],
                'foto_path'     => $path,
            ]);
        }

        return response()->json(['message' => 'Data RMA & Multi-Foto berhasil disimpan.'], 201);
    }

    public function generatePdf($id)
    {
        // 1. Ambil data RMA beserta relasi foto-fotonya (materials)
        $data = Rma::with('materials')->findOrFail($id);

        // 2. Load file blade PDF yang sudah kita buat tadi
        $pdf = Pdf::loadView('pdf.rma', compact('data'));

        // 3. Tampilkan PDF langsung di browser
        return $pdf->stream('RMA_' . $data->serial_number . '.pdf');
    }
}
