<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;

    protected $fillable = [
        'pop_id',
        'rectifier_id',
        'building',
        'pic',
        'type_pop',
        'recti',
        'nomor_recti',
        'nomor_bank',
        'merk_battery',
        'tipe_battery',
        'tegangan',
        'jenis_battery',
        'kapasitas_battery',
        'kapasitas_uji',
        'vrla_1',
        'vrla_2',
        'vrla_3',
        'vrla_4',
        'kapasitas_battery_persen',
        'performa_baterai',
        'tanggal_uji_terakhir',
        'tanggal_penggantian',
        'status_uji',
        'photo_battery',
        'keterangan_gambar',
        'diupdate_oleh',
    ];

    protected $casts = [
        'tegangan'                 => 'float',
        'kapasitas_battery'        => 'float',
        'kapasitas_uji'            => 'float',
        'vrla_1'                   => 'float',
        'vrla_2'                   => 'float',
        'vrla_3'                   => 'float',
        'vrla_4'                   => 'float',
        'kapasitas_battery_persen' => 'float',
        'tanggal_uji_terakhir'     => 'date',
        'tanggal_penggantian'      => 'date',
    ];

    // Relasi ke POP (Parent)
    public function pop()
    {
        return $this->belongsTo(Pop::class);
    }

    // Relasi ke Rectifier (Optional parent)
    public function rectifier()
    {
        return $this->belongsTo(Rectifier::class);
    }

    // Relasi ke User yang terakhir mengubah data
    public function diupdateOleh()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    protected static array $kolomKelengkapanSelalu = [
        'rectifier_id',
        'building',
        'pic',
        'type_pop',
        'nomor_bank',
        'merk_battery',
        'tipe_battery',
        'tegangan',
        'jenis_battery',
        'kapasitas_battery',
        'kapasitas_uji',
        'tanggal_uji_terakhir',
        'tanggal_penggantian',
        'photo_battery',
        'keterangan_gambar',
    ];

    protected static array $kolomKelengkapanVrla = ['vrla_1', 'vrla_2', 'vrla_3', 'vrla_4'];

    public function getKelengkapanFormAttribute(): array
    {
        $terisi = 0;
        $belum_diisi = [];
        
        foreach (static::$kolomKelengkapanSelalu as $kolom) {
            if ($this->{$kolom} !== null && $this->{$kolom} !== '') {
                $terisi++;
            } else {
                $belum_diisi[] = ucwords(str_replace('_', ' ', $kolom));
            }
        }

        $total = count(static::$kolomKelengkapanSelalu);

        if ($this->jenis_battery === 'VRLA') {
            $total += count(static::$kolomKelengkapanVrla);
            foreach (static::$kolomKelengkapanVrla as $kolom) {
                if ($this->{$kolom} !== null && $this->{$kolom} !== '') {
                    $terisi++;
                } else {
                    $belum_diisi[] = ucwords(str_replace('_', ' ', $kolom));
                }
            }
        }

        return ['terisi' => $terisi, 'total' => $total, 'belum_diisi' => $belum_diisi];
    }

    public function getPersenKelengkapanAttribute(): float
    {
        $k = $this->kelengkapan_form;
        return $k['total'] > 0 ? round(($k['terisi'] / $k['total']) * 100, 2) : 0;
    }

    // ============ PERFORMA BATERAI — donut & badge ============

    public function getPmBerikutnyaAttribute(): ?\Carbon\Carbon
    {
        return $this->tanggal_uji_terakhir ? $this->tanggal_uji_terakhir->copy()->addYear() : null;
    }

    public function getStatusUjiLiveAttribute(): string
    {
        if (!$this->tanggal_uji_terakhir) return 'BLM UJI BATT';
        $diffDays = now()->diffInDays($this->tanggal_uji_terakhir, false);
        return abs($diffDays) >= 365 ? 'JADWAL UJI BATT' : 'SUDAH UJI BATT';
    }

    public function getStatusUjiLabelAttribute(): string
    {
        return match ($this->status_uji_live) {
            'SUDAH UJI BATT' => 'Sudah Uji Baterai',
            'JADWAL UJI BATT' => 'Jadwal Uji Baterai',
            default => 'Belum Uji Baterai',
        };
    }

    public function getStatusUjiBadgeClassAttribute(): string
    {
        return match ($this->status_uji_live) {
            'SUDAH UJI BATT' => 'uji-sudah',
            'JADWAL UJI BATT' => 'uji-jadwal',
            default => 'uji-belum',
        };
    }

    public function getPerformaLabelBersihAttribute(): string
    {
        return $this->performa_baterai ? preg_replace('/^\d+-/', '', $this->performa_baterai) : 'BLM UJI BATT';
    }

    public function getPerformaColorAttribute(): string
    {
        return match ($this->performa_baterai) {
            '1-EXCELLENT' => '#22c55e',
            '2-GOOD ENOUGH' => '#eab308',
            '3-WARNING' => '#f97316',
            '4-ALERT' => '#ef4444',
            default => '#cbd5e1',
        };
    }

    public function getPerformaTrackAttribute(): string
    {
        return match ($this->performa_baterai) {
            '1-EXCELLENT' => 'rgba(34,197,94,0.15)',
            '2-GOOD ENOUGH' => 'rgba(234,179,8,0.15)',
            '3-WARNING' => 'rgba(249,115,22,0.15)',
            '4-ALERT' => 'rgba(239,68,68,0.15)',
            default => 'rgba(203,213,225,0.3)',
        };
    }

    public function getPerformaBadgeClassAttribute(): string
    {
        return match ($this->performa_baterai) {
            '1-EXCELLENT' => 'perf-excellent',
            '2-GOOD ENOUGH' => 'perf-good',
            '3-WARNING' => 'perf-warning',
            '4-ALERT' => 'perf-alert',
            default => 'perf-none',
        };
    }

    public static function performaBackupClass(?float $backupTime, bool $dataLengkap): array
    {
        if (!$dataLengkap) return ['label' => 'DATA BLM LENGKAP', 'class' => 'status-neutral'];
        if ($backupTime === null) return ['label' => 'BLM UJI BATT', 'class' => 'status-neutral'];
        if ($backupTime >= 8) return ['label' => 'EXCELLENT', 'class' => 'status-excellent'];
        if ($backupTime >= 6) return ['label' => 'GOOD ENOUGH', 'class' => 'status-good'];
        if ($backupTime >= 4) return ['label' => 'WARNING', 'class' => 'status-warning'];
        return ['label' => 'ALERT', 'class' => 'status-danger'];
    }

    public function getKapasitasUjiFormattedAttribute(): string
    {
        return $this->kapasitas_uji !== null
            ? number_format($this->kapasitas_uji, 2, ',', '.') . ' Ah'
            : '-';
    }
}
