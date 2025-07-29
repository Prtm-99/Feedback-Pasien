<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_hp',
        'jenis_kelamin',
        'usia',
        'pendidikan',
        'pekerjaan',
        'unit_layanan_id',
        'tanggal_survei',
        'jam_survei'
    ];

    protected $casts = [
        'tanggal_survei' => 'date',
    ];

    // Relasi ke Unit Layanan
    public function unit()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_layanan_id');
    }

    // Relasi many-to-many dengan Topic
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'identitas_topic', 'identitas_id', 'topic_id')
                   ->withTimestamps()
                   ->withPivot(['created_at', 'updated_at']);
    }

    // Relasi ke Feedback
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}