<?php

namespace App\Http\Controllers;

use App\Models\Kwh;
use App\Models\Pop;
use App\Models\Rectifier;
use Illuminate\Http\Request;
use App\Models\Battery;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Services\HealthyIndexService;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPop = Pop::count();
        $canFilter = auth()->user()->can('dashboard.filter');
        $canEkspor = auth()->user()->can('dashboard.ekspor');
        $kpi = $this->kpiSummary();
        $healthyIndexService = app(HealthyIndexService::class);

        $healthyIndexPops = Pop::whereRaw('LOWER(provinsi) LIKE ?', ['%jambi%'])
            ->with([
                'rectifiers.batteries',
                'kwhs',
                'acs',
                'gensets',
            ])
            ->get()
            ->map(fn(Pop $pop) => $healthyIndexService->calculateForPop($pop))
            ->values();

        $rectifierHealthStatuses = $healthyIndexPops
            ->flatMap(fn(array $pop) => $pop['rectifiers'] ?? [])
            ->filter(fn(array $index) => $index['score'] !== null)
            ->pluck('status_key');

        $totalRectifierHealthy = $rectifierHealthStatuses
            ->filter(fn(string $status) => in_array($status, ['very_healthy', 'healthy'], true))
            ->count();

        $totalRectifierUnhealthy = $rectifierHealthStatuses
            ->filter(fn(string $status) => in_array($status, ['unhealthy', 'very_unhealthy'], true))
            ->count();

        $totalRectifierWithHealthyIndex = $rectifierHealthStatuses->count();

        $latestStatusNotifications = collect();
        $activeRole = session('active_role')
            ?? (auth()->user()->hasRole('manajer') ? 'manajer' : 'super_admin');

        if (in_array($activeRole, ['manajer', 'super_admin'], true)) {
            \App\Models\Notification::regenerateStatus();
            $latestStatusNotifications = \App\Models\Notification::status()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard', array_merge(
            compact('totalPop', 'canFilter', 'canEkspor', 'latestStatusNotifications'),
            $kpi,
            [
                'totalPendingApproval' => $this->pendingApprovalCount(),
                'populasiPop'          => $this->popPopulation(),
                'healthyIndexPops' => $healthyIndexPops,
                'totalRectifierHealthy' => $totalRectifierHealthy,
                'totalRectifierUnhealthy' => $totalRectifierUnhealthy,
                'totalRectifierWithHealthyIndex' => $totalRectifierWithHealthyIndex,
            ]
        ));
    }

    private function kpiSummary(): array
    {
        // di-cache 5 menit karena menghitung accessor untuk semua POP cukup berat
        return Cache::remember('dashboard.kpi', now()->addMinutes(5), function () {
            $pops = Pop::with(['rectifiers', 'kwhs', 'batteries', 'acs', 'gensets'])->get();

            $totalPopAlert = 0;
            $totalPopWarning = 0;

            foreach ($pops as $pop) {
                $levels = collect();

                foreach ($pop->rectifiers as $r) {
                    $levels->push($this->normalizeLevel($r->status_utilisasi));
                }
                foreach ($pop->kwhs as $k) {
                    $levels->push($this->normalizeLevel($k->status_utilisasi));
                }
                foreach ($this->batteryGroups($pop) as $g) {
                    $levels->push(match ($g['level']) {
                        'alert' => 'alert',
                        'warn'  => 'warning',
                        default => 'good',   // excellent & good enough dianggap sehat
                    });
                }
                foreach ($pop->acs as $ac) {
                    $levels->push($this->levelFromPmClass($ac->status_pm['class'] ?? null));
                }
                foreach ($pop->gensets as $g) {
                    $levels->push($this->levelFromPmClass($g->status_pm['class'] ?? null));
                }

                if ($levels->contains('alert')) {
                    $totalPopAlert++;
                } elseif ($levels->contains('warning')) {
                    $totalPopWarning++;
                }
            }

            return compact('totalPopAlert', 'totalPopWarning');
        });
    }

    public function deviceStatus()
    {
        $data = Cache::remember('dashboard.deviceStatus', now()->addMinutes(5), function () {
            return $this->buildDeviceStatus();
        });

        return response()->json($data);
    }

    /**
     * Struktur hasil:
     * [ 'rectifier' => ['good' => [item...], 'warning' => [...], 'alert' => [...]], 'kwh' => ..., ... ]
     * Satu item = satu UNIT perangkat (bukan satu POP).
     */
    private function buildDeviceStatus(): array
    {
        $pops = Pop::with([
            'rectifiers',
            'kwhs',
            'batteries',
            'acs',
            'gensets',
        ])->get();

        $config = [
            'rectifier' => [
                'rel' => 'rectifiers',
                'level' => fn($d) => $this->normalizeLevel($d->status_utilisasi),
                'unit' => fn($d) => $d->nomor_recti ?? $d->nama_alias ?? '-',
                'sort_value' => fn($d) => $d->utilisasi !== null
                    ? (float) $d->utilisasi
                    : null,
            ],
            'kwh' => [
                'rel' => 'kwhs',
                'level' => fn($d) => $this->normalizeLevel($d->status_utilisasi),
                'unit' => fn($d) => $d->nomor_kwh ?? '-',
                'sort_value' => fn($d) => $d->persentase_utilisasi !== null
                    ? (float) $d->persentase_utilisasi
                    : null,
            ],
            'ac' => [
                'rel' => 'acs',
                'level' => fn($d) => match ($d->status_pm['class'] ?? null) {
                    'pm-badge-success' => 'sudah_pm',
                    'pm-badge-warning' => 'jadwal_pm',
                    'pm-badge-danger' => 'belum_pm',
                    default => 'belum_pm',
                },
                'unit' => fn($d) => $d->nomor_ac ?? '-',
                'sort_value' => fn($d) => $d->pm_berikutnya?->timestamp,
                'statuses' => [
                    'sudah_pm' => [],
                    'jadwal_pm' => [],
                    'belum_pm' => [],
                ],
            ],
            'genset' => [
                'rel' => 'gensets',
                'level' => fn($d) => match ($d->status_pm['class'] ?? null) {
                    'pm-badge-success' => 'sudah_pm',
                    'pm-badge-warning' => 'jadwal_pm',
                    'pm-badge-danger' => 'belum_pm',
                    default => 'belum_pm',
                },
                'unit' => fn($d) => $d->nomor_genset ?? '-',
                'sort_value' => fn($d) => $d->pm_berikutnya?->timestamp,
                'statuses' => [
                    'sudah_pm' => [],
                    'jadwal_pm' => [],
                    'belum_pm' => [],
                ],
            ],
        ];

        $result = [];

        foreach ($config as $key => $cfg) {
            $result[$key] = $cfg['statuses'] ?? [
                'good' => [],
                'warning' => [],
                'alert' => [],
            ];

            foreach ($pops as $pop) {
                $devices = $pop->{$cfg['rel']};
                $showDeviceId = in_array($key, ['rectifier', 'ac'], true)
                    && $devices->count() > 1;

                foreach ($devices as $device) {
                    $level = ($cfg['level'])($device);

                    $result[$key][$level][] = [
                        'pop_id' => $pop->id,
                        'kode' => $pop->kode_pop,
                        'pop_name' => $pop->nama_pop_display,
                        'unit' => (string) ($cfg['unit'])($device),
                        'show_device_id' => $showDeviceId,
                        'device_id' => $device->id,
                        'sort_value' => ($cfg['sort_value'])($device),
                    ];
                }
            }
        }

        $result['battery'] = [
            'excellent' => [],
            'enough' => [],
            'warn' => [],
            'alert' => [],
        ];

        foreach ($pops as $pop) {
            foreach ($this->batteryGroups($pop) as $group) {
                $result['battery'][$group['level']][] = [
                    'pop_id' => $pop->id,
                    'kode' => $pop->kode_pop,
                    'pop_name' => $pop->nama_pop_display,
                    'unit' => $group['unit'],
                    'device_id' => $group['device_id'],
                    'sort_value' => $group['sort_value'],
                ];
            }
        }

        foreach ($result as $deviceKey => &$statusGroups) {
            foreach ($statusGroups as $statusKey => &$items) {
                $descending = match ($deviceKey) {
                    'rectifier', 'kwh' => in_array($statusKey, ['alert', 'warning'], true),
                    'battery' => in_array($statusKey, ['excellent', 'enough'], true),
                    'ac', 'genset' => $statusKey === 'sudah_pm',
                    default => false,
                };

                usort($items, function (array $a, array $b) use ($descending): int {
                    $aValue = $a['sort_value'];
                    $bValue = $b['sort_value'];

                    // Data tanpa nilai pembanding diletakkan setelah data yang punya nilai.
                    if ($aValue === null && $bValue !== null) {
                        return 1;
                    }

                    if ($aValue !== null && $bValue === null) {
                        return -1;
                    }

                    if ($aValue !== null && $bValue !== null) {
                        $comparison = $aValue <=> $bValue;

                        if ($comparison !== 0) {
                            return $descending ? -$comparison : $comparison;
                        }
                    }

                    // Jika nilainya sama, gunakan nama POP lalu ID perangkat agar urutan stabil.
                    $popComparison = strcasecmp($a['pop_name'] ?? '', $b['pop_name'] ?? '');

                    return $popComparison !== 0
                        ? $popComparison
                        : (($a['device_id'] ?? 0) <=> ($b['device_id'] ?? 0));
                });

                foreach ($items as &$item) {
                    unset($item['sort_value']);
                }
                unset($item);
            }
            unset($items);
        }
        unset($statusGroups);

        return $result;
    }

    private function batteryGroups(Pop $pop): array
    {
        $out = [];

        foreach ($pop->batteries->groupBy('rectifier_id') as $rectifierId => $group) {
            $rectifier = $rectifierId ? $pop->rectifiers->firstWhere('id', $rectifierId) : null;

            $totalUji = $group->sum('kapasitas_uji');
            $beban = ($rectifier && (float) $rectifier->beban > 0) ? (float) $rectifier->beban : null;
            $backupTime = ($beban && $totalUji > 0)
                ? (float) $totalUji / $beban
                : null;

            $performa = Battery::performaBackupClass($backupTime, $beban !== null);

            $level = match ($performa['class'] ?? null) {
                'status-excellent' => 'excellent',
                'status-good'      => 'enough',   // Good Enough
                'status-warning'   => 'warn',
                'status-danger'    => 'alert',
                default            => null,       // neutral / belum ada data
            };

            if (!$level) {
                continue;
            }

            $out[] = [
                'level'     => $level,
                'unit' => $rectifier?->nomor_recti ?? ('Rectifier ' . $rectifierId),
                'device_id' => $rectifierId,
                'sort_value' => $backupTime,
            ];
        }

        return $out;
    }

    private function normalizeLevel(?string $value): string
    {
        $v = strtoupper((string) $value);

        return match (true) {
            str_contains($v, 'ALERT') => 'alert',
            str_contains($v, 'WARNING') => 'warning',
            default => 'good',
        };
    }

    private function levelFromPmClass(?string $class): string
    {
        return match ($class) {
            'pm-badge-danger' => 'alert',
            'pm-badge-warning' => 'warning',
            default => 'good',
        };
    }

    private function pendingApprovalCount(): int
    {
        // SESUAIKAN dengan struktur tabel users kamu, contoh:
        return User::where('is_active', '0')->count();
        // alternatif: User::whereNull('approved_at')->count();
        // alternatif: User::where('is_approved', false)->count();
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
        $batteries = $pop->batteries()->with('rectifier')->orderBy('rectifier_id')->orderBy('nomor_bank')->get();

        $batteryGroups = $batteries->groupBy('rectifier_id')->map(function ($group) use ($rectifiers) {
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
        $healthyIndex = app(HealthyIndexService::class)->calculateForPop($pop);

        return view(
            'dashboard.partials.pop-summary',
            compact(
                'pop',
                'rectifiers',
                'kwhs',
                'batteries',
                'batteryGroups',
                'acs',
                'gensets',
                'healthyIndex'
            )
        );
    }

    // Isi dropdown Kota/Kabupaten di panel filter
    public function filterOptions()
    {
        $kotaList = Pop::whereNotNull('kota_kabupaten')
            ->where('kota_kabupaten', '!=', '')
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

    private function popPopulation(): array
    {
        // SESUAIKAN: ganti 'tipe_pop' dengan nama kolom tipe di tabel pops
        $rows = Pop::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(tipe_pop), ''), 'Belum diisi') as tipe, COUNT(*) as total")
            ->groupBy('tipe')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $rows->pluck('tipe')->values(),
            'data'   => $rows->pluck('total')->map(fn($v) => (int) $v)->values(),
        ];
    }
}
