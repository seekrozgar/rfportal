<?php
// app/Models/JobCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'parent_id',
        'is_active',
        'order',
        'featured_image',
        'meta_title',
        'meta_description',
        'keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * ✅ Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * ✅ Parent category relationship
     */
    public function parent()
    {
        return $this->belongsTo(JobCategory::class, 'parent_id');
    }

    /**
     * ✅ Children categories relationship
     */
    public function children()
    {
        return $this->hasMany(JobCategory::class, 'parent_id')->orderBy('order');
    }

    /**
     * ✅ Recursive children (all descendants)
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * ✅ Jobs in this category
     */
    public function jobs()
    {
        return $this->hasMany(JobPosting::class, 'category_id');
    }

    /**
     * ✅ Get active jobs count
     */
    public function getActiveJobsCountAttribute()
    {
        return $this->jobs()->where('is_active', true)->count();
    }

    /**
     * ✅ Get total jobs count (including subcategories)
     */
    public function getTotalJobsCountAttribute()
    {
        $count = $this->jobs()->count();

        foreach ($this->children as $child) {
            $count += $child->total_jobs_count;
        }

        return $count;
    }

    /**
     * ✅ Get full hierarchy path
     */
    public function getPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' → ', $path);
    }

    /**
     * ✅ Get icon with default
     */
    public function getIconHtmlAttribute()
    {
        if ($this->icon) {
            return '<i class="' . $this->icon . '"></i>';
        }
        return '<i class="fas fa-folder"></i>';
    }

    /**
     * ✅ Get featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    /**
     * ✅ Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%");
    }
}
