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
    ];

    public function pop()
    {
        return $this->belongsTo(Pop::class);
    }

    public function diupdateOleh()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }
}
