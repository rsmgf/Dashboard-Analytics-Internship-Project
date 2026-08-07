<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rectifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'pop_id',
        'nama_alias',
        'deskripsi',
        'merk',
        'type',
        'sn_rectifier',
        'kapasitas_slot',
    ];

    // Relasi ke POP (Parent)
    public function pop()
    {
        return $this->belongsTo(Pop::class);
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
}