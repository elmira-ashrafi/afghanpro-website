<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'password',
        'telegram_number',
        'city',
        'province',
        'is_verified',
        'is_admin',
        'is_support',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_admin' => 'boolean',
        'is_support' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if user is support
     */
    public function isSupport()
    {
        return $this->is_support;
    }

    /**
     * Get user's wallet
     */
    public function afghanWallet()
    {
        return $this->hasOne(AfghanWallet::class);
    }

    /**
     * Get user's dollar wallet
     */
    public function dollarWallet()
    {
        return $this->hasOne(DollarWallet::class);
    }

    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get user's transfer requests
     */
    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class);
    }

    /**
     * Get user's money requests
     */
    public function moneyRequests()
    {
        return $this->hasMany(MoneyRequest::class, 'sender_id');
    }

    /**
     * Get money requests received by the user
     */
    public function receivedMoneyRequests()
    {
        return $this->hasMany(MoneyRequest::class, 'recipient_id');
    }

    /**
     * Get user's agency withdrawals
     */
    public function agencyWithdrawals()
    {
        return $this->hasMany(AgencyWithdrawal::class);
    }

    /**
     * Get user's course enrollments
     */
    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get user's enrolled courses
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
            ->withPivot('status', 'progress_percentage', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolledIn($courseId)
    {
        return $this->courseEnrollments()
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->exists();
    }
}
