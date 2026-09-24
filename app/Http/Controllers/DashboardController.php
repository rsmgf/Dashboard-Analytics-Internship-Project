<?php

namespace App\Http\Controllers;

use App\Models\Kwh;
use App\Models\Pop;
use App\Models\Rectifier;
use Illuminate\Http\Request;
use App\Models\Battery;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPop = Pop::count();
        $canFilter = auth()->user()->can('dashboard.filter.read');

        return view('dashboard', compact('totalPop', 'canFilter'));
    }

    public function searchPop(Request $request)
    {
        $q = $request->query('q', '');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $pops = Pop::where('nama_pop', 'like', "%{$q}%")
            ->orWhere('kode_pop', 'like', "%{$q}%")
            ->limit(8)
            ->get(['id', 'nama_pop', 'kode_pop', 'kota_kabupaten']);

        return response()->json($pops);
    }

    public function popSummary(Pop $pop)
    {
        $rectifiers = $pop->rectifiers()->with('modules')->get();
        $kwhs = $pop->kwhs()->get();
        $batteries = $pop->batteries()->with('rectifier')->orderBy('nomor_recti')->orderBy('nomor_bank')->get();

        $batteryGroups = $batteries->groupBy('nomor_recti')->map(function ($group) use ($rectifiers) {
            $rectifierId = $group->first()->rectifier_id;
            $rectifier = $rectifierId ? $rectifiers->firstWhere('id', $rectifierId) : null;

            $totalUji = $group->sum('kapasitas_uji');
            $beban = ($rectifier && (float) $rectifier->beban > 0) ? (float) $rectifier->beban : null;
            $backupTime = ($beban && $totalUji > 0) ? round($totalUji / $beban, 2) : null;
            $performa = Battery::performaBackupClass($backupTime, $beban !== null);

            return [
                'rectifier' => $rectifier,
                'backup_time' => $backupTime,
                'performa_label' => $performa['label'],
                'performa_class' => $performa['class'],
                'banks' => $group->values(),
            ];
        })->values();

        $acs = $pop->acs()->get();
        $gensets = $pop->gensets()->get();

        return view('dashboard.partials.pop-summary', compact('pop', 'rectifiers', 'kwhs', 'batteries', 'batteryGroups', 'acs', 'gensets'));
    }

    // Isi dropdown Kota/Kabupaten di panel filter
    public function filterOptions()
    {
        $kotaList = Pop::whereNotNull('kota_kabupaten')
            ->distinct()
            ->orderBy('kota_kabupaten')
            ->pluck('kota_kabupaten');

        return response()->json(['kota' => $kotaList]);
    }

    public function filterPop(Request $request)
    {
        $kota = $request->query('kota_kabupaten');
        $status = $request->query('status_utilisasi'); // Good / Warning / Alert
        $kelengkapan = $request->query('kelengkapan'); // lengkap / belum_lengkap

        $pops = Pop::query()
            ->when($kota, fn($q) => $q->where('kota_kabupaten', $kota))
            ->with(['rectifiers', 'kwhs'])
            ->get();

        // Saring lebih lanjut di level koleksi (status/kelengkapan dihitung dari accessor Model, bukan kolom DB langsung)
        if ($status) {
            $pops = $pops->filter(function ($pop) use ($status) {
                $adaRectifierCocok = $pop->rectifiers->contains(fn($r) => $r->status_utilisasi === $status);
                $adaKwhCocok = $pop->kwhs->contains(fn($k) => $k->status_utilisasi === $status);
                return $adaRectifierCocok || $adaKwhCocok;
            });
        }

        if ($kelengkapan) {
            $pops = $pops->filter(function ($pop) use ($kelengkapan) {
                $totalPerangkat = $pop->rectifiers->count() + $pop->kwhs->count();

                if ($totalPerangkat === 0) {
                    return $kelengkapan === 'belum_lengkap';
                }

                $adaBelumLengkap =
                    $pop->rectifiers->contains(fn($r) => $r->kelengkapan_form['terisi'] < $r->kelengkapan_form['total']) ||
                    $pop->kwhs->contains(fn($k) => $k->kelengkapan_form['terisi'] < $k->kelengkapan_form['total']);

                return $kelengkapan === 'belum_lengkap' ? $adaBelumLengkap : !$adaBelumLengkap;
            });
        }

        $pops = $pops->values();

        return view('dashboard.partials.filter-results', compact('pops'));
    }
}
