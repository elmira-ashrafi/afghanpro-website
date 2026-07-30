@extends('layouts.admin')

@section('title', 'مدیریت دوره‌ها')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">مدیریت دوره‌های آموزشی</h3>
                    <p class="text-muted mb-0">مشاهده و مدیریت دوره‌های آموزشی</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.admin.courses.import') }}" class="btn btn-success">
                        <i class="ri-file-upload-line me-2"></i>
                        وارد کردن از CSV
                    </a>
                    <a href="{{ route('dashboard.admin.courses.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>
                        دوره جدید
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-circle-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.admin.courses.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
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
                            <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-filter-line me-2"></i>فیلتر
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="80"></th>
                            <th>نام دوره</th>
                            <th width="150">دسته‌بندی</th>
                            <th width="100" class="text-center">جلسات</th>
                            <th width="100" class="text-center">ثبت‌نام</th>
                            <th width="100" class="text-center">بازدید</th>
                            <th width="100" class="text-center">وضعیت</th>
                            <th width="150" class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>
                                @if($course->image_url)
                                    <img src="{{ $course->image_url }}" 
                                         alt="{{ $course->name }}"
                                         class="rounded"
                                         style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 40px;">
                                        <i class="ri-graduation-cap-line text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ Str::limit($course->name, 50) }}</div>
                                @if($course->source)
                                    <small class="text-muted">
                                        <i class="ri-book-line"></i> {{ $course->source }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($course->categories->isNotEmpty())
                                    <span class="badge bg-primary">
                                        {{ $course->categories->first()->name }}
                                    </span>
                                    @if($course->categories->count() > 1)
                                        <span class="badge bg-secondary">
                                            +{{ $course->categories->count() - 1 }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $course->sessions_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ number_format($course->enrollments_count) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ number_format($course->views_count) }}</span>
                            </td>
                            <td class="text-center">
                                @if($course->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                                @if($course->is_featured)
                                    <span class="badge bg-warning">ویژه</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dashboard.courses.show', $course->slug) }}" 
                                       class="btn btn-outline-primary"
                                       title="مشاهده"
                                       target="_blank">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('dashboard.admin.courses.edit', $course->id) }}" 
                                       class="btn btn-outline-warning"
                                       title="ویرایش">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('dashboard.admin.courses.destroy', $course->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('آیا از حذف این دوره اطمینان دارید؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger"
                                                title="حذف">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted d-block mb-3"></i>
                                <h5 class="text-muted">دوره‌ای یافت نشد</h5>
                                <p class="text-muted">دوره جدید اضافه کنید یا از CSV وارد کنید</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($courses->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">کل دوره‌ها</h6>
                            <h3 class="mb-0">{{ \App\Models\Course::count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-graduation-cap-line"></i>
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
                            <h6 class="text-white-50 mb-1">دوره‌های فعال</h6>
                            <h3 class="mb-0">{{ \App\Models\Course::where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-check-line"></i>
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
                            <h6 class="text-white-50 mb-1">کل ثبت‌نام‌ها</h6>
                            <h3 class="mb-0">{{ \App\Models\CourseEnrollment::count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-user-line"></i>
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
                            <h6 class="text-white-50 mb-1">کل ویدیوها</h6>
                            <h3 class="mb-0">{{ \App\Models\CourseVideo::count() }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="ri-video-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>
@endsection

