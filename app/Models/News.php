<?php
// app/Models/News.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'featured_image_original',
        'source',
        'news_date',
        'posted_by',
        'is_published',
        'views_count'
    ];

    protected $casts = [
        'news_date' => 'datetime',  // ✅ Change to datetime
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    // ✅ FIX: Proper date formatting
    public function getFormattedDateAttribute()
    {
        if ($this->news_date) {
            return $this->news_date->format('d M, Y');
        }
        return $this->created_at->format('d M, Y');
    }

    // ✅ Get featured image URL
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
