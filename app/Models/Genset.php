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
}
