@extends('layouts.dashboard')

@section('title', 'ورود محصولات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ورود محصولات از فایل CSV</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class=" alert-info">
                                <h5>راهنمای ورود اطلاعات محصولات:</h5>
                                <ul>
                                    <li>فایل CSV باید شامل سرستون‌های مشخص باشد (Name, Images, Description, ID, Parent, Type, Regular price, Categories, Attribute 1 name, Attribute 1 value(s), ...)</li>
                                    <li>برای محصولات متغیر، ابتدا یک ردیف برای محصول اصلی با Type=variable وارد کنید</li>
                                    <li>سپس برای هر متغیر، یک ردیف با Parent=ID محصول اصلی وارد کنید</li>
                                    <li>تصاویر باید به صورت URL کامل وارد شوند</li>
                                    <li>مقادیر ویژگی‌ها می‌توانند با کاراکتر | یا , از هم جدا شوند (سیستم به صورت خودکار هر دو فرمت را پشتیبانی می‌کند)</li>
                                    <li>نمونه: <code>نوع اشتراک و فعالسازی,پرداخت ارزی قانونی (قابل تمدید)</code> یا <code>نوع اشتراک و فعالسازی|پرداخت ارزی قانونی (قابل تمدید)</code></li>
                                </ul>
                                <p>نمونه فایل CSV را می‌توانید از <a href="{{ route('dashboard.shop.admin.import.sample') }}" class="text-primary">اینجا</a> دانلود کنید.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('dashboard.shop.admin.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv_file">انتخاب فایل CSV:</label>
                            <input type="file" name="csv_file" id="csv_file" class="form-control-file @error('csv_file') is-invalid @enderror" required>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="download_images" id="download_images" value="1" checked>
                                <label class="form-check-label" for="download_images">
                                    دانلود تصاویر از لینک‌های خارجی و ذخیره در سرور
                                </label>
                                <div class="form-text small">
                                    با فعال کردن این گزینه، تصاویر از لینک‌های خارجی دانلود و در سرور ذخیره می‌شوند. در صورت غیرفعال بودن، فقط لینک تصاویر ذخیره می‌شود.
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> بارگذاری و ورود اطلاعات
                            </button>
                            <a href="{{ route('dashboard.shop.admin.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i> بازگشت
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 