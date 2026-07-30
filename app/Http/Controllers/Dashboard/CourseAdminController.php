<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseVideo;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseAdminController extends Controller
{
    /**
     * Display a listing of courses
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Course::with(['categories', 'videos']);

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $categoryId = $request->input('category');
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('course_categories.id', $categoryId);
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        // Order by
        $orderBy = $request->input('order_by', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($orderBy, $order);

        $courses = $query->paginate(20);
        $categories = CourseCategory::active()->parents()->orderBy('name')->get();

        return view('dashboard.admin.courses.index', compact('user', 'courses', 'categories'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $user = auth()->user();
        $categories = CourseCategory::active()->parents()->with('children')->orderBy('name')->get();

        return view('dashboard.admin.courses.create', compact('user', 'categories'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'source' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'what_you_learn' => 'nullable|string',
            'who_this_for' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'language' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:course_categories,id',
        ]);

        DB::beginTransaction();

        try {
            $course = Course::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
                'image_url' => $validated['image_url'] ?? null,
                'source' => $validated['source'] ?? null,
                'duration' => $validated['duration'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'what_you_learn' => $validated['what_you_learn'] ?? null,
                'who_this_for' => $validated['who_this_for'] ?? null,
                'prerequisites' => $validated['prerequisites'] ?? null,
                'language' => $validated['language'] ?? 'fa',
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
            ]);

            // Attach categories
            if (!empty($validated['categories'])) {
                $course->categories()->attach($validated['categories']);
            }

            DB::commit();

            return redirect()->route('dashboard.admin.courses.edit', $course->id)
                ->with('success', 'دوره با موفقیت ایجاد شد.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در ایجاد دوره: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified course
     */
    public function show($id)
    {
        $user = auth()->user();
        $course = Course::with(['categories', 'videos', 'sections.videos', 'enrollments'])->findOrFail($id);

        return view('dashboard.admin.courses.show', compact('user', 'course'));
    }

    /**
     * Show the form for editing the specified course
     */
    public function edit($id)
    {
        $user = auth()->user();
        $course = Course::with(['categories', 'videos', 'sections'])->findOrFail($id);
        $categories = CourseCategory::active()->parents()->with('children')->orderBy('name')->get();

        return view('dashboard.admin.courses.edit', compact('user', 'course', 'categories'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'source' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'what_you_learn' => 'nullable|string',
            'who_this_for' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'language' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:course_categories,id',
        ]);

        DB::beginTransaction();

        try {
            $course->update([
                'name' => $validated['name'],
                'image_url' => $validated['image_url'] ?? null,
                'source' => $validated['source'] ?? null,
                'duration' => $validated['duration'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'what_you_learn' => $validated['what_you_learn'] ?? null,
                'who_this_for' => $validated['who_this_for'] ?? null,
                'prerequisites' => $validated['prerequisites'] ?? null,
                'language' => $validated['language'] ?? 'fa',
                'is_active' => $validated['is_active'] ?? true,
                'is_featured' => $validated['is_featured'] ?? false,
            ]);

            // Sync categories
            if (isset($validated['categories'])) {
                $course->categories()->sync($validated['categories']);
            }

            DB::commit();

            return back()->with('success', 'دوره با موفقیت به‌روزرسانی شد.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در به‌روزرسانی دوره: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified course
     */
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return redirect()->route('dashboard.admin.courses.index')
                ->with('success', 'دوره با موفقیت حذف شد.');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در حذف دوره: ' . $e->getMessage());
        }
    }

    /**
     * Add video to course
     */
    public function addVideo(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'subtitle_url' => 'nullable|url',
            'type' => 'required|in:video,document',
            'duration' => 'nullable|string|max:50',
            'section_id' => 'nullable|exists:course_sections,id',
            'is_free' => 'boolean',
        ]);

        try {
            $maxOrder = $course->videos()->max('order') ?? -1;

            CourseVideo::create([
                'course_id' => $course->id,
                'section_id' => $validated['section_id'] ?? null,
                'title' => $validated['title'],
                'video_url' => $validated['video_url'],
                'subtitle_url' => $validated['subtitle_url'] ?? null,
                'type' => $validated['type'],
                'duration' => $validated['duration'] ?? null,
                'order' => $maxOrder + 1,
                'is_free' => $validated['is_free'] ?? false,
            ]);

            $course->increment('sessions_count');

            return back()->with('success', 'ویدیو با موفقیت اضافه شد.');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در افزودن ویدیو: ' . $e->getMessage());
        }
    }

    /**
     * Delete video
     */
    public function deleteVideo($courseId, $videoId)
    {
        try {
            $video = CourseVideo::where('course_id', $courseId)->findOrFail($videoId);
            $video->delete();

            $course = Course::findOrFail($courseId);
            $course->decrement('sessions_count');

            return back()->with('success', 'ویدیو با موفقیت حذف شد.');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در حذف ویدیو: ' . $e->getMessage());
        }
    }
}
