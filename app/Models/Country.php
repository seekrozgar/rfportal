<?php
// app/Models/Country.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'phone_code', 'is_active'];

    public function states()
    {
        return $this->hasMany(State::class);
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
