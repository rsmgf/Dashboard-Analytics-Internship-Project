<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ac extends Model
{
    use HasFactory;

    protected $table = 'acs';

    protected $fillable = [
        'pop_id',
        'nomor_ac',
        'pic',
        'jenis_freon',
        'merk_ac',
        'tahun_manufaktur',
        'type_ac',
        'pk',
        'tanggal_instalasi',
        'tanggal_terakhir_pm',
        'status_ac',
        'photo_ac',
        'keterangan_gambar_ac',
        'diupdate_oleh',
    ];

    protected $casts = [
        'tahun_manufaktur'    => 'integer',
        'tanggal_instalasi'   => 'date',
        'tanggal_terakhir_pm' => 'date',
        'tanggal_pemeriksaan' => 'date',
    ];

    public function pop()
    {
        return $this->belongsTo(Pop::class);
    }

    public function diupdateOleh()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    protected static array $kolomKelengkapan = [
        'nomor_ac',
        'pic',
        'jenis_freon',
        'merk_ac',
        'tahun_manufaktur',
        'type_ac',
        'pk',
        'tanggal_instalasi',
        'tanggal_terakhir_pm',
        'tanggal_pemeriksaan',
        'status_ac',
        'photo_ac',
        'keterangan_gambar_ac'
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
        return $this->tanggal_terakhir_pm ? $this->tanggal_terakhir_pm->copy()->addMonths(3) : null;
    }

    public function getStatusAcAttribute(): string
    {
        if (!$this->tanggal_terakhir_pm) {
            return 'Belum PM';
        }

        return now()->startOfDay()->greaterThanOrEqualTo($this->pm_berikutnya->copy()->startOfDay())
            ? 'Jadwal PM'
            : 'Sudah PM';
    }

    public function getStatusPmAttribute(): array
    {
        if (!$this->tanggal_terakhir_pm) {
            return [
                'status' => 'Belum Preventive Maintenance',
                'class' => 'pm-badge-danger',
                'text' => '-'
            ];
        }

        $nextPm = $this->pm_berikutnya;
        $now = now()->startOfDay();
        $dueDate = $nextPm->copy()->startOfDay();

        if ($now->greaterThanOrEqualTo($dueDate)) {
            $lewatHari = $dueDate->diffInDays($now);
            return [
                'status' => 'Jadwal Preventive Maintenance',
                'class' => 'pm-badge-warning',
                'text' => $lewatHari === 0 ? '(jatuh tempo hari ini)' : '(lewat ' . $lewatHari . ' hari)'
            ];
        }

        $dalamHari = $now->diffInDays($dueDate);
        return [
            'status' => 'Sudah Preventive Maintenance',
            'class' => 'pm-badge-success',
            'text' => '(dalam ' . max(0, $dalamHari) . ' hari)'
        ];
    }
}
