<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pop', 
        'nama_pop', 
        'provinsi', 
        'kota_kabupaten', 
        'tipe_pop', 
        'jenis_bangunan',
    ];

    public function rectifiers()
    {
        return $this->hasMany(Rectifier::class);
    }
}