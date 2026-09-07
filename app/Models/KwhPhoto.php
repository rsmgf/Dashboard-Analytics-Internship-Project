<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KwhPhoto extends Model
{
    protected $fillable = ['kwh_id', 'path', 'keterangan', 'urutan'];

    public function kwh()
    {
        return $this->belongsTo(Kwh::class);
    }
}
