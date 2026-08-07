<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rma extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['kerusakan' => 'array', 'tanggal' => 'date'];

    // Satu RMA punya Banyak Material (Foto)
    public function materials()
    {
        return $this->hasMany(RmaMaterial::class);
    }
}
