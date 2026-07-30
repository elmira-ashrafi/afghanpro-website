<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the course this section belongs to
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all videos in this section
     */
    public function videos()
    {
        return $this->hasMany(CourseVideo::class, 'section_id')->orderBy('order');
    }

    /**
     * Get videos count
     */
    public function getVideosCountAttribute()
    {
        return $this->videos()->count();
    }

    /**
     * Get total duration of all videos in this section
     */
    public function getTotalDurationAttribute()
    {
        // This would require parsing duration strings
        // For now, return count
        return $this->videos_count . ' ویدیو';
    }
}
