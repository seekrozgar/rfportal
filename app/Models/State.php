<?php
// app/Models/State.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class State extends Model
{
    protected $fillable = ['country_id', 'name', 'code', 'is_active'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
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
