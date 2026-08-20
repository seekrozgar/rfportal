<?php
// app/Models/City.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = ['state_id', 'name', 'is_active'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
