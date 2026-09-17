<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcRequest;
use App\Http\Requests\UpdateAcRequest;
use App\Models\Ac;
use App\Models\Pop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AcController extends Controller
{
    // ─── 1. List AC milik sebuah POP ──────────────────────────────────────────
    public function index($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        $acs = Ac::where('pop_id', $pop->id)
            ->with('diupdateOleh')
            ->orderBy('nomor_ac')
            ->get();

        return view('pop.AC.ac-card', compact('pop', 'acs'));
    }

    // ─── 2. Form Tambah AC ────────────────────────────────────────────────────
    public function create($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);

        return view('pop.AC.ac-create', compact('pop'));
    }

    // ─── 3. Simpan AC Baru ────────────────────────────────────────────────────
    public function store(StoreAcRequest $request, $pop_id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $validated = $request->validated();

        // Resolve field "Others" → simpan ke kolom yang sama
        $jenisFreon = $validated['jenis_freon'] === 'Others'
            ? ($validated['jenis_freon_others'] ?? $validated['jenis_freon'])
            : $validated['jenis_freon'];

        $merkAc = $validated['merk_ac'] === 'Others'
            ? ($validated['merk_ac_others'] ?? $validated['merk_ac'])
            : $validated['merk_ac'];

        $typeAc = $validated['type_ac'] === 'Others'
            ? ($validated['type_ac_others'] ?? $validated['type_ac'])
            : $validated['type_ac'];

        // Auto-generate nomor AC: {KODE_POP}_AC01, _AC02, dst.
        $existingCount = Ac::where('pop_id', $pop->id)->count();
        $nomorAc       = $pop->kode_pop . '_AC' . str_pad($existingCount + 1, 2, '0', STR_PAD_LEFT);

        // Hitung status AC dari tanggal_terakhir_pm (6 bulan)
        $statusAc = $this->calcStatusPm($validated['tanggal_terakhir_pm'] ?? null);

        // Upload foto
        $photoPath = null;
        if ($request->hasFile('photo_ac')) {
            $photoPath = $request->file('photo_ac')->store('ac_images', 'public');
        }

        Ac::create([
            'pop_id'               => $pop->id,
            'nomor_ac'             => $nomorAc,
            'jenis_freon'          => $jenisFreon,
            'merk_ac'              => $merkAc,
            'tahun_manufaktur'     => (int) $validated['tahun_manufaktur'],
            'type_ac'              => $typeAc,
            'pk'                   => $validated['pk'],
            'tanggal_instalasi'    => $validated['tanggal_instalasi'] ?? null,
            'tanggal_terakhir_pm'  => $validated['tanggal_terakhir_pm'] ?? null,
            'status_ac'            => $statusAc,
            'photo_ac'             => $photoPath,
            'keterangan_gambar_ac' => $validated['keterangan_gambar_ac'] ?? null,
            'diupdate_oleh'        => Auth::id(),
        ]);

        return redirect()->route('acs.index', $pop->id)
            ->with('success', 'Data AC berhasil disimpan!');
    }

    // ─── 4. Detail Satu AC ────────────────────────────────────────────────────
    public function show($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $ac  = Ac::where('pop_id', $pop->id)
            ->with('diupdateOleh')
            ->findOrFail($id);

        return view('pop.AC.ac-detail', compact('pop', 'ac'));
    }

    // ─── 5. Form Edit AC ──────────────────────────────────────────────────────
    public function edit($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $ac  = Ac::where('pop_id', $pop->id)->findOrFail($id);

        return view('pop.AC.ac-edit', compact('pop', 'ac'));
    }

    // ─── 6. Update Data AC ────────────────────────────────────────────────────
    public function update(UpdateAcRequest $request, $pop_id, $id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $ac        = Ac::where('pop_id', $pop->id)->findOrFail($id);
        $validated = $request->validated();

        // Resolve field "Others"
        $jenisFreon = $validated['jenis_freon'] === 'Others'
            ? ($validated['jenis_freon_others'] ?? $validated['jenis_freon'])
            : $validated['jenis_freon'];

        $merkAc = $validated['merk_ac'] === 'Others'
            ? ($validated['merk_ac_others'] ?? $validated['merk_ac'])
            : $validated['merk_ac'];

        $typeAc = $validated['type_ac'] === 'Others'
            ? ($validated['type_ac_others'] ?? $validated['type_ac'])
            : $validated['type_ac'];

        // Hitung status AC
        $statusAc = $this->calcStatusPm($validated['tanggal_terakhir_pm'] ?? null);

        $updateData = [
            'jenis_freon'          => $jenisFreon,
            'merk_ac'              => $merkAc,
            'tahun_manufaktur'     => (int) $validated['tahun_manufaktur'],
            'type_ac'              => $typeAc,
            'pk'                   => $validated['pk'],
            'tanggal_instalasi'    => $validated['tanggal_instalasi'] ?? null,
            'tanggal_terakhir_pm'  => $validated['tanggal_terakhir_pm'] ?? null,
            'status_ac'            => $statusAc,
            'keterangan_gambar_ac' => $validated['keterangan_gambar_ac'] ?? $ac->keterangan_gambar_ac,
            'diupdate_oleh'        => Auth::id(),
        ];

        // Upload foto baru jika ada (jika tidak, foto lama tetap dipakai)
        if ($request->hasFile('photo_ac')) {
            $updateData['photo_ac'] = $request->file('photo_ac')->store('ac_images', 'public');
        }

        $ac->update($updateData);

        return redirect()->route('acs.show', [$pop->id, $ac->id])
            ->with('success', 'Data AC berhasil diperbarui!');
    }

    // ─── 7. Hapus AC ──────────────────────────────────────────────────────────
    public function destroy($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $ac  = Ac::where('pop_id', $pop->id)->findOrFail($id);

        $ac->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data AC berhasil dihapus!',
            ]);
        }

        return redirect()->route('acs.index', $pop->id)
            ->with('success', 'Data AC berhasil dihapus!');
    }

    // ─── Helper: Hitung Status PM dari tanggal_terakhir_pm ────────────────────
    private function calcStatusPm(?string $tanggalPm): string
    {
        if (empty($tanggalPm)) {
            return 'Belum PM';
        }

        $bulanBerlalu = Carbon::parse($tanggalPm)->diffInMonths(now());

        return $bulanBerlalu >= 6 ? 'Jadwal PM' : 'Sudah PM';
    }
}
