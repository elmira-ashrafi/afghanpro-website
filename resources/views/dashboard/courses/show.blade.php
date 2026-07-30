@extends('layouts.dashboard')

@section('title', $course->name)

@section('content')
<div class="container-fluid">
    <!-- Course Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.courses.index') }}">دوره‌ها</a></li>
                    @if($course->categories->isNotEmpty())
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.courses.category', $course->categories->first()->slug) }}">
                                {{ $course->categories->first()->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ $course->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Course Image -->
            @if($course->image_url)
            <div class="card shadow-sm mb-4">
                <img src="{{ $course->image_url }}" 
                     class="card-img-top" 
                     alt="{{ $course->name }}"
                     style="max-height: 400px; object-fit: cover;">
            </div>
            @endif

            <!-- Course Title -->
            <div class="mb-4">
                <h2 class="fw-bold">{{ $course->name }}</h2>
                <div class="d-flex flex-wrap gap-2 align-items-center text-muted mt-2">
                    @if($course->source)
                        <span><i class="ri-book-line me-1"></i>{{ $course->source }}</span>
                    @endif
                    @if($course->duration)
                        <span><i class="ri-time-line me-1"></i>{{ $course->duration }}</span>
                    @endif
                    <span><i class="ri-video-line me-1"></i>{{ $course->videos->count() }} جلسه</span>
                    <span><i class="ri-eye-line me-1"></i>{{ number_format($course->views_count) }} بازدید</span>
                    <span><i class="ri-group-line me-1"></i>{{ number_format($course->enrollments_count) }} شرکت‌کننده</span>
                </div>

                <!-- Categories -->
                @if($course->categories->isNotEmpty())
                <div class="mt-3">
                    @foreach($course->categories as $category)
                        <a href="{{ route('dashboard.courses.category', $category->slug) }}" 
                           class="badge bg-primary text-decoration-none">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Short Description -->
            @if($course->short_description)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-information-line text-primary me-2"></i>توضیحات</h5>
                </div>
                <div class="card-body">
                    <div class="course-content">
                        {!! $course->short_description !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- What You Will Learn -->
            @if($course->what_you_learn)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-check-double-line text-success me-2"></i>آنچه در این دوره می‌آموزید</h5>
                </div>
                <div class="card-body">
                    <div class="course-content">
                        {!! $course->what_you_learn !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Prerequisites -->
            @if($course->prerequisites)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-list-check text-warning me-2"></i>پیش‌نیازهای دوره</h5>
                </div>
                <div class="card-body">
                    <div class="course-content">
                        {!! $course->prerequisites !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Who This For -->
            @if($course->who_this_for)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-user-star-line text-info me-2"></i>این دوره برای چه کسانی مناسب است</h5>
                </div>
                <div class="card-body">
                    <div class="course-content">
                        {!! $course->who_this_for !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Full Description -->
            @if($course->description)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-file-text-line text-secondary me-2"></i>توضیحات بیشتر</h5>
                </div>
                <div class="card-body">
                    <div class="course-content">
                        {!! $course->description !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Course Info Table -->
            @if($course->info)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-information-fill text-primary me-2"></i>اطلاعات دوره</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach($course->info as $key => $value)
                            <tr>
                                <td class="fw-bold" width="40%">{{ $key }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Course Videos/Content -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="ri-play-list-2-line text-primary me-2"></i>محتوای دوره</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($course->videos as $video)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($video->type === 'video')
                                    <i class="ri-play-circle-line text-primary fs-5 me-3"></i>
                                @else
                                    <i class="ri-file-text-line text-secondary fs-5 me-3"></i>
                                @endif
                                <div>
                                    <div class="fw-medium">{{ $video->title }}</div>
                                    @if($video->duration)
                                        <small class="text-muted">{{ $video->duration }}</small>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($video->is_free)
                                    <span class="badge bg-success">رایگان</span>
                                @endif
                                @if($video->hasSubtitles())
                                    <span class="badge bg-info">زیرنویس</span>
                                @endif
                                @if($isEnrolled || $video->is_free)
                                    <a href="{{ route('dashboard.courses.watch', [$course->slug, $video->id]) }}" 
                                       class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-play me-1"></i>مشاهده
                                    </a>
                                @else
                                    <i class="fas fa-lock text-muted"></i>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            محتوایی برای این دوره ثبت نشده است.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Related Courses -->
            @if($relatedCourses->isNotEmpty())
            <div class="mb-4">
                <h4 class="mb-3"><i class="fas fa-graduation-cap text-primary me-2"></i>دوره‌های مرتبط</h4>
                <div class="row g-3">
                    @foreach($relatedCourses as $related)
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            @if($related->image_url)
                            <img src="{{ $related->image_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $related->name }}"
                                 style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ route('dashboard.courses.show', $related->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($related->name, 60) }}
                                    </a>
                                </h6>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-video me-1"></i>{{ $related->sessions_count }} جلسه
                                    @if($related->duration)
                                        • {{ $related->duration }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <!-- Enrollment Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        @if($isEnrolled)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fs-1 mb-2"></i>
                                <p class="mb-0 fw-bold">شما در این دوره ثبت‌نام کرده‌اید</p>
                            </div>

                            @if($enrollment && $enrollment->progress_percentage > 0)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>پیشرفت دوره</span>
                                    <span class="fw-bold">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $enrollment->progress_percentage }}%">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <a href="{{ route('dashboard.courses.my-courses') }}" 
                               class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-graduation-cap me-2"></i>
                                مشاهده دوره‌های من
                            </a>
                        @else
                            <h3 class="text-primary mb-3">رایگان</h3>
                            <p class="text-muted">برای دسترسی به تمام محتوای دوره ثبت‌نام کنید</p>

                            <form action="{{ route('dashboard.courses.enroll', $course->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    ثبت‌نام در دوره
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>آمار دوره</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-users text-muted me-2"></i>شرکت‌کنندگان</span>
                            <span class="fw-bold">{{ number_format($course->enrollments_count) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-eye text-muted me-2"></i>بازدیدها</span>
                            <span class="fw-bold">{{ number_format($course->views_count) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-video text-muted me-2"></i>تعداد جلسات</span>
                            <span class="fw-bold">{{ $course->sessions_count }}</span>
                        </div>
                        @if($course->duration)
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-clock text-muted me-2"></i>مدت زمان</span>
                            <span class="fw-bold">{{ $course->duration }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.course-content ul {
    list-style: none;
    padding-right: 0;
}
.course-content ul li {
    padding: 8px 0;
    padding-right: 25px;
    position: relative;
}
.course-content ul li:before {
    content: "✓";
    position: absolute;
    right: 0;
    color: #28a745;
    font-weight: bold;
}
.course-content p {
    line-height: 2;
    text-align: justify;
}
</style>
@endsection

