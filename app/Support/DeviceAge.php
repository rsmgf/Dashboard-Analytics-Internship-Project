<?php

namespace App\Support;

trait DeviceAge
{
    public function getUmurPerangkatAttribute(): ?string
    {
        $tanggal = $this->tanggal_pemasangan;
        if (!$tanggal) {
            return null;
        }

        $start = $tanggal->copy()->startOfDay();
        $today = now()->startOfDay();
        if ($start->greaterThan($today)) {
            return '0 tahun 0 hari';
        }

        $years = (int) $start->diffInYears($today);
        $days = (int) $start->copy()->addYears($years)->diffInDays($today);

        return "{$years} tahun {$days} hari";
    }
}
