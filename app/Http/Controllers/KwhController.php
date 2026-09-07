<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKwhRequest;
use App\Http\Requests\UpdateKwhRequest;
use App\Models\Kwh;
use App\Models\Pop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwhController extends Controller
{
    // 1. Menampilkan semua kWh di dalam satu POP tertentu
    public function index($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        $kwhs = Kwh::where('pop_id', $pop_id)->with(['photos', 'diupdateOleh'])->get();

        return view('pop.kwh.kwh-card', compact('pop', 'kwhs'));
    }

    // 2. Form tambah kWh
    public function create($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        return view('pop.kwh.kwh-create', compact('pop'));
    }

    // 3. Simpan kWh baru beserta foto-fotonya
    public function store(StoreKwhRequest $request, $pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        $validated = $request->validated();
        $dayaPsGi = Kwh::hitungDayaPsGi((float) $validated['mcb_utama'], $validated['jumlah_phasa']);
        $totalDayaTerpakai = Kwh::hitungTotalDayaTerpakai(
            $validated['jumlah_phasa'],
            (float) $validated['arus_r'],
            (float) ($validated['arus_s'] ?? 0),
            (float) ($validated['arus_t'] ?? 0)
        );
        $totalBeban = Kwh::hitungTotalBeban(
            (float) $validated['arus_r'],
            (float) ($validated['arus_s'] ?? 0),
            (float) ($validated['arus_t'] ?? 0)
        );
        $hitung = Kwh::hitungStatus($totalDayaTerpakai, $dayaPsGi);

        DB::beginTransaction();
        try {
            $kwh = Kwh::create([
                'pop_id' => $pop->id,
                'building' => $validated['building'],
                'pic' => $validated['pic'],
                'type_pop' => $validated['type_pop'],
                'id_customer_pln' => $validated['id_customer_pln'],
                'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
                'daya_ps_gi' => $dayaPsGi,
                'mcb_utama' => $validated['mcb_utama'],
                'jumlah_phasa' => $validated['jumlah_phasa'],
                'keberadaan_arrester' => $validated['keberadaan_arrester'],
                'merk_type_arrester' => $validated['merk_type_arrester'] ?? null,
                'status_utilisasi' => $hitung['status_utilisasi'],
                'persentase_utilisasi' => $hitung['persentase_utilisasi'],
                'teg_rn' => $validated['teg_rn'],
                'arus_r' => $validated['arus_r'],
                'teg_sn' => $validated['teg_sn'] ?? null,
                'arus_s' => $validated['arus_s'] ?? null,
                'teg_tn' => $validated['teg_tn'] ?? null,
                'arus_t' => $validated['arus_t'] ?? null,
                'teg_rs' => $validated['teg_rs'] ?? null,
                'teg_st' => $validated['teg_st'] ?? null,
                'teg_rt' => $validated['teg_rt'] ?? null,
                'teg_ng' => $validated['teg_ng'],
                'total_daya_terpakai' => $totalDayaTerpakai,
                'total_beban' => $totalBeban,
                'warna_r' => $validated['warna_r'],
                'warna_s' => $validated['warna_s'],
                'warna_t' => $validated['warna_t'],
                'warna_n' => $validated['warna_n'],
                'warna_g' => $validated['warna_g'],
                'ukuran_r' => $validated['ukuran_r'],
                'ukuran_s' => $validated['ukuran_s'],
                'ukuran_t' => $validated['ukuran_t'],
                'ukuran_n' => $validated['ukuran_n'],
                'ukuran_g' => $validated['ukuran_g'],
                'diupdate_oleh' => auth()->id(),
            ]);

            foreach ($request->file('photos', []) as $i => $file) {
                if ($file) {
                    $kwh->photos()->create([
                        'path' => $file->store('kwh', 'public'),
                        'keterangan' => $validated['captions'][$i] ?? 'Foto ' . ($i + 1),
                        'urutan' => $i,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('kwh.card', $pop->id)
                ->with('success', 'Data kWh berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // 4. Detail 1 kWh
    public function show($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $kwh = Kwh::where('pop_id', $pop_id)->with(['photos', 'diupdateOleh'])->findOrFail($id);

        return view('pop.kwh.kwh-detail', compact('pop', 'kwh'));
    }

    // 5. Form edit kWh
    public function edit($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $kwh = Kwh::where('pop_id', $pop_id)->with('photos')->findOrFail($id);

        return view('pop.kwh.kwh-edit', compact('pop', 'kwh'));
    }

    // 6. Update kWh
    public function update(UpdateKwhRequest $request, $pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $kwh = Kwh::where('pop_id', $pop_id)->findOrFail($id);
        $validated = $request->validated();

        $dayaPsGi = Kwh::hitungDayaPsGi((float) $validated['mcb_utama'], $validated['jumlah_phasa']);
        $totalDayaTerpakai = Kwh::hitungTotalDayaTerpakai(
            $validated['jumlah_phasa'],
            (float) $validated['arus_r'],
            (float) ($validated['arus_s'] ?? 0),
            (float) ($validated['arus_t'] ?? 0)
        );
        $totalBeban = Kwh::hitungTotalBeban(
            (float) $validated['arus_r'],
            (float) ($validated['arus_s'] ?? 0),
            (float) ($validated['arus_t'] ?? 0)
        );
        $hitung = Kwh::hitungStatus($totalDayaTerpakai, $dayaPsGi);

        DB::beginTransaction();
        try {
            $kwh->update([
                'building' => $validated['building'],
                'pic' => $validated['pic'],
                'type_pop' => $validated['type_pop'],
                'id_customer_pln' => $validated['id_customer_pln'],
                'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
                'daya_ps_gi' => $dayaPsGi,
                'mcb_utama' => $validated['mcb_utama'],
                'jumlah_phasa' => $validated['jumlah_phasa'],
                'keberadaan_arrester' => $validated['keberadaan_arrester'],
                'merk_type_arrester' => $validated['merk_type_arrester'] ?? null,
                'status_utilisasi' => $hitung['status_utilisasi'],
                'persentase_utilisasi' => $hitung['persentase_utilisasi'],
                'teg_rn' => $validated['teg_rn'],
                'arus_r' => $validated['arus_r'],
                'teg_sn' => $validated['teg_sn'] ?? null,
                'arus_s' => $validated['arus_s'] ?? null,
                'teg_tn' => $validated['teg_tn'] ?? null,
                'arus_t' => $validated['arus_t'] ?? null,
                'teg_rs' => $validated['teg_rs'] ?? null,
                'teg_st' => $validated['teg_st'] ?? null,
                'teg_rt' => $validated['teg_rt'] ?? null,
                'teg_ng' => $validated['teg_ng'],
                'total_daya_terpakai' => $totalDayaTerpakai,
                'total_beban' => $totalBeban,
                'warna_r' => $validated['warna_r'],
                'warna_s' => $validated['warna_s'],
                'warna_t' => $validated['warna_t'],
                'warna_n' => $validated['warna_n'],
                'warna_g' => $validated['warna_g'],
                'ukuran_r' => $validated['ukuran_r'],
                'ukuran_s' => $validated['ukuran_s'],
                'ukuran_t' => $validated['ukuran_t'],
                'ukuran_n' => $validated['ukuran_n'],
                'ukuran_g' => $validated['ukuran_g'],
                'diupdate_oleh' => auth()->id(),
            ]);

            foreach ($request->file('photos', []) as $i => $file) {
                if ($file) {
                    $existing = $kwh->photos()->where('urutan', $i)->first();
                    if ($existing) {
                        if (\Storage::disk('public')->exists($existing->path)) {
                            \Storage::disk('public')->delete($existing->path);
                        }
                        $existing->update([
                            'path' => $file->store('kwh', 'public'),
                            'keterangan' => $validated['captions'][$i] ?? $existing->keterangan,
                        ]);
                    } else {
                        $kwh->photos()->create([
                            'path' => $file->store('kwh', 'public'),
                            'keterangan' => $validated['captions'][$i] ?? 'Foto ' . ($i + 1),
                            'urutan' => $i,
                        ]);
                    }
                } elseif (isset($validated['captions'][$i])) {
                    $kwh->photos()->where('urutan', $i)->update(['keterangan' => $validated['captions'][$i]]);
                }
            }

            DB::commit();

            return redirect()->route('kwh.detail', [$pop->id, $kwh->id])
                ->with('success', 'Data kWh berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // 7. Hapus kWh
    public function destroy($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $kwh = Kwh::where('pop_id', $pop_id)->findOrFail($id);

        try {
            foreach ($kwh->photos as $photo) {
                if (\Storage::disk('public')->exists($photo->path)) {
                    \Storage::disk('public')->delete($photo->path);
                }
            }
            $kwh->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Data kWh berhasil dihapus permanen!']);
            }

            return redirect()->route('kwh.card', $pop->id)->with('success', 'Data kWh berhasil dihapus permanen!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return redirect()->route('kwh.card', $pop->id)->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
