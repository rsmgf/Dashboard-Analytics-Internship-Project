<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RectifierModule extends Model
{
    use HasFactory;

    protected $fillable = ['rectifier_id', 'sn_modul', 'kapasitas_ampere'];

    public function rectifier()
    {
        return $this->belongsTo(Rectifier::class);
    }
}
