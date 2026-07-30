@extends('layouts.dashboard')

@section('title', 'دوره‌های آموزشی')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-1">دوره‌های آموزشی</h2>
            <p class="text-muted">آموزش‌های تخصصی و حرفه‌ای</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.courses.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="جستجوی دوره..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">همه دسته‌بندی‌ها</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" 
                                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->courses_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Source Filter -->
                        <div class="col-md-3">
                            <select name="source" class="form-select">
                                <option value="">همه منابع</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source }}" 
                                            {{ request('source') == $source ? 'selected' : '' }}>
                                        {{ $source }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>فیلتر
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.courses.index') }}" 
                   class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">
                    همه دوره‌ها
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('dashboard.courses.category', $category->slug) }}" 
                       class="btn btn-outline-primary">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card shadow-sm h-100 course-card">
                <!-- Course Image -->
                @if($course->image_url)
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    <img src="{{ $course->image_url }}" 
                         class="card-img-top w-100 h-100" 
                         style="object-fit: cover;"
                         alt="{{ $course->name }}">
                    <div class="position-absolute top-0 start-0 p-2">
                        @if($course->is_featured)
                            <span class="badge bg-warning">
                                <i class="fas fa-star"></i> ویژه
                            </span>
                        @endif
                    </div>
                </div>
                @else
                <div class="bg-gradient-primary text-white d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <i class="fas fa-graduation-cap fs-1"></i>
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
                           class="text-decoration-none text-dark stretched-link">
                            {{ Str::limit($course->name, 60) }}
                        </a>
                    </h6>

                    <!-- Short Description -->
                    @if($course->short_description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit(strip_tags($course->short_description), 100) }}
                    </p>
                    @endif

                    <!-- Meta Info -->
                    <div class="mt-auto">
                        <div class="d-flex flex-wrap gap-2 text-muted small mb-2">
                            @if($course->source)
                                <span><i class="fas fa-book"></i> {{ $course->source }}</span>
                            @endif
                            <span><i class="fas fa-video"></i> {{ $course->sessions_count }} جلسه</span>
                        </div>

                        @if($course->duration)
                        <div class="text-muted small">
                            <i class="fas fa-clock me-1"></i>{{ $course->duration }}
                        </div>
                        @endif

                        <!-- Stats -->
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>{{ number_format($course->enrollments_count) }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($course->views_count) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fs-1 mb-3 d-block"></i>
                <h5>دوره‌ای یافت نشد</h5>
                <p class="mb-0">با فیلترهای دیگر جستجو کنید یا بعداً مراجعه نمایید.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.course-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection

