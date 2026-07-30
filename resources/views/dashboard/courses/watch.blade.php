@extends('layouts.dashboard')

@section('title', $video->title . ' - ' . $course->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Video Player Section -->
        <div class="col-lg-9">
            <div class="bg-black" style="min-height: calc(100vh - 60px);">
                <!-- Video Player -->
                <div class="position-relative">
                    @if($video->type === 'video')
                        <div id="videoContainer" class="w-100" style="background: #000;">
                            <iframe 
                                id="videoPlayer"
                                src="{{ $video->video_url }}" 
                                frameborder="0" 
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                style="width: 100%; height: 70vh; min-height: 500px;">
                            </iframe>

                            @if($video->hasSubtitles())
                            <div class="position-absolute bottom-0 end-0 p-3">
                                <button class="btn btn-sm btn-light" id="subtitleToggle" title="تنظیمات زیرنویس">
                                    <i class="fas fa-closed-captioning"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    @else
                        <!-- Document View -->
                        <div class="text-center p-5 bg-dark text-white">
                            <i class="fas fa-file-alt fs-1 mb-3"></i>
                            <h4>{{ $video->title }}</h4>
                            <p class="text-muted">این یک فایل اسنادی است</p>
                            <a href="{{ $video->video_url }}" 
                               target="_blank" 
                               class="btn btn-primary btn-lg mt-3">
                                <i class="fas fa-download me-2"></i>
                                دانلود فایل
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Video Info -->
                <div class="p-4 bg-dark text-white">
                    <h3 class="mb-3">{{ $video->title }}</h3>
                    
                    <div class="d-flex flex-wrap gap-3 align-items-center text-white-50 mb-3">
                        <span><i class="fas fa-graduation-cap me-2"></i>{{ $course->name }}</span>
                        @if($video->duration)
                            <span><i class="fas fa-clock me-2"></i>{{ $video->duration }}</span>
                        @endif
                        <span><i class="fas fa-eye me-2"></i>{{ number_format($video->views_count) }} بازدید</span>
                        @if($video->hasSubtitles())
                            <span class="badge bg-info"><i class="fas fa-closed-captioning me-1"></i>زیرنویس فارسی</span>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        @if($previousVideo)
                            <a href="{{ route('dashboard.courses.watch', [$course->slug, $previousVideo->id]) }}" 
                               class="btn btn-outline-light">
                                <i class="fas fa-chevron-right me-2"></i>
                                قبلی
                            </a>
                        @endif

                        <a href="{{ route('dashboard.courses.show', $course->slug) }}" 
                           class="btn btn-outline-light">
                            <i class="fas fa-list me-2"></i>
                            فهرست دوره
                        </a>

                        @if($nextVideo)
                            <a href="{{ route('dashboard.courses.watch', [$course->slug, $nextVideo->id]) }}" 
                               class="btn btn-primary">
                                بعدی
                                <i class="fas fa-chevron-left ms-2"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Progress (if enrolled) -->
                    @if($enrollment)
                    <div class="mt-4 pt-3 border-top border-secondary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>پیشرفت شما در این دوره</span>
                            <span class="fw-bold">{{ $enrollment->progress_percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $enrollment->progress_percentage }}%">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Playlist Sidebar -->
        <div class="col-lg-3 bg-light" style="max-height: calc(100vh - 60px); overflow-y: auto;">
            <div class="p-3 border-bottom bg-white sticky-top">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    فهرست دوره
                </h5>
                <small class="text-muted">{{ $course->videos->count() }} جلسه</small>
            </div>

            <div class="list-group list-group-flush">
                @foreach($course->videos as $index => $v)
                <a href="{{ route('dashboard.courses.watch', [$course->slug, $v->id]) }}" 
                   class="list-group-item list-group-item-action {{ $v->id == $video->id ? 'active' : '' }}"
                   style="{{ $v->id == $video->id ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);' : '' }}">
                    <div class="d-flex align-items-start">
                        <div class="me-3 mt-1">
                            @if($v->type === 'video')
                                <i class="fas fa-play-circle {{ $v->id == $video->id ? 'text-white' : 'text-primary' }}"></i>
                            @else
                                <i class="fas fa-file-alt {{ $v->id == $video->id ? 'text-white' : 'text-secondary' }}"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-medium">{{ $index + 1 }}. {{ Str::limit($v->title, 40) }}</span>
                                @if(!$isEnrolled && !$v->is_free)
                                    <i class="fas fa-lock text-muted"></i>
                                @endif
                            </div>
                            @if($v->duration)
                                <small class="{{ $v->id == $video->id ? 'text-white-50' : 'text-muted' }}">
                                    <i class="fas fa-clock me-1"></i>{{ $v->duration }}
                                </small>
                            @endif
                            @if($v->hasSubtitles())
                                <span class="badge bg-info badge-sm ms-2">
                                    <i class="fas fa-closed-captioning"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if($video->hasSubtitles())
<!-- Subtitle Modal -->
<div class="modal fade" id="subtitleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-closed-captioning me-2"></i>تنظیمات زیرنویس</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="enableSubtitle" checked>
                    <label class="form-check-label" for="enableSubtitle">
                        نمایش زیرنویس
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">اندازه فونت</label>
                    <select class="form-select" id="subtitleSize">
                        <option value="small">کوچک</option>
                        <option value="medium" selected>متوسط</option>
                        <option value="large">بزرگ</option>
                    </select>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    زیرنویس فارسی برای این ویدیو موجود است
                </div>

                <a href="{{ $video->subtitle_url }}" 
                   target="_blank" 
                   class="btn btn-outline-primary w-100">
                    <i class="fas fa-download me-2"></i>
                    دانلود فایل زیرنویس
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtitleToggle = document.getElementById('subtitleToggle');
    const subtitleModal = new bootstrap.Modal(document.getElementById('subtitleModal'));

    if (subtitleToggle) {
        subtitleToggle.addEventListener('click', function() {
            subtitleModal.show();
        });
    }

    // Save subtitle preferences to localStorage
    const enableSubtitle = document.getElementById('enableSubtitle');
    const subtitleSize = document.getElementById('subtitleSize');

    if (enableSubtitle) {
        const saved = localStorage.getItem('subtitleEnabled');
        if (saved !== null) {
            enableSubtitle.checked = saved === 'true';
        }

        enableSubtitle.addEventListener('change', function() {
            localStorage.setItem('subtitleEnabled', this.checked);
        });
    }

    if (subtitleSize) {
        const savedSize = localStorage.getItem('subtitleSize');
        if (savedSize) {
            subtitleSize.value = savedSize;
        }

        subtitleSize.addEventListener('change', function() {
            localStorage.setItem('subtitleSize', this.value);
        });
    }
});
</script>
@endif

<style>
.list-group-item.active {
    border-color: #667eea;
}
.list-group-item.active * {
    color: white !important;
}
</style>
@endsection

