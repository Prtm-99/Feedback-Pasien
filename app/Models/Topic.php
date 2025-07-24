<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
        public function identitas()
    {
        return $this->belongsToMany(Identitas::class, 'identitas_topic', 'topic_id', 'identitas_id');
    }

}
