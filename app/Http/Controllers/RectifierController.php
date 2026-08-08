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
        // Pastikan POP-nya ada, lalu ambil semua rectifiers yang berelasi
        $pop = Pop::findOrFail($pop_id);

        $rectifiers = Rectifier::where('pop_id', $pop_id)
            ->with(['modules', 'outputs']) // Langsung tarik data anak-anaknya juga
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil data Rectifier untuk ' . $pop->nama_pop,
            'data'    => $rectifiers
        ], 200);
    }

    // 2. Menyimpan data Rectifier Baru beserta Modul & Output-nya sekaligus
    public function store(StoreRectifierRequest $request, $pop_id) // Ubah di sini
    {
        $pop = Pop::findOrFail($pop_id);

        $validated = $request->validated();

        // Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // A. Simpan Master Rectifier
            $rectifier = Rectifier::create([
                'pop_id'         => $pop->id,
                'nama_alias'     => $validated['nama_alias'],
                'deskripsi'      => $validated['deskripsi'] ?? null,
                'merk'           => $validated['merk'],
                'type'           => $validated['type'],
                'sn_rectifier'   => $validated['sn_rectifier'],
                'kapasitas_slot' => $validated['kapasitas_slot'],
            ]);

            // B. Simpan Data Modul (jika ada yang diinput)
            if (!empty($request->modules)) {
                foreach ($request->modules as $modul) {
                    $rectifier->modules()->create([
                        'sn_modul'         => $modul['sn_modul'],
                        'kapasitas_ampere' => $modul['kapasitas_ampere'],
                    ]);
                }
            }

            // C. Simpan Data Output (jika ada yang diinput)
            if (!empty($request->outputs)) {
                foreach ($request->outputs as $output) {
                    $rectifier->outputs()->create([
                        'merk_mcb'      => $output['merk_mcb'],
                        'kapasitas_mcb' => $output['kapasitas_mcb'],
                        'peruntukan'    => $output['peruntukan'],
                    ]);
                }
            }

            // Jika semua aman, simpan permanen ke database
            DB::commit();

            return response()->json([
                'message' => 'Data Rectifier berhasil disimpan secara utuh!',
                'data'    => $rectifier->load(['modules', 'outputs']) // Load ulang untuk melihat hasil akhir
            ], 201);
        } catch (\Exception $e) {
            // Jika ada yang error di tengah jalan, batalkan semuanya!
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan data! Transaksi dibatalkan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // 3. Menampilkan Detail 1 Rectifier (lengkap dengan relasinya)
    public function show($pop_id, $id)
    {
        $rectifier = Rectifier::where('pop_id', $pop_id)
            ->with(['modules', 'outputs', 'pop'])
            ->findOrFail($id);

        return response()->json([
            'message' => 'Detail Rectifier',
            'data'    => $rectifier
        ], 200);
    }

    // 4. Memperbarui Data Rectifier (Update)
    public function update(UpdateRectifierRequest $request, $pop_id, $id) // Ubah di sini
    {
        $pop = Pop::findOrFail($pop_id);
        $rectifier = Rectifier::where('pop_id', $pop_id)->findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // A. Update Master Rectifier
            $rectifier->update([
                'nama_alias'     => $validated['nama_alias'],
                'deskripsi'      => $validated['deskripsi'] ?? null,
                'merk'           => $validated['merk'],
                'type'           => $validated['type'],
                'sn_rectifier'   => $validated['sn_rectifier'],
                'kapasitas_slot' => $validated['kapasitas_slot'],
            ]);

            // B. Sinkronisasi Data Modul
            if ($request->has('modules')) {
                // Ambil semua ID modul dari request yang dikirim user
                $requestedModuleIds = collect($request->modules)->pluck('id')->filter()->toArray();

                // Hapus modul di database yang ID-nya tidak ada di request (artinya dihapus oleh user)
                $rectifier->modules()->whereNotIn('id', $requestedModuleIds)->delete();

                foreach ($request->modules as $modul) {
                    if (isset($modul['id'])) {
                        // Jika punya ID, lakukan Update
                        $rectifier->modules()->where('id', $modul['id'])->update([
                            'sn_modul'         => $modul['sn_modul'],
                            'kapasitas_ampere' => $modul['kapasitas_ampere'],
                        ]);
                    } else {
                        // Jika tidak punya ID, berarti modul Baru (Insert)
                        $rectifier->modules()->create([
                            'sn_modul'         => $modul['sn_modul'],
                            'kapasitas_ampere' => $modul['kapasitas_ampere'],
                        ]);
                    }
                }
            } else {
                // Jika array modules dikosongkan sama sekali, hapus semua modul yang ada
                $rectifier->modules()->delete();
            }

            // C. Sinkronisasi Data Output (Logikanya sama dengan Modul)
            if ($request->has('outputs')) {
                $requestedOutputIds = collect($request->outputs)->pluck('id')->filter()->toArray();
                $rectifier->outputs()->whereNotIn('id', $requestedOutputIds)->delete();

                foreach ($request->outputs as $output) {
                    if (isset($output['id'])) {
                        $rectifier->outputs()->where('id', $output['id'])->update([
                            'merk_mcb'      => $output['merk_mcb'],
                            'kapasitas_mcb' => $output['kapasitas_mcb'],
                            'peruntukan'    => $output['peruntukan'],
                        ]);
                    } else {
                        $rectifier->outputs()->create([
                            'merk_mcb'      => $output['merk_mcb'],
                            'kapasitas_mcb' => $output['kapasitas_mcb'],
                            'peruntukan'    => $output['peruntukan'],
                        ]);
                    }
                }
            } else {
                $rectifier->outputs()->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Data Rectifier berhasil diperbarui!',
                'data'    => $rectifier->load(['modules', 'outputs'])
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui data!',
                'error'   => $e->getMessage()
            ], 500);
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
