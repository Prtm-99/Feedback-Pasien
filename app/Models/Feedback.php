<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'question_id',
        'identitas_id',
        'unit_id',
        'dokter_id',
        'topic_id',
        'answer',
        'comment',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function identitas()
    {
        return $this->belongsTo(Identitas::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

        public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}

