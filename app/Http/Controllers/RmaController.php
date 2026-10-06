<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRmaRequest;
use App\Models\Rma;
use App\Models\RmaMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RmaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdminOrManager = $user && ($user->hasRole(['super_admin', 'manajer']) || $user->role === 'super_admin' || $user->role === 'manajer');

        $search    = $request->query('search');
        $sort      = $request->query('sort', 'id');
        $direction = strtolower($request->query('direction')) === 'desc' ? 'desc' : 'asc';
        // Filter khusus Super Admin/Manager: 'semua' (default) atau 'milik_saya'
        $tampil    = $request->query('tampil', 'semua');
        $filter    = $request->query('filter'); // 'hari_ini'
        $dateFrom  = $request->query('date_from');
        $dateTo    = $request->query('date_to');

        $query = Rma::with(['materials', 'user'])
            ->when(!$isSuperAdminOrManager, function ($q) use ($user) {
                // Teknisi/Karyawan hanya melihat RMA miliknya
                $q->where(function ($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->orWhere('nama_pemohon', $user->name);
                });
            })
            ->when($isSuperAdminOrManager && $tampil === 'milik_saya', function ($q) use ($user) {
                // Super Admin memilih filter "Milik Saya"
                $q->where(function ($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->orWhere('nama_pemohon', $user->name);
                });
            })
            ->when($filter === 'hari_ini', function ($q) {
                $today = now()->format('Y-m-d');
                $q->where(function ($sub) use ($today) {
                    $sub->whereDate('created_at', $today)
                        ->orWhereDate('tanggal', $today);
                });
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('judul_rma', 'like', "%{$search}%")
                        ->orWhere('so_po', 'like', "%{$search}%")
                        ->orWhere('lokasi_asal', 'like', "%{$search}%")
                        ->orWhere('nama_pemohon', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->where(function ($sub) use ($dateFrom) {
                    $sub->whereDate('created_at', '>=', $dateFrom)
                        ->orWhereDate('tanggal', '>=', $dateFrom);
                });
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                $q->where(function ($sub) use ($dateTo) {
                    $sub->whereDate('created_at', '<=', $dateTo)
                        ->orWhereDate('tanggal', '<=', $dateTo);
                });
            });

        if ($sort === 'tanggal') {
            $query->orderBy('tanggal', $direction)->orderBy('created_at', $direction);
        } else {
            $query->orderBy('id', $direction);
        }

        $perPage = 8;
        $rmas = $query->paginate($perPage)->withQueryString();

        return view('rma.rma', compact('rmas', 'sort', 'direction', 'isSuperAdminOrManager', 'tampil', 'filter', 'dateFrom', 'dateTo'));
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

        $noDokumen = trim($validatedData['so_po'] ?? '');
        $lokasiAsal = trim($validatedData['lokasi_asal'] ?? 'POP');
        $prefix = stripos($noDokumen, 'RMA') === 0 ? '' : 'RMA ';
        $defaultJudul = ($prefix . ($noDokumen ?: 'Dokumen')) . ' - ' . ($lokasiAsal ?: 'POP');

        $judulRma = !empty($validatedData['judul_rma'])
            ? trim($validatedData['judul_rma'])
            : $defaultJudul;

        // 2. Simpan data utama
        $rma = Rma::create([
            'judul_rma'         => $judulRma,
            'user_id'           => auth()->id(),
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
            'serial_number'     => $validatedData['serial_number'],
            'material_number'   => $validatedData['material_number'] ?? null,
            'description'       => $validatedData['description'] ?? null,
            'kerusakan'         => $validatedData['kerusakan'] ?? null,
            'alasan'            => $validatedData['alasan'] ?? null,
        ]);

        // 3. Simpan foto material (jika ada)
        if ($request->hasFile('foto_material')) {
            foreach ($request->file('foto_material') as $file) {
                $path = $file->store('material_images', 'public');

                $rma->materials()->create([
                    'serial_number' => $validatedData['serial_number'],
                    'foto_path'     => $path,
                ]);
            }
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

    public function edit($id)
    {
        $user = auth()->user();
        $rma  = Rma::with('materials')->findOrFail($id);

        $isSuperAdminOrManager = $user && ($user->hasRole(['super_admin', 'manajer']) || in_array($user->role, ['super_admin', 'manajer']));

        // Teknisi hanya bisa edit miliknya sendiri
        if (!$isSuperAdminOrManager && $rma->user_id && $rma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit RMA ini.');
        }

        $managers = Rma::select('nama_manager')
            ->whereNotNull('nama_manager')
            ->where('nama_manager', '!=', '')
            ->distinct()
            ->orderBy('nama_manager')
            ->pluck('nama_manager');

        return view('rma.rma-edit', compact('rma', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $rma  = Rma::with('materials')->findOrFail($id);

        $isSuperAdminOrManager = $user && ($user->hasRole(['super_admin', 'manajer']) || in_array($user->role, ['super_admin', 'manajer']));

        // Teknisi hanya bisa update miliknya sendiri
        if (!$isSuperAdminOrManager && $rma->user_id && $rma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah RMA ini.');
        }

        $request->validate([
            'judul_rma'         => 'nullable|string|max:255',
            'nama_pemohon'      => 'required|string|max:255',
            'nama_manager'      => 'required|string|max:255',
            'is_material_rusak' => 'nullable|boolean',
            'so_po'             => 'required|string|max:255',
            'valuation_type'    => 'required|in:ex-project,dismantle,rusak-L,rusak-TL',
            'tanggal'           => 'required|date',
            'lokasi_asal'       => 'required|string|max:255',
            'merk'              => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'material_number'   => 'nullable|string|max:255',
            'description'       => 'required|string',
            'kerusakan'         => 'nullable|array',
            'alasan'            => 'nullable|string',
            'serial_number'     => 'required|string|max:255',
            'foto_material_baru.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hapus_foto'        => 'nullable|array',
        ]);

        $noDokumen = trim($request->so_po ?? '');
        $lokasiAsal = trim($request->lokasi_asal ?? 'POP');
        $prefix = stripos($noDokumen, 'RMA') === 0 ? '' : 'RMA ';
        $defaultJudul = ($prefix . ($noDokumen ?: 'Dokumen')) . ' - ' . ($lokasiAsal ?: 'POP');

        $judulRma = !empty($request->judul_rma)
            ? trim($request->judul_rma)
            : $defaultJudul;

        $rma->update([
            'judul_rma'         => $judulRma,
            'nama_pemohon'      => $request->nama_pemohon,
            'nama_manager'      => $request->nama_manager,
            'is_material_rusak' => $request->is_material_rusak ?? $rma->is_material_rusak,
            'so_po'             => $request->so_po,
            'valuation_type'    => $request->valuation_type,
            'tanggal'           => $request->tanggal,
            'lokasi_asal'       => $request->lokasi_asal,
            'merk'              => $request->merk,
            'type'              => $request->type,
            'serial_number'     => $request->serial_number,
            'material_number'   => $request->material_number,
            'description'       => $request->description,
            'kerusakan'         => $request->kerusakan,
            'alasan'            => $request->alasan,
        ]);

        // Hapus foto yang dipilih untuk dihapus
        if ($request->has('hapus_foto')) {
            foreach ($request->hapus_foto as $materialId) {
                $material = RmaMaterial::find($materialId);
                if ($material && $material->rma_id === $rma->id) {
                    Storage::disk('public')->delete($material->foto_path);
                    $material->delete();
                }
            }
        }

        // Tambah foto baru jika ada
        if ($request->hasFile('foto_material_baru')) {
            foreach ($request->file('foto_material_baru') as $file) {
                $path = $file->store('material_images', 'public');
                $rma->materials()->create([
                    'serial_number' => $request->serial_number,
                    'foto_path'     => $path,
                ]);
            }
        }

        // Update serial_number di semua material
        $rma->materials()->update(['serial_number' => $request->serial_number]);

        return redirect()->route('rma')->with('success', 'Data RMA berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $rma  = Rma::with('materials')->findOrFail($id);

        $isSuperAdminOrManager = $user && ($user->hasRole(['super_admin', 'manajer']) || in_array($user->role, ['super_admin', 'manajer']));

        // Teknisi hanya bisa hapus miliknya sendiri
        if (!$isSuperAdminOrManager) {
            $isOwner = ($rma->user_id && $rma->user_id === $user->id)
                    || (!$rma->user_id && $rma->nama_pemohon === $user->name);
            if (!$isOwner) {
                if (request()->expectsJson()) {
                    return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus RMA ini.'], 403);
                }
                abort(403, 'Anda tidak memiliki izin untuk menghapus RMA ini.');
            }
        }

        foreach ($rma->materials as $material) {
            Storage::disk('public')->delete($material->foto_path);
        }
        $rma->materials()->delete();
        $rma->delete();

        return redirect()->route('rma')->with('success', 'Data RMA berhasil dihapus.');
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
        $user = auth()->user();
        $isSuperAdminOrManager = $user && ($user->hasRole(['super_admin', 'manajer']) || $user->role === 'super_admin' || $user->role === 'manajer');

        if ($request->input('mode') === 'hari_ini') {
            $today = now()->format('Y-m-d');
            $tampil = $request->input('tampil', 'semua');

            $rmas = Rma::with(['materials', 'user'])
                ->when(!$isSuperAdminOrManager, function ($q) use ($user) {
                    $q->where(function ($sub) use ($user) {
                        $sub->where('user_id', $user->id)
                            ->orWhere('nama_pemohon', $user->name);
                    });
                })
                ->when($isSuperAdminOrManager && $tampil === 'milik_saya', function ($q) use ($user) {
                    $q->where(function ($sub) use ($user) {
                        $sub->where('user_id', $user->id)
                            ->orWhere('nama_pemohon', $user->name);
                    });
                })
                ->where(function ($sub) use ($today) {
                    $sub->whereDate('created_at', $today)
                        ->orWhereDate('tanggal', $today);
                })
                ->get();
        } else {
            $ids = $request->input('ids');
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            $ids = array_filter(array_map('intval', (array) $ids));

            if (empty($ids)) {
                return redirect()->route('rma')->with('error', 'Pilih minimal satu data RMA untuk didownload.');
            }

            $rmas = Rma::with('materials')->whereIn('id', $ids)->get();
        }

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