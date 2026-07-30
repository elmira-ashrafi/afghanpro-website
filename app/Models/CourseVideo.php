<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'video_url',
        'subtitle_url',
        'type',
        'duration',
        'order',
        'is_free',
        'views_count',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'views_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the course this video belongs to
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the section this video belongs to
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * Check if this is a video (not a document)
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Check if this has subtitles
     */
    public function hasSubtitles()
    {
        return !empty($this->subtitle_url);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute()
    {
        return $this->type === 'video' ? 'play-circle' : 'file-text';
    }
}
