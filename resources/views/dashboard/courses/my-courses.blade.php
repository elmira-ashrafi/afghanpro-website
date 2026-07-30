@extends('layouts.dashboard')

@section('title', 'دوره‌های من')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">دوره‌های من</h2>
                    <p class="text-muted mb-0">دوره‌هایی که در آن‌ها ثبت‌نام کرده‌اید</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.courses.index') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>
                        مشاهده دوره‌های جدید
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row g-4">
        @forelse($enrollments as $enrollment)
        @php
            $course = $enrollment->course;
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <!-- Course Image -->
                @if($course->image_url)
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    <img src="{{ $course->image_url }}" 
                         class="card-img-top w-100 h-100" 
                         style="object-fit: cover;"
                         alt="{{ $course->name }}">
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 p-2">
                        @if($enrollment->status === 'completed')
                            <span class="badge bg-success">
                                <i class="ri-check-line"></i> تکمیل شده
                            </span>
                        @elseif($enrollment->status === 'active')
                            <span class="badge bg-primary">
                                <i class="ri-play-line"></i> در حال یادگیری
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                لغو شده
                            </span>
                        @endif
                    </div>
                </div>
                @else
                <div class="bg-gradient-primary text-white d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <i class="ri-graduation-cap-line fs-1"></i>
                </div>
                @endif

                <!-- Course Body -->
                <div class="card-body d-flex flex-column">
                    <!-- Categories -->
                    @if($course->categories->isNotEmpty())
                    <div class="mb-2">
                        @foreach($course->categories->take(2) as $category)
                            <span class="badge bg-primary-subtle text-primary">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Title -->
                    <h6 class="card-title fw-bold mb-2">
                        <a href="{{ route('dashboard.courses.show', $course->slug) }}" 
                           class="text-decoration-none text-dark">
                            {{ $course->name }}
                        </a>
                    </h6>

                    <!-- Progress Bar -->
                    @if($enrollment->status === 'active' || $enrollment->status === 'completed')
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">پیشرفت شما</small>
                            <small class="fw-bold">{{ $enrollment->progress_percentage }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar {{ $enrollment->status === 'completed' ? 'bg-success' : 'bg-primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ $enrollment->progress_percentage }}%">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Meta Info -->
                    <div class="mt-auto">
                        <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                            <span><i class="ri-video-line"></i> {{ $course->sessions_count }} جلسه</span>
                            @if($course->duration)
                                <span><i class="ri-time-line"></i> {{ $course->duration }}</span>
                            @endif
                        </div>

                        <!-- Enrollment Date -->
                        <div class="text-muted small mb-3">
                            <i class="ri-calendar-line me-1"></i>
                            ثبت‌نام: {{ $enrollment->created_at->format('Y/m/d') }}
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($enrollment->status === 'completed')
                                <a href="{{ route('dashboard.courses.show', $course->slug) }}" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="ri-eye-line me-1"></i>
                                    مشاهده دوره
                                </a>
                            @elseif($enrollment->status === 'active')
                                @php
                                    $firstVideo = $course->videos()->orderBy('order')->first();
                                @endphp
                                @if($firstVideo)
                                    <a href="{{ route('dashboard.courses.watch', [$course->slug, $firstVideo->id]) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="ri-play-circle-line me-1"></i>
                                        ادامه یادگیری
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.courses.show', $course->slug) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="ri-eye-line me-1"></i>
                                        مشاهده دوره
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ri-graduation-cap-line fs-1 text-muted mb-3 d-block"></i>
                    <h5>هنوز در هیچ دوره‌ای ثبت‌نام نکرده‌اید</h5>
                    <p class="text-muted mb-4">برای شروع یادگیری، در دوره‌های آموزشی ثبت‌نام کنید</p>
                    <a href="{{ route('dashboard.courses.index') }}" class="btn btn-primary">
                        <i class="ri-search-line me-2"></i>
                        مشاهده دوره‌های آموزشی
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($enrollments->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Stats -->
    @if($enrollments->isNotEmpty())
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-3">آمار یادگیری شما</h4>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">کل دوره‌ها</h6>
                            <h3 class="mb-0">{{ $enrollments->total() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-book-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">تکمیل شده</h6>
                            <h3 class="mb-0">{{ $enrollments->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-check-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">در حال یادگیری</h6>
                            <h3 class="mb-0">{{ $enrollments->where('status', 'active')->count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-play-circle-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">میانگین پیشرفت</h6>
                            <h3 class="mb-0">{{ round($enrollments->avg('progress_percentage')) }}%</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-bar-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
</style>
@endsection

