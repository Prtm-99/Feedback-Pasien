<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UnitLayanan extends Model
{
    protected $table = 'unit_layanan';

    protected $fillable = [
        'nama_unit',
        'deskripsi'
    ];


}

