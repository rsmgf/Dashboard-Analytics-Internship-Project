<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genset extends Model
{
    use HasFactory;

    protected $fillable = [
        'pop_id',
        'nomor_genset',
        'pic',
        'bentuk_fisik',
        'merk_genset',
        'model',
        'sn_genset',
        'kapasitas_kva',
        'tipe_engine',
        'sn_engine',
        'tahun_pasang',
        'tanggal_pm',
        'status_genset',
        'photo_genset',
        'keterangan_gambar_genset',
        'photo_engine',
        'keterangan_gambar_engine',
        'diupdate_oleh',
    ];

    protected $casts = [
        'kapasitas_kva' => 'float',
        'tahun_pasang'  => 'integer',
        'tanggal_pm'    => 'date',
    ];

    // Relasi ke POP (Parent)
    public function pop()
    {
        return $this->belongsTo(Pop::class);
    }

    // Relasi ke User yang terakhir mengubah data
    public function diupdateOleh()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    protected static array $kolomKelengkapan = [
        'nomor_genset', 'pic', 'bentuk_fisik', 'merk_genset', 'model', 'sn_genset',
        'kapasitas_kva', 'tipe_engine', 'sn_engine', 'tahun_pasang', 'tanggal_pm',
        'status_genset', 'photo_genset', 'keterangan_gambar_genset',
        'photo_engine', 'keterangan_gambar_engine'
    ];

    public function getKelengkapanFormAttribute(): array
    {
        $terisi = 0;
        $belum_diisi = [];

        foreach (static::$kolomKelengkapan as $kolom) {
            $nilai = $this->{$kolom};
            if ($nilai !== null && $nilai !== '') {
                $terisi++;
            } else {
                $belum_diisi[] = ucwords(str_replace('_', ' ', $kolom));
            }
        }

        return [
            'terisi' => $terisi,
            'total' => count(static::$kolomKelengkapan),
            'belum_diisi' => $belum_diisi,
        ];
    }

    public function getPersenKelengkapanAttribute(): float
    {
        $k = $this->kelengkapan_form;
        return $k['total'] > 0 ? round(($k['terisi'] / $k['total']) * 100, 2) : 0;
    }

    public function getPmBerikutnyaAttribute(): ?\Carbon\Carbon
    {
        return $this->tanggal_pm ? $this->tanggal_pm->copy()->addMonths(6) : null;
    }

    public function getStatusPmAttribute(): array
    {
        if (!$this->tanggal_pm) {
            return [
                'status' => 'Belum Preventive Maintenance',
                'class' => 'pm-badge-danger',
                'text' => '-'
            ];
        }

        $nextPm = $this->pm_berikutnya;
        $now = now();

        if ($now->startOfDay()->greaterThan($nextPm->startOfDay())) {
            $lewatHari = $nextPm->startOfDay()->diffInDays($now->startOfDay());
            return [
                'status' => 'Jadwal Preventive Maintenance',
                'class' => 'pm-badge-warning',
                'text' => '(lewat ' . max(1, $lewatHari) . ' hari)'
            ];
        }

        $dalamHari = $now->startOfDay()->diffInDays($nextPm->startOfDay());
        return [
            'status' => 'Sudah Preventive Maintenance',
            'class' => 'pm-badge-success',
            'text' => '(dalam ' . max(0, $dalamHari) . ' hari)'
        ];
    }
}
