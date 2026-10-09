<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaType extends Model
{
    protected $guarded = ['id'];

    public function rma() { return $this->belongsTo(Rma::class); }
    public function serials() { return $this->hasMany(RmaSerial::class)->orderBy('sort_order'); }
}
