<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display list of courses
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Course::with(['categories'])->active();

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by source
        if ($request->has('source')) {
            $query->where('source', $request->input('source'));
        }

        // Order by
        $orderBy = $request->input('order_by', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($orderBy, $order);

        $courses = $query->paginate(12);
        $categories = CourseCategory::active()->parents()->withCount('courses')->orderBy('name')->get();
        $sources = Course::active()->distinct()->pluck('source')->filter()->values();

        return view('dashboard.courses.index', compact('user', 'courses', 'categories', 'sources'));
    }

    /**
     * Display single course details
     */
    public function show($slug)
    {
        $user = Auth::user();
        
        $course = Course::with([
            'categories',
            'videos' => function ($query) {
                $query->orderBy('order');
            },
            'sections.videos'
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Increment views
        $course->incrementViews();

        // Check if user is enrolled
        $isEnrolled = $user ? $course->isEnrolledBy($user) : false;

        // Get enrollment progress if enrolled
        $enrollment = null;
        if ($isEnrolled) {
            $enrollment = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
        }

        // Get related courses (same categories)
        $relatedCourses = Course::active()
            ->where('id', '!=', $course->id)
            ->whereHas('categories', function ($q) use ($course) {
                $q->whereIn('course_categories.id', $course->categories->pluck('id'));
            })
            ->limit(4)
            ->get();

        return view('dashboard.courses.show', compact('user', 'course', 'isEnrolled', 'enrollment', 'relatedCourses'));
    }

    /**
     * Enroll in a course
     */
    public function enroll($slug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $slug)->where('is_active', true)->firstOrFail();

        try {
            // Check if already enrolled
            $existingEnrollment = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existingEnrollment) {
                if ($existingEnrollment->status === 'cancelled') {
                    // Reactivate
                    $existingEnrollment->update(['status' => 'active']);
                    return redirect()->route('dashboard.courses.show', $course->slug)
                        ->with('success', 'ثبت‌نام شما مجدداً فعال شد.');
                }

                return redirect()->route('dashboard.courses.show', $course->slug)
                    ->with('info', 'شما قبلاً در این دوره ثبت‌نام کرده‌اید.');
            }

            // Create enrollment
            DB::beginTransaction();

            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'active',
                'progress_percentage' => 0,
            ]);

            $course->increment('enrollments_count');

            DB::commit();

            return redirect()->route('dashboard.courses.show', $course->slug)
                ->with('success', 'شما با موفقیت در دوره ثبت‌نام شدید.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در ثبت‌نام: ' . $e->getMessage());
        }
    }

    /**
     * Display course video player
     */
    public function watch($slug, $videoId)
    {
        $user = Auth::user();
        
        $course = Course::with(['videos', 'sections.videos'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $video = CourseVideo::where('course_id', $course->id)
            ->where('id', $videoId)
            ->firstOrFail();

        // Check access
        $isEnrolled = $user ? $course->isEnrolledBy($user) : false;
        
        if (!$video->is_free && !$isEnrolled) {
            return redirect()->route('dashboard.courses.show', $course->slug)
                ->with('error', 'برای دسترسی به این ویدیو باید در دوره ثبت‌نام کنید.');
        }

        // Increment video views
        $video->incrementViews();

        // Get enrollment for progress tracking
        $enrollment = null;
        if ($isEnrolled) {
            $enrollment = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
        }

        // Get next and previous videos
        $nextVideo = CourseVideo::where('course_id', $course->id)
            ->where('order', '>', $video->order)
            ->orderBy('order', 'asc')
            ->first();

        $previousVideo = CourseVideo::where('course_id', $course->id)
            ->where('order', '<', $video->order)
            ->orderBy('order', 'desc')
            ->first();

        return view('dashboard.courses.watch', compact('user', 'course', 'video', 'enrollment', 'nextVideo', 'previousVideo', 'isEnrolled'));
    }

    /**
     * My courses (enrolled courses)
     */
    public function myCourses()
    {
        $user = Auth::user();
        
        $enrollments = CourseEnrollment::with('course.categories')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('dashboard.courses.my-courses', compact('user', 'enrollments'));
    }

    /**
     * Browse by category
     */
    public function category($slug)
    {
        $user = Auth::user();
        
        $category = CourseCategory::with(['children'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Course::active()->with(['categories']);

        // Get courses from this category and its children
        $categoryIds = [$category->id];
        if ($category->children->isNotEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('course_categories.id', $categoryIds);
        });

        $courses = $query->paginate(12);
        $allCategories = CourseCategory::active()->parents()->orderBy('name')->get();

        return view('dashboard.courses.category', compact('user', 'category', 'courses', 'allCategories'));
    }
}
