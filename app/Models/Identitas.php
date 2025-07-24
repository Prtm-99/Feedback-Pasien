<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    protected $fillable = [
        'nama', 'no_hp', 'alamat', 'jenis_kelamin', 'usia',
        'pendidikan', 'pekerjaan', 'tanggal_survei', 'jam_survei',
        'unit_layanan_id','topic_id'
    ];

    public function unit()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_layanan_id');
    }
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'identitas_topic', 'identitas_id', 'topic_id');
    }

}
