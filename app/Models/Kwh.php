<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwh extends Model
{
    use HasFactory;

    protected $fillable = [
        'pop_id',
        'building',
        'pic',
        'type_pop',
        'id_customer_pln',
        'tanggal_pemeriksaan',
        'daya_ps_gi',
        'mcb_utama',
        'jumlah_phasa',
        'keberadaan_arrester',
        'merk_type_arrester',
        'status_utilisasi',
        'teg_rn',
        'arus_r',
        'teg_sn',
        'arus_s',
        'teg_tn',
        'arus_t',
        'teg_rs',
        'teg_st',
        'teg_rt',
        'teg_ng',
        'total_daya_terpakai',
        'persentase_utilisasi',
        'total_beban',
        'warna_r',
        'warna_s',
        'warna_t',
        'warna_n',
        'warna_g',
        'ukuran_r',
        'ukuran_s',
        'ukuran_t',
        'ukuran_n',
        'ukuran_g',
        'diupdate_oleh',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
    ];

    public function pop()
    {
        return $this->belongsTo(Pop::class);
    }

    public function photos()
    {
        return $this->hasMany(KwhPhoto::class)->orderBy('urutan');
    }

    public function diupdateOleh()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    public static function hitungDayaPsGi(float $mcb, string $jumlahPhasa): float
    {
        return match ($jumlahPhasa) {
            '1 Phasa' => 220 * $mcb,
            '3 Phasa' => 3 * 220 * $mcb,
            default => 0,
        };
    }

    public static function hitungTotalDayaTerpakai(string $jumlahPhasa, float $arusR, float $arusS, float $arusT): float
    {
        if ($jumlahPhasa === '1 Phasa') {
            return $arusR * 220;
        }

        // 3 Phasa
        $arusTerbesar = max($arusR, $arusS, $arusT);
        return $arusTerbesar * 380 * 0.75 * 1.73;
    }

    public static function hitungTotalBeban(float $arusR, float $arusS, float $arusT): float
    {
        return $arusR + $arusS + $arusT;
    }

    public static function hitungStatus(float $totalDayaTerpakai, float $dayaPsGi): array
    {
        $persen = $dayaPsGi > 0
            ? round(($totalDayaTerpakai / $dayaPsGi) * 100, 2)
            : 0;

        if ($persen <= 50) {
            $status = 'Good';
        } elseif ($persen <= 70) {
            $status = 'Warning';
        } else {
            $status = 'Alert';
        }

        return ['persentase_utilisasi' => $persen, 'status_utilisasi' => $status];
    }

    public function getDayaPsGiFormattedAttribute(): string
    {
        return number_format($this->daya_ps_gi, 0, ',', '.') . ' VA';
    }

    public function getTotalDayaTerpakaiFormattedAttribute(): string
    {
        return number_format($this->total_daya_terpakai, 2, ',', '.') . ' VA';
    }

    public function getTotalBebanFormattedAttribute(): string
    {
        return number_format($this->total_beban, 2, ',', '.') . ' A';
    }

    public function getPersentaseUtilisasiFormattedAttribute(): string
    {
        return number_format($this->persentase_utilisasi, 2, ',', '.') . '%';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_utilisasi) {
            'Good' => 'status-aman',
            'Warning' => 'status-waspada',
            default => 'status-kritis',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status_utilisasi) {
            'Good' => 'bi-shield-check',
            'Warning' => 'bi-exclamation-triangle-fill',
            default => 'bi-x-octagon-fill',
        };
    }
}
