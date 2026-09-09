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
        'jenis_battery',
        'kapasitas_battery',
        'kapasitas_uji',
        'kapasitas_battery_persen',
        'performa_baterai',
        'backup_timer',
        'tanggal_uji_terakhir',
        'tanggal_penggantian',
        'status_uji',
        'area_sti',
        'diupdate_oleh',
    ];

    protected $casts = [
        'kapasitas_battery'        => 'float',
        'kapasitas_uji'            => 'float',
        'kapasitas_battery_persen' => 'float',
        'backup_timer'             => 'float',
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
}
