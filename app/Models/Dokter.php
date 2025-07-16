<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $fillable = ['nama', 'spesialis', 'unit_layanan_id'];

    public function unit()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_layanan_id');
    }
}
