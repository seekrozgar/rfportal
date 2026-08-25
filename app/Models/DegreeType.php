<?php
// app/Models/DegreeType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreeType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function jobs()
    {
        return $this->hasMany(GeneralJob::class, 'degree_type_id');
    }
}
