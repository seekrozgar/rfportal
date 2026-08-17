<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends BaseAttribute
{
    protected $fillable = ['name', 'slug', 'icon', 'parent_id', 'is_active'];

    public function parent()
    {
        return $this->belongsTo(JobCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(JobCategory::class, 'parent_id');
    }

    public function jobs()
    {
        return $this->hasMany(JobPosting::class, 'category_id');
    }
}
