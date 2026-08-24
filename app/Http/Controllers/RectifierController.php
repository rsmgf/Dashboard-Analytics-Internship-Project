<?php

namespace App\Http\Controllers;

use App\Models\Pop;
use App\Models\Rectifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRectifierRequest;
use App\Http\Requests\UpdateRectifierRequest;

class RectifierController extends Controller
{
    // 1. Menampilkan semua Rectifier di dalam satu POP tertentu
    public function index($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);

        $rectifiers = Rectifier::where('pop_id', $pop_id)
            ->with(['modules', 'outputs', 'diupdateOleh'])
            ->get();

        return view('pop.rectifier.rectifier-card', compact('pop', 'rectifiers'));
    }

    // 2. Menyimpan data Rectifier Baru beserta Modul & Output-nya sekaligus
    public function store(StoreRectifierRequest $request, $pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto_rectifier')) {
                $fotoPath = $request->file('foto_rectifier')->store('rectifiers', 'public');
            }

            // A. Simpan Master Rectifier
            $rectifier = Rectifier::create([
                'pop_id'               => $pop->id,
                'nama_alias'           => $validated['nama_alias'],
                'deskripsi'            => $validated['deskripsi'] ?? null,
                'tanggal_pemeriksaan'  => $validated['tanggal_pemeriksaan'] ?? null,
                'pic'                  => $validated['pic'] ?? null,
                'merk'                 => $validated['merk'],
                'type'                 => $validated['type'],
                'sn_rectifier'         => $validated['sn_rectifier'],
                'kapasitas_slot'       => $validated['kapasitas_slot'],
                'couple'               => $validated['couple'] ?? null,
                'type_modul_controller'=> $validated['type_modul_controller'] ?? null,
                'type_modul_power'     => $validated['type_modul_power'] ?? null,
                'kapasitas_rectifier'  => $validated['kapasitas_rectifier'] ?? null,
                'beban'                => $validated['beban'] ?? null,
                'utilisasi'            => $validated['utilisasi'] ?? null,
                'foto_rectifier'       => $fotoPath,
                'diupdate_oleh'        => auth()->id(),
            ]);

            // B. Simpan Data Modul (jika ada)
            if (!empty($validated['modules'])) {
                foreach ($validated['modules'] as $modul) {
                    if (!empty($modul['sn_modul'])) {
                        $rectifier->modules()->create([
                            'sn_modul'         => $modul['sn_modul'],
                            'kapasitas_ampere' => $modul['kapasitas_ampere'] ?? null,
                        ]);
                    }
                }
            }

            // C. Simpan Data Output (jika ada)
            if (!empty($validated['outputs'])) {
                foreach ($validated['outputs'] as $output) {
                    if (!empty($output['nama_mcb'])) {
                        $rectifier->outputs()->create([
                            'nama_mcb'      => $output['nama_mcb'],
                            'merk_mcb'      => $output['merk_mcb'] ?? null,
                            'kapasitas_mcb' => $output['kapasitas_mcb'] ?? null,
                            'peruntukan'    => $output['peruntukan'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('rectifiers.index', $pop->id)
                ->with('success', 'Data Rectifier berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // 3. Menampilkan Detail 1 Rectifier (lengkap dengan relasinya)
    public function show($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);

        $rectifier = Rectifier::where('pop_id', $pop_id)
            ->with(['modules', 'outputs', 'pop', 'diupdateOleh'])
            ->findOrFail($id);

        return view('pop.rectifier.rectifier-detail', compact('pop', 'rectifier'));
    }

    // 3b. Tampilkan form tambah Rectifier baru
    public function create($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        return view('pop.rectifier.rectifier-create', compact('pop'));
    }

    // 3c. Tampilkan form edit Rectifier
    public function edit($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $rectifier = Rectifier::where('pop_id', $pop_id)
            ->with(['modules', 'outputs'])
            ->findOrFail($id);
        return view('pop.rectifier.rectifier-edit', compact('pop', 'rectifier'));
    }

    // 4. Memperbarui Data Rectifier (Update)
    public function update(UpdateRectifierRequest $request, $pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $rectifier = Rectifier::where('pop_id', $pop_id)->findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Upload foto baru jika ada
            $fotoPath = $rectifier->foto_rectifier; // pertahankan foto lama
            if ($request->hasFile('foto_rectifier')) {
                // Hapus foto lama jika ada
                if ($fotoPath && \Storage::disk('public')->exists($fotoPath)) {
                    \Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto_rectifier')->store('rectifiers', 'public');
            }

            // A. Update Master Rectifier (semua field)
            $rectifier->update([
                'nama_alias'            => $validated['nama_alias'],
                'deskripsi'             => $validated['deskripsi'] ?? null,
                'tanggal_pemeriksaan'   => $validated['tanggal_pemeriksaan'] ?? null,
                'pic'                   => $validated['pic'] ?? null,
                'merk'                  => $validated['merk'],
                'type'                  => $validated['type'],
                'sn_rectifier'          => $validated['sn_rectifier'],
                'kapasitas_slot'        => $validated['kapasitas_slot'],
                'couple'                => $validated['couple'] ?? null,
                'type_modul_controller' => $validated['type_modul_controller'] ?? null,
                'type_modul_power'      => $validated['type_modul_power'] ?? null,
                'kapasitas_rectifier'   => $validated['kapasitas_rectifier'] ?? null,
                'beban'                 => $validated['beban'] ?? null,
                'utilisasi'             => $validated['utilisasi'] ?? null,
                'foto_rectifier'        => $fotoPath,
                'diupdate_oleh'         => auth()->id(),
            ]);

            // B. Sinkronisasi Data Modul
            if ($request->has('modules')) {
                $requestedModuleIds = collect($request->modules)->pluck('id')->filter()->toArray();
                $rectifier->modules()->whereNotIn('id', $requestedModuleIds)->delete();

                foreach ($request->modules as $modul) {
                    if (!empty($modul['sn_modul'])) {
                        if (isset($modul['id']) && $modul['id']) {
                            $rectifier->modules()->where('id', $modul['id'])->update([
                                'sn_modul'         => $modul['sn_modul'],
                                'kapasitas_ampere' => $modul['kapasitas_ampere'] ?? null,
                            ]);
                        } else {
                            $rectifier->modules()->create([
                                'sn_modul'         => $modul['sn_modul'],
                                'kapasitas_ampere' => $modul['kapasitas_ampere'] ?? null,
                            ]);
                        }
                    }
                }
            } else {
                $rectifier->modules()->delete();
            }

            // C. Sinkronisasi Data Output
            if ($request->has('outputs')) {
                $requestedOutputIds = collect($request->outputs)->pluck('id')->filter()->toArray();
                $rectifier->outputs()->whereNotIn('id', $requestedOutputIds)->delete();

                foreach ($request->outputs as $output) {
                    if (!empty($output['nama_mcb'])) {
                        if (isset($output['id']) && $output['id']) {
                            $rectifier->outputs()->where('id', $output['id'])->update([
                                'nama_mcb'      => $output['nama_mcb'],
                                'merk_mcb'      => $output['merk_mcb'] ?? null,
                                'kapasitas_mcb' => $output['kapasitas_mcb'] ?? null,
                                'peruntukan'    => $output['peruntukan'] ?? null,
                            ]);
                        } else {
                            $rectifier->outputs()->create([
                                'nama_mcb'      => $output['nama_mcb'],
                                'merk_mcb'      => $output['merk_mcb'] ?? null,
                                'kapasitas_mcb' => $output['kapasitas_mcb'] ?? null,
                                'peruntukan'    => $output['peruntukan'] ?? null,
                            ]);
                        }
                    }
                }
            } else {
                $rectifier->outputs()->delete();
            }

            DB::commit();

            return redirect()->route('rectifiers.show', [$pop->id, $rectifier->id])
                ->with('success', 'Data Rectifier berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // 5. Menghapus Data Rectifier (Delete)
    public function destroy($pop_id, $id)
    {
        $rectifier = Rectifier::where('pop_id', $pop_id)->findOrFail($id);

        // Cukup panggil delete(), modul dan output akan ikut hancur berkat onDelete('cascade')
        $rectifier->delete();

        return response()->json([
            'message' => 'Data Rectifier beserta semua isinya berhasil dihapus permanen!'
        ], 200);
    }
}
