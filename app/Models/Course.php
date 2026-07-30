<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'duration',
        'source',
        'short_description',
        'description',
        'what_you_learn',
        'who_this_for',
        'prerequisites',
        'info',
        'sessions_count',
        'language',
        'published_at',
        'is_active',
        'is_featured',
        'views_count',
        'enrollments_count',
    ];

    protected $casts = [
        'info' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'date',
        'views_count' => 'integer',
        'enrollments_count' => 'integer',
        'sessions_count' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Get all categories for this course
     */
    public function categories()
    {
        return $this->belongsToMany(CourseCategory::class, 'course_course_category');
    }

    /**
     * Get all videos for this course
     */
    public function videos()
    {
        return $this->hasMany(CourseVideo::class)->orderBy('order');
    }

    /**
     * Get all sections for this course
     */
    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    /**
     * Get all enrollments for this course
     */
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Check if user is enrolled
     */
    public function isEnrolledBy($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Scope for active courses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured courses
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'نامشخص';
        }
        
        return $this->duration;
    }
}
