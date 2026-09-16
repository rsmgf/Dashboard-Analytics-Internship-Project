<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGensetRequest;
use App\Http\Requests\UpdateGensetRequest;
use App\Models\Genset;
use App\Models\Pop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GensetController extends Controller
{
    // ─── 1. List Genset milik sebuah POP ──────────────────────────────────────
    public function index($pop_id)
    {
        $pop     = Pop::findOrFail($pop_id);
        $gensets = Genset::where('pop_id', $pop->id)
            ->with('diupdateOleh')
            ->orderBy('nomor_genset')
            ->get();

        return view('pop.Genset.Genset-card', compact('pop', 'gensets'));
    }

    // ─── 2. Form Tambah Genset ─────────────────────────────────────────────────
    public function create($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);

        return view('pop.Genset.Genset-Create', compact('pop'));
    }

    // ─── 3. Simpan Genset Baru ─────────────────────────────────────────────────
    public function store(StoreGensetRequest $request, $pop_id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $validated = $request->validated();

        // Resolve field "Others" → simpan ke kolom yang sama
        $merkGenset = $validated['merk_genset'] === 'Others'
            ? ($validated['merk_genset_others'] ?? $validated['merk_genset'])
            : $validated['merk_genset'];

        $model = $validated['model'] === 'Others'
            ? ($validated['model_others'] ?? $validated['model'])
            : $validated['model'];

        $tipeEngine = $validated['tipe_engine'] === 'Others'
            ? ($validated['tipe_engine_others'] ?? $validated['tipe_engine'])
            : $validated['tipe_engine'];

        // Auto-generate nomor genset
        $existingCount  = Genset::where('pop_id', $pop->id)->count();
        $nomorGenset    = $pop->kode_pop . '_GEN' . str_pad($existingCount + 1, 2, '0', STR_PAD_LEFT);

        // Hitung status PM otomatis (berbasis 6 bulan dari tanggal_pm)
        $statusGenset = $this->calcStatusPm($validated['tanggal_pm'] ?? null);

        // Upload foto genset
        $photoGensetPath = null;
        if ($request->hasFile('photo_genset')) {
            $photoGensetPath = $request->file('photo_genset')->store('genset_images', 'public');
        }

        // Upload foto engine
        $photoEnginePath = null;
        if ($request->hasFile('photo_engine')) {
            $photoEnginePath = $request->file('photo_engine')->store('genset_images', 'public');
        }

        Genset::create([
            'pop_id'                   => $pop->id,
            'nomor_genset'             => $nomorGenset,
            'pic'                      => $validated['pic'],
            'bentuk_fisik'             => $validated['bentuk_fisik'],
            'merk_genset'              => $merkGenset,
            'model'                    => $model,
            'sn_genset'                => $validated['sn_genset'],
            'kapasitas_kva'            => (float) $validated['kapasitas_kva'],
            'tipe_engine'              => $tipeEngine,
            'sn_engine'                => $validated['sn_engine'],
            'tahun_pasang'             => (int) $validated['tahun_pasang'],
            'tanggal_pm'               => $validated['tanggal_pm'] ?? null,
            'status_genset'            => $statusGenset,
            'photo_genset'             => $photoGensetPath,
            'keterangan_gambar_genset' => $validated['keterangan_gambar_genset'] ?? null,
            'photo_engine'             => $photoEnginePath,
            'keterangan_gambar_engine' => $validated['keterangan_gambar_engine'] ?? null,
            'diupdate_oleh'            => Auth::id(),
        ]);

        return redirect()->route('gensets.index', $pop->id)
            ->with('success', 'Data Genset berhasil disimpan!');
    }

    // ─── 4. Detail Satu Genset ─────────────────────────────────────────────────
    public function show($pop_id, $id)
    {
        $pop    = Pop::findOrFail($pop_id);
        $genset = Genset::where('pop_id', $pop->id)
            ->with('diupdateOleh')
            ->findOrFail($id);

        return view('pop.Genset.Genset-detail', compact('pop', 'genset'));
    }

    // ─── 5. Form Edit Genset ───────────────────────────────────────────────────
    public function edit($pop_id, $id)
    {
        $pop    = Pop::findOrFail($pop_id);
        $genset = Genset::where('pop_id', $pop->id)->findOrFail($id);

        return view('pop.Genset.Genset-Edit', compact('pop', 'genset'));
    }

    // ─── 6. Update Data Genset ─────────────────────────────────────────────────
    public function update(UpdateGensetRequest $request, $pop_id, $id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $genset    = Genset::where('pop_id', $pop->id)->findOrFail($id);
        $validated = $request->validated();

        // Resolve field "Others" → simpan ke kolom yang sama
        $merkGenset = $validated['merk_genset'] === 'Others'
            ? ($validated['merk_genset_others'] ?? $validated['merk_genset'])
            : $validated['merk_genset'];

        $model = $validated['model'] === 'Others'
            ? ($validated['model_others'] ?? $validated['model'])
            : $validated['model'];

        $tipeEngine = $validated['tipe_engine'] === 'Others'
            ? ($validated['tipe_engine_others'] ?? $validated['tipe_engine'])
            : $validated['tipe_engine'];

        // Hitung status PM otomatis
        $statusGenset = $this->calcStatusPm($validated['tanggal_pm'] ?? null);

        $updateData = [
            'pic'                      => $validated['pic'],
            'bentuk_fisik'             => $validated['bentuk_fisik'],
            'merk_genset'              => $merkGenset,
            'model'                    => $model,
            'sn_genset'                => $validated['sn_genset'],
            'kapasitas_kva'            => (float) $validated['kapasitas_kva'],
            'tipe_engine'              => $tipeEngine,
            'sn_engine'                => $validated['sn_engine'],
            'tahun_pasang'             => (int) $validated['tahun_pasang'],
            'tanggal_pm'               => $validated['tanggal_pm'] ?? null,
            'status_genset'            => $statusGenset,
            'keterangan_gambar_genset' => $validated['keterangan_gambar_genset'] ?? $genset->keterangan_gambar_genset,
            'keterangan_gambar_engine' => $validated['keterangan_gambar_engine'] ?? $genset->keterangan_gambar_engine,
            'diupdate_oleh'            => Auth::id(),
        ];

        // Upload foto baru jika ada (jika tidak, foto lama tetap dipakai)
        if ($request->hasFile('photo_genset')) {
            $updateData['photo_genset'] = $request->file('photo_genset')->store('genset_images', 'public');
        }

        if ($request->hasFile('photo_engine')) {
            $updateData['photo_engine'] = $request->file('photo_engine')->store('genset_images', 'public');
        }

        $genset->update($updateData);

        return redirect()->route('gensets.show', [$pop->id, $genset->id])
            ->with('success', 'Data Genset berhasil diperbarui!');
    }

    // ─── 7. Hapus Genset ───────────────────────────────────────────────────────
    public function destroy($pop_id, $id)
    {
        $pop    = Pop::findOrFail($pop_id);
        $genset = Genset::where('pop_id', $pop->id)->findOrFail($id);

        $genset->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Genset berhasil dihapus!',
            ]);
        }

        return redirect()->route('gensets.index', $pop->id)
            ->with('success', 'Data Genset berhasil dihapus!');
    }

    // ─── Helper: Hitung Status PM dari tanggal_pm ──────────────────────────────
    private function calcStatusPm(?string $tanggalPm): string
    {
        if (empty($tanggalPm)) {
            return 'Belum PM';
        }

        $bulanBerlalu = Carbon::parse($tanggalPm)->diffInMonths(now());

        return $bulanBerlalu >= 6 ? 'Jadwal PM' : 'Sudah PM';
    }
}
