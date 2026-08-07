<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaMaterial extends Model
{
    protected $guarded = ['id'];

    // Material ini milik Satu RMA
    public function rma()
    {
        return $this->belongsTo(Rma::class);
    }
}
