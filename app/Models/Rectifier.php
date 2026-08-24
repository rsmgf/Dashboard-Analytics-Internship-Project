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
}