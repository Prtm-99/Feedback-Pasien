<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['topic_id', 'text'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}

