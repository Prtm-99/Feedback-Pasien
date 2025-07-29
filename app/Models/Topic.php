<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = [
        'name',
        'is_default',
        'category',
        'is_active'
    ];

    // Relasi many-to-many dengan Identitas
    public function identitas(): BelongsToMany
    {
        return $this->belongsToMany(Identitas::class, 'identitas_topic')
            ->withTimestamps();
    }

    // Relasi ke Question dengan pengurutan
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)
            ->orderBy('order')
            ->orderBy('created_at');
    }

    // Scope untuk topik default
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Scope untuk topik khusus
    public function scopeSpecial($query)
    {
        return $query->where('is_default', false)
            ->whereIn('name', ['Farmasi', 'Laboratorium', 'Radiologi']);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($topic) {
            // Pastikan hanya ada satu topik default
            if ($topic->is_default) {
                self::where('is_default', true)
                    ->where('id', '!=', $topic->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}