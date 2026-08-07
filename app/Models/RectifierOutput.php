<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RectifierOutput extends Model
{
    use HasFactory;

    protected $fillable = ['rectifier_id', 'merk_mcb', 'kapasitas_mcb', 'peruntukan'];

    public function rectifier()
    {
        return $this->belongsTo(Rectifier::class);
    }
}