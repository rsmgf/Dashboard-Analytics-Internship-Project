<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rectifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'pop_id',
        'nama_alias',
        'deskripsi',
        'tanggal_pemeriksaan',
        'pic',
        'merk',
        'type',
        'sn_rectifier',
        'kapasitas_slot',
        'couple',
        'type_modul_controller',
        'type_modul_power',
        'kapasitas_rectifier',
        'beban',
        'utilisasi',
        'foto_rectifier',
        'diupdate_oleh',
    ];

    protected static array $kolomKelengkapan = [
        'nama_alias',
        'deskripsi',
        'tanggal_pemeriksaan',
        'pic',
        'merk',
        'type',
        'sn_rectifier',
        'kapasitas_slot',
        'couple',
        'type_modul_controller',
        'type_modul_power',
        'kapasitas_rectifier',
        'beban',
        'utilisasi',
        'foto_rectifier',
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

    // Relasi ke Modul (Child)
    public function modules()
    {
        return $this->hasMany(RectifierModule::class);
    }

    // Relasi ke Output MCB (Child)
    public function outputs()
    {
        return $this->hasMany(RectifierOutput::class);
    }

    // Accessor untuk menghitung sisa slot terpakai secara otomatis
    public function getSisaSlotAttribute()
    {
        return $this->kapasitas_slot - $this->modules()->count();
    }

    public function getStatusUtilisasiAttribute(): ?string
    {
        if ($this->utilisasi === null) {
            return null;
        }

        if ($this->utilisasi <= 50) {
            return 'Good';
        } elseif ($this->utilisasi <= 70) {
            return 'Warning';
        } else {
            return 'Alert';
        }
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_utilisasi) {
            'Good' => 'status-aman',
            'Warning' => 'status-waspada',
            'Alert' => 'status-kritis',
            default => 'status-kosong',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status_utilisasi) {
            'Good' => 'bi-shield-check',
            'Warning' => 'bi-exclamation-triangle-fill',
            'Alert' => 'bi-x-octagon-fill',
            default => 'bi-dash-circle',
        };
    }

    public function getUtilisasiFormattedAttribute(): string
    {
        return $this->utilisasi !== null
            ? number_format($this->utilisasi, 2, ',', '.') . '%'
            : '-';
    }

    // ============ KELENGKAPAN FORM (X / Y) ============

    public function getKelengkapanFormAttribute(): array
    {
        $terisi = 0;

        foreach (static::$kolomKelengkapan as $kolom) {
            $nilai = $this->{$kolom};
            if ($nilai !== null && $nilai !== '') {
                $terisi++;
            }
        }

        $totalKolomStatis = count(static::$kolomKelengkapan);
        $totalSlot = (int) $this->kapasitas_slot;
        $jumlahModulTerisi = min($this->modules()->count(), $totalSlot); // cap, jaga-jaga modul lebih banyak dari slot

        return [
            'terisi' => $terisi + $jumlahModulTerisi,
            'total' => $totalKolomStatis + $totalSlot,
        ];
    }

    public function getPersenKelengkapanAttribute(): float
    {
        $k = $this->kelengkapan_form;
        return $k['total'] > 0 ? round(($k['terisi'] / $k['total']) * 100, 2) : 0;
    }
}
