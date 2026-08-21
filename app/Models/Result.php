<?php
// app/Models/Result.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Result extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'file_original_name',
        'institution',
        'exam_type',
        'result_date',
        'category',
        'posted_by',
        'is_published',
        'views_count'
    ];

    protected $casts = [
        'result_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getFormattedDateAttribute()
    {
        if ($this->result_date) {
            return $this->result_date->format('d M, Y');
        }
        return $this->created_at->format('d M, Y');
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($result) {
            $result->slug = Str::slug($result->title);
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
