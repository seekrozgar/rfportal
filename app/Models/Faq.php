<?php
// app/Models/Faq.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'slug',
        'category_id',
        'order',
        'is_active',
        'is_featured',
        'views_count',
        'helpful_count',
        'not_helpful_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'views_count' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
    ];

    protected $appends = [
        'status_badge',
        'short_answer',
        'helpful_percentage',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($faq) {
            if (empty($faq->slug)) {
                $faq->slug = Str::slug($faq->question);
            }
        });

        static::updating(function ($faq) {
            if ($faq->isDirty('question') && !$faq->isDirty('slug')) {
                $faq->slug = Str::slug($faq->question);
            }
        });
    }

    // ✅ Relationships
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ✅ Accessors
    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="status-badge status-inactive"><i class="fas fa-ban"></i> Inactive</span>';
        }
        if ($this->is_featured) {
            return '<span class="status-badge status-featured"><i class="fas fa-star"></i> Featured</span>';
        }
        return '<span class="status-badge status-active"><i class="fas fa-check-circle"></i> Active</span>';
    }

    public function getShortAnswerAttribute()
    {
        return Str::limit(strip_tags($this->answer), 150);
    }

    public function getHelpfulPercentageAttribute()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        if ($total === 0)
            return 0;
        return round(($this->helpful_count / $total) * 100);
    }

    // ✅ Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('question', 'LIKE', "%{$term}%")
                ->orWhere('answer', 'LIKE', "%{$term}%");
        });
    }
}
