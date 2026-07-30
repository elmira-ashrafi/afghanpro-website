@extends('layouts.admin')

@section('title', 'وارد کردن دوره‌ها از CSV')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">وارد کردن دوره‌ها</h3>
                    <p class="text-muted mb-0">وارد کردن دوره‌های آموزشی از فایل CSV</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.admin.courses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        بازگشت به لیست دوره‌ها
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>خطاهای وارد کردن:</h6>
                    <ul class="mb-0">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>خطاهای اعتبارسنجی:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Import Instructions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>راهنمای وارد کردن</h5>
                </div>
                <div class="card-body">
                    <h6>فرمت فایل CSV:</h6>
                    <p>فایل CSV شما باید شامل ستون‌های زیر باشد:</p>
                    <ul>
                        <li><strong>name:</strong> نام دوره (الزامی)</li>
                        <li><strong>image:</strong> URL تصویر کاور دوره</li>
                        <li><strong>time:</strong> مدت زمان دوره (مثل: "1:19:25")</li>
                        <li><strong>tags:</strong> تگ‌های دوره برای دسته‌بندی (جدا شده با کاما)</li>
                        <li><strong>info:</strong> اطلاعات دوره به صورت JSON (زبان، جلسات، تاریخ انتشار)</li>
                        <li><strong>source:</strong> منبع دوره (مثل: Udemy)</li>
                        <li><strong>short description:</strong> توضیحات کوتاه (HTML)</li>
                        <li><strong>what you will learn:</strong> آنچه یاد خواهید گرفت (HTML)</li>
                        <li><strong>who this course is for:</strong> این دوره برای چه کسانی است (HTML)</li>
                        <li><strong>course prerequisites:</strong> پیش‌نیازهای دوره (HTML)</li>
                        <li><strong>description:</strong> توضیحات کامل (HTML)</li>
                        <li><strong>video titles:</strong> عناوین ویدیوها (جدا شده با کاما)</li>
                        <li><strong>video links:</strong> لینک‌های ویدیوها (جدا شده با کاما)</li>
                        <li><strong>subtitles:</strong> لینک‌های زیرنویس (جدا شده با کاما، اختیاری)</li>
                    </ul>

                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>نکته:</strong> فیلدهایی که خالی یا "None" هستند نادیده گرفته می‌شوند.
                    </div>

                    <a href="{{ route('dashboard.admin.courses.import.sample') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>
                        دانلود فایل نمونه CSV
                    </a>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>آپلود فایل CSV</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.admin.courses.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="csv_file" class="form-label">انتخاب فایل CSV</label>
                            <input type="file" 
                                   class="form-control @if(session()->has('errors') && session('errors')->has('csv_file')) is-invalid @endif" 
                                   id="csv_file" 
                                   name="csv_file" 
                                   accept=".csv"
                                   required>
                            @if(session()->has('errors') && session('errors')->has('csv_file'))
                                <div class="invalid-feedback">{{ session('errors')->first('csv_file') }}</div>
                            @endif
                            <div class="form-text">
                                حداکثر حجم فایل: 10 مگابایت. فرمت مجاز: CSV
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-file-import me-2"></i>
                                وارد کردن دوره‌ها
                            </button>

                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                زمان پردازش بستگی به تعداد دوره‌ها دارد
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics (if available) -->
            @php
                $totalCourses = \App\Models\Course::count();
                $totalCategories = \App\Models\CourseCategory::count();
                $totalVideos = \App\Models\CourseVideo::count();
            @endphp

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">تعداد دوره‌ها</h6>
                                    <h3 class="mb-0">{{ number_format($totalCourses) }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">دسته‌بندی‌ها</h6>
                                    <h3 class="mb-0">{{ number_format($totalCategories) }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fas fa-folder"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-gradient-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">ویدیوها</h6>
                                    <h3 class="mb-0">{{ number_format($totalVideos) }}</h3>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fas fa-video"></i>
                                </div>
                            </div>
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
</style>
@endsection

