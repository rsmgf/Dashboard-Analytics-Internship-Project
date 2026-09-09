<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBatteryRequest;
use App\Http\Requests\UpdateBatteryRequest;
use App\Models\Battery;
use App\Models\Pop;
use App\Models\Rectifier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BatteryController extends Controller
{
    public function index($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);
        $batteries = Battery::where('pop_id', $pop->id)
            ->with(['rectifier', 'diupdateOleh'])
            ->orderBy('nomor_recti')
            ->orderBy('nomor_bank')
            ->get();

        $rectifiers = Rectifier::where('pop_id', $pop->id)->get();
        $groupedBatteries = $batteries->groupBy('nomor_recti');

        $rectifierBackupStats = [];
        foreach ($groupedBatteries as $nomorRecti => $group) {
            
            $rectifierId       = $group->first()->rectifier_id;
            $matchingRectifier = $rectifierId ? $rectifiers->firstWhere('id', $rectifierId) : null;

            $totalKapasitasUji = $group->sum('kapasitas_uji');
            $beban = ($matchingRectifier && (float)$matchingRectifier->beban > 0) ? (float)$matchingRectifier->beban : null;

            $backupTime = ($beban && $totalKapasitasUji > 0) ? round($totalKapasitasUji / $beban, 2) : null;
            
            $performaBackup = 'BLM UJI BATT';
            $badgeClass = 'status-warning';

            if ($backupTime !== null) {
                if ($backupTime >= 8) {
                    $performaBackup = '1-EXCELLENT';
                    $badgeClass = 'status-excellent';
                } elseif ($backupTime >= 6) {
                    $performaBackup = '2-GOOD ENOUGH';
                    $badgeClass = 'status-good';
                } elseif ($backupTime >= 4) {
                    $performaBackup = '3-WARNING';
                    $badgeClass = 'status-warning';
                } else {
                    $performaBackup = '4-ALERT';
                    $badgeClass = 'status-danger';
                }
            }

            $rectifierBackupStats[$nomorRecti] = [
                'rectifier'       => $matchingRectifier,
                'total_uji'       => $totalKapasitasUji,
                'beban'           => $beban,
                'backup_time'     => $backupTime,
                'performa_backup' => $performaBackup,
                'badge_class'     => $badgeClass,
            ];
        }


        return view('pop.Battery.battery-card', compact('pop', 'batteries', 'groupedBatteries', 'rectifierBackupStats', 'rectifiers'));
    }

    // 2. Menampilkan Form Tambah Baterai
    public function create($pop_id)
    {
        $pop = Pop::findOrFail($pop_id);

        // Rectifier dengan label urutan RECT01, RECT02, dst.
        $rectifiers = Rectifier::where('pop_id', $pop->id)
            ->orderBy('created_at')->orderBy('id')->get()->values()
            ->map(function ($r, $index) use ($pop) {
                $r->recti_label = $pop->kode_pop . '_RECT' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                return $r;
            });

        $existingCount = Battery::where('pop_id', $pop->id)->count();
        $suggestedBank = $pop->kode_pop . '_BANK' . str_pad($existingCount + 1, 2, '0', STR_PAD_LEFT);

        return view('pop.Battery.battery-create', compact('pop', 'rectifiers', 'suggestedBank'));
    }

    // 3. Menyimpan Data Baterai Baru
    public function store(StoreBatteryRequest $request, $pop_id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $validated = $request->validated();

        // Rectifier dipilih via ID integer dari dropdown
        $rectifierId       = (int) $validated['rectifier_id'];
        $matchingRectifier = Rectifier::where('pop_id', $pop->id)->find($rectifierId);
        $nomorRecti        = $matchingRectifier
            ? $this->generateNomorRecti($pop, $rectifierId)
            : 'UNKNOWN';

        // Hitung persentase kapasitas & performa baterai
        $kapasitasBattery = (float) $validated['kapasitas_battery'];
        $kapasitasUji     = isset($validated['kapasitas_uji']) && $validated['kapasitas_uji'] !== ''
            ? (float) str_replace(',', '.', $validated['kapasitas_uji'])
            : null;

        $persen   = null;
        $performa = 'BLM UJI BATT';
        if ($kapasitasUji !== null && $kapasitasBattery > 0) {
            $persen = round(($kapasitasUji / $kapasitasBattery) * 100, 2);
            if ($persen >= 90)      $performa = '1-EXCELLENT';
            elseif ($persen >= 75)  $performa = '2-GOOD ENOUGH';
            elseif ($persen >= 50)  $performa = '3-WARNING';
            else                    $performa = '4-ALERT';
        }

        // Hitung status uji baterai berdasarkan tanggal uji terakhir
        $statusUji = 'BLM UJI BATT';
        if (!empty($validated['tanggal_uji_terakhir'])) {
            $tglUji    = Carbon::parse($validated['tanggal_uji_terakhir']);
            $diffDays  = now()->diffInDays($tglUji, false);
            $statusUji = (abs($diffDays) >= 365) ? 'JADWAL UJI BATT' : 'SUDAH UJI BATT';
        }

        Battery::create([
            'pop_id'                   => $pop->id,
            'rectifier_id'             => $matchingRectifier?->id,
            'building'                 => $validated['building'],
            'pic'                      => $validated['pic'],
            'type_pop'                 => $validated['type_pop'],
            'recti'                    => null,
            'nomor_recti'              => $nomorRecti,
            'nomor_bank'               => $validated['nomor_bank'],
            'merk_battery'             => $validated['merk_battery'],
            'tipe_battery'             => $validated['tipe_battery'],
            'jenis_battery'            => $validated['jenis_battery'],
            'kapasitas_battery'        => $kapasitasBattery,
            'kapasitas_uji'            => $kapasitasUji,
            'kapasitas_battery_persen' => $persen,
            'performa_baterai'         => $performa,
            'backup_timer'             => null,
            'tanggal_uji_terakhir'     => $validated['tanggal_uji_terakhir'] ?? null,
            'tanggal_penggantian'      => $validated['tanggal_penggantian'] ?? null,
            'status_uji'               => $statusUji,
            'area_sti'                 => $validated['area_sti'],
            'diupdate_oleh'            => Auth::id(),
        ]);

        return redirect()->route('batteries.index', $pop->id)
            ->with('success', 'Data Baterai berhasil disimpan!');
    }

    // 4. Menampilkan Detail Baterai
    public function show($pop_id, $id)
    {
        $pop     = Pop::findOrFail($pop_id);
        $battery = Battery::where('pop_id', $pop->id)
            ->with(['rectifier', 'diupdateOleh'])
            ->findOrFail($id);

        // Hitung backup time & performa untuk halaman detail
        $rectifier     = $battery->rectifier;
        $beban         = ($rectifier && (float) $rectifier->beban > 0) ? (float) $rectifier->beban : null;
        $totalUjiGroup = Battery::where('pop_id', $pop->id)
            ->where('rectifier_id', $battery->rectifier_id)
            ->sum('kapasitas_uji');

        $backupTime     = ($beban !== null && $totalUjiGroup > 0)
            ? round($totalUjiGroup / $beban, 2)
            : null;
        $performaBackup = $this->calcPerformaBackup($backupTime, $beban !== null);
        $badgeClass     = $this->badgeFromPerforma($performaBackup);

        return view('pop.Battery.battery-detail', compact(
            'pop', 'battery', 'backupTime', 'performaBackup', 'badgeClass', 'beban', 'totalUjiGroup'
        ));
    }

    // 5. Menampilkan Form Edit Baterai
    public function edit($pop_id, $id)
    {
        $pop     = Pop::findOrFail($pop_id);
        $battery = Battery::where('pop_id', $pop->id)->findOrFail($id);

        // Rectifier dengan label urutan RECT01, RECT02, dst.
        $rectifiers = Rectifier::where('pop_id', $pop->id)
            ->orderBy('created_at')->orderBy('id')->get()->values()
            ->map(function ($r, $index) use ($pop) {
                $r->recti_label = $pop->kode_pop . '_RECT' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                return $r;
            });

        return view('pop.Battery.battery-edit', compact('pop', 'battery', 'rectifiers'));
    }

    // 6. Memperbarui Data Baterai
    public function update(UpdateBatteryRequest $request, $pop_id, $id)
    {
        $pop       = Pop::findOrFail($pop_id);
        $battery   = Battery::where('pop_id', $pop->id)->findOrFail($id);
        $validated = $request->validated();

        // Rectifier dipilih via ID integer dari dropdown
        $rectifierId       = (int) $validated['rectifier_id'];
        $matchingRectifier = Rectifier::where('pop_id', $pop->id)->find($rectifierId);
        $nomorRecti        = $matchingRectifier
            ? $this->generateNomorRecti($pop, $rectifierId)
            : $battery->nomor_recti;

        $kapasitasBattery = (float) $validated['kapasitas_battery'];
        $kapasitasUji     = isset($validated['kapasitas_uji']) && $validated['kapasitas_uji'] !== ''
            ? (float) str_replace(',', '.', $validated['kapasitas_uji'])
            : null;

        $persen   = null;
        $performa = 'BLM UJI BATT';
        if ($kapasitasUji !== null && $kapasitasBattery > 0) {
            $persen = round(($kapasitasUji / $kapasitasBattery) * 100, 2);
            if ($persen >= 90)      $performa = '1-EXCELLENT';
            elseif ($persen >= 75)  $performa = '2-GOOD ENOUGH';
            elseif ($persen >= 50)  $performa = '3-WARNING';
            else                    $performa = '4-ALERT';
        }

        $statusUji = 'BLM UJI BATT';
        if (!empty($validated['tanggal_uji_terakhir'])) {
            $tglUji    = Carbon::parse($validated['tanggal_uji_terakhir']);
            $diffDays  = now()->diffInDays($tglUji, false);
            $statusUji = (abs($diffDays) >= 365) ? 'JADWAL UJI BATT' : 'SUDAH UJI BATT';
        }

        $battery->update([
            'rectifier_id'             => $matchingRectifier?->id,
            'building'                 => $validated['building'],
            'pic'                      => $validated['pic'],
            'type_pop'                 => $validated['type_pop'],
            'recti'                    => null,
            'nomor_recti'              => $nomorRecti,
            'nomor_bank'               => $validated['nomor_bank'],
            'merk_battery'             => $validated['merk_battery'],
            'tipe_battery'             => $validated['tipe_battery'],
            'jenis_battery'            => $validated['jenis_battery'],
            'kapasitas_battery'        => $kapasitasBattery,
            'kapasitas_uji'            => $kapasitasUji,
            'kapasitas_battery_persen' => $persen,
            'performa_baterai'         => $performa,
            'backup_timer'             => null,
            'tanggal_uji_terakhir'     => $validated['tanggal_uji_terakhir'] ?? null,
            'tanggal_penggantian'      => $validated['tanggal_penggantian'] ?? null,
            'status_uji'               => $statusUji,
            'area_sti'                 => $validated['area_sti'],
            'diupdate_oleh'            => Auth::id(),
        ]);

        return redirect()->route('batteries.show', [$pop->id, $battery->id])
            ->with('success', 'Data Baterai berhasil diperbarui!');
    }

    // 7. Menghapus Data Baterai
    public function destroy($pop_id, $id)
    {
        $pop = Pop::findOrFail($pop_id);
        $battery = Battery::where('pop_id', $pop->id)->findOrFail($id);

        $battery->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Baterai berhasil dihapus!',
            ]);
        }

        return redirect()->route('batteries.index', $pop->id)
            ->with('success', 'Data Baterai berhasil dihapus!');
    }

    // ─── Helper: Generate label nomor recti dari posisi rectifier ────────────
    private function generateNomorRecti(Pop $pop, int $rectifierId): string
    {
        $ids      = Rectifier::where('pop_id', $pop->id)
            ->orderBy('created_at')->orderBy('id')->pluck('id')->toArray();
        $position = array_search($rectifierId, $ids);
        $position = $position === false ? 0 : $position;
        return $pop->kode_pop . '_RECT' . str_pad($position + 1, 2, '0', STR_PAD_LEFT);
    }

    // ─── Helper: Hitung performa backup time (formula Excel) ─────────────────
    private function calcPerformaBackup(?float $backupTime, bool $dataLengkap): string
    {
        if (!$dataLengkap)          return 'DATA BLM LENGKAP';
        if ($backupTime === null)   return 'BLM UJI BATT';
        if ($backupTime >= 8)       return '1-EXCELLENT';
        if ($backupTime >= 6)       return '2-GOOD ENOUGH';
        if ($backupTime >= 4)       return '3-WARNING';
        return '4-ALERT';
    }

    // ─── Helper: Badge class dari performa backup ─────────────────────────────
    private function badgeFromPerforma(string $performa): string
    {
        return match ($performa) {
            '1-EXCELLENT'   => 'status-excellent',
            '2-GOOD ENOUGH' => 'status-good',
            '3-WARNING'     => 'status-warning',
            '4-ALERT'       => 'status-danger',
            default         => 'status-neutral',
        };
    }
}
