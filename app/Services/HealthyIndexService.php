<?php

namespace App\Services;

use App\Models\Battery;
use App\Models\Pop;
use App\Models\Rectifier;

class HealthyIndexService
{
    public function calculateForPop(Pop $pop): array
    {
        $pop->loadMissing([
            'rectifiers.batteries',
            'kwhs',
            'acs',
            'gensets',
        ]);

        $missingCommon = [];

        if ($pop->rectifiers->isEmpty()) {
            $missingCommon[] = 'Rectifier';
        }

        $kwh = $pop->kwhs->first();

        if (!$kwh) {
            $missingCommon[] = 'kWh';
        } elseif ($kwh->persentase_utilisasi === null) {
            $missingCommon[] = 'data utilisasi kWh';
        }

        if ($pop->acs->isEmpty()) {
            $missingCommon[] = 'AC';
        }

        $results = $pop->rectifiers->map(function (Rectifier $rectifier) use (
            $pop,
            $kwh,
            $missingCommon
        ) {
            $missing = $missingCommon;

            if ($rectifier->utilisasi === null) {
                $missing[] = 'data utilisasi ' . ($rectifier->nomor_recti ?? 'Rectifier');
            }

            $batteries = $rectifier->batteries;

            if ($batteries->isEmpty()) {
                $missing[] = 'bank Battery untuk ' . ($rectifier->nomor_recti ?? 'Rectifier');
            } else {
                if ((float) $rectifier->beban <= 0) {
                    $missing[] = 'data beban ' . ($rectifier->nomor_recti ?? 'Rectifier');
                }

                if ($batteries->contains(fn(Battery $battery) => $battery->kapasitas_uji === null)) {
                    $missing[] = 'data kapasitas uji Battery untuk ' . ($rectifier->nomor_recti ?? 'Rectifier');
                }
            }

            $missing = array_values(array_unique($missing));

            if ($missing !== []) {
                return [
                    'rectifier_id' => $rectifier->id,
                    'rectifier_name' => $rectifier->nomor_recti ?? ('Rectifier ' . $rectifier->id),
                    'score' => null,
                    'status_key' => 'data_incomplete',
                    'status_label' => 'Data belum lengkap',
                    'missing' => $missing,
                    'components' => [],
                ];
            }

            $rectifierUtilisasi = (float) $rectifier->utilisasi;
            $kwhUtilisasi = (float) $kwh->persentase_utilisasi;

            // Rumus utilisasi dipertahankan sesuai ketentuan:
            // Good <= 50: poin penuh.
            // Warning > 50 dan < 80: gunakan pembagi 80.
            // Alert >= 80: 0 poin.
            $rectifierPoints = $this->utilizationPoints($rectifierUtilisasi, 25);
            $kwhPoints = $this->utilizationPoints($kwhUtilisasi, 10);

            // Battery dihitung dari total kapasitas uji bank yang terhubung
            // ke Rectifier ini, dibagi beban Rectifier yang sama.
            $totalCapacityTested = (float) $batteries->sum('kapasitas_uji');
            $backupTime = $totalCapacityTested / (float) $rectifier->beban;
            $batteryPoints = $this->batteryPoints($backupTime);

            // Skor AC adalah rata-rata poin semua AC pada POP.
            $acPointsPerUnit = $pop->acs->map(function ($ac) {
                if (!$ac->tanggal_terakhir_pm) {
                    return 0;
                }

                $nextPm = $ac->tanggal_terakhir_pm->copy()->addMonths(3);

                // Pada tanggal jatuh tempo tiga bulan, status masuk Jadwal PM.
                return now()->startOfDay()->greaterThanOrEqualTo($nextPm->startOfDay())
                    ? 5
                    : 15;
            });

            $acPoints = $acPointsPerUnit->avg() ?? 0;

            // Genset bersifat opsional. Jika tidak ada, skor penuh.
            // Jika ada: Sudah PM = 20, Jadwal PM = 5, Belum PM = 0.
            $genset = $pop->gensets->first();

            if (!$genset) {
                $gensetPoints = 20;
            } elseif (!$genset->tanggal_pm) {
                $gensetPoints = 0;
            } else {
                $nextPm = $genset->tanggal_pm->copy()->addMonths(6);

                $gensetPoints = now()->startOfDay()->greaterThanOrEqualTo($nextPm->startOfDay())
                    ? 5
                    : 20;
            }

            $total = $rectifierPoints + $kwhPoints + $batteryPoints + $acPoints + $gensetPoints;

            [$statusKey, $statusLabel] = $this->classify($total);

            return [
                'rectifier_id' => $rectifier->id,
                'rectifier_name' => $rectifier->nomor_recti ?? ('Rectifier ' . $rectifier->id),
                'score' => $total,
                'status_key' => $statusKey,
                'status_label' => $statusLabel,
                'missing' => [],
                'components' => [
                    'rectifier' => $rectifierPoints,
                    'kwh' => $kwhPoints,
                    'battery' => $batteryPoints,
                    'battery_backup_hours' => $backupTime,
                    'ac' => $acPoints,
                    'genset' => $gensetPoints,
                ],
            ];
        });

        return [
            'pop_id' => $pop->id,
            'pop_code' => $pop->kode_pop,
            'pop_name' => $pop->nama_pop_display,
            'rectifiers' => $results->values()->all(),
        ];
    }

    private function utilizationPoints(float $utilization, float $maxPoints): float
    {
        if ($utilization <= 50) {
            return $maxPoints;
        }

        if ($utilization >= 80) {
            return 0;
        }

        // Pembagi 80 dipakai sesuai rumus yang ditetapkan.
        return max(0, min(
            $maxPoints,
            $maxPoints - ((($utilization - 50) / 80) * $maxPoints)
        ));
    }

    private function batteryPoints(float $backupTime): float
    {
        if ($backupTime >= 8) {
            return 30;
        }

        if ($backupTime >= 6) {
            return 30 - (((8 - $backupTime) / 8) * 30);
        }

        if ($backupTime >= 4) {
            return 20 - (((6 - $backupTime) / 6) * 20);
        }

        return 0;
    }

    private function classify(float $score): array
    {
        if ($score >= 90) {
            return ['very_healthy', 'Very Healthy'];
        }

        if ($score >= 75) {
            return ['healthy', 'Healthy'];
        }

        if ($score >= 50) {
            return ['unhealthy', 'UnHealthy'];
        }

        return ['very_unhealthy', 'Very UnHealthy'];
    }
}
