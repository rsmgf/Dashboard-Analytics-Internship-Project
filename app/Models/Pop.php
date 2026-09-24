<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    /**
     * Nama POP tanpa prefix ID POP (untuk tampilan web).
     * DB menyimpan format "KODE_POP Nama POP" — cukup tampilkan bagian namanya.
     */
    public function getNamaPopDisplayAttribute(): string
    {
        $nama = $this->nama_pop ?? '';
        // Jika nama_pop diawali dengan kode_pop (misal "113030 Nama POP" atau "POP_113030_Nama"),
        // strip prefix tersebut agar yang tampil hanya nama bersihnya.
        if ($this->kode_pop) {
            $patterns = [
                '/^' . preg_quote($this->kode_pop, '/') . '[_\-\s]+/i',
                '/^POP_' . preg_quote($this->kode_pop, '/') . '[_\-\s]+/i',
            ];
            foreach ($patterns as $pattern) {
                $cleaned = preg_replace($pattern, '', $nama);
                if ($cleaned && $cleaned !== $nama) {
                    return trim($cleaned);
                }
            }
        }
        return trim($nama);
    }

    public function rectifiers()
    {
        return $this->hasMany(Rectifier::class);
    }

    public function kwhs()
    {
        return $this->hasMany(Kwh::class);
    }

    public function batteries()
    {
        return $this->hasMany(Battery::class);
    }

    public function gensets()
    {
        return $this->hasMany(Genset::class);
    }

    public function acs()
    {
        return $this->hasMany(Ac::class);
    }
}