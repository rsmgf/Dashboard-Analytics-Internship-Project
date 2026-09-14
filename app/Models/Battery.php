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
}
