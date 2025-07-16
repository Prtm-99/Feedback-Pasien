<?php

namespace App\Models;
use App\Models\Dokter;
use Illuminate\Database\Eloquent\Model;

class UnitLayanan extends Model
{
    protected $table = 'unit_layanan';

    protected $fillable = [
        'nama_unit',
        'deskripsi'
    ];

        public function dokters()
    {
        return $this->hasMany(Dokter::class, 'unit_layanan_id');
    }
}

