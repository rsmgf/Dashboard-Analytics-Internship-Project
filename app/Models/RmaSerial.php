<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmaSerial extends Model
{
    protected $guarded = ['id'];

    public function type() { return $this->belongsTo(RmaType::class, 'rma_type_id'); }
    public function materials() { return $this->hasMany(RmaMaterial::class)->orderBy('id'); }
}
