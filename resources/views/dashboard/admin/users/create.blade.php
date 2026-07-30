@extends('layouts.admin')

@section('title', 'افزودن کاربر جدید')
@section('page-title', 'افزودن کاربر جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">افزودن کاربر جدید</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.admin.users.store') }}" method="POST" id="userCreateForm">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">نام</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="lastname" class="form-label">نام خانوادگی</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">ایمیل</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="phone" class="form-label">شماره تماس</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">تایید رمز عبور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telegram_number" class="form-label">شماره تلگرام (اختیاری)</label>
                    <input type="text" class="form-control @error('telegram_number') is-invalid @enderror" id="telegram_number" name="telegram_number" value="{{ old('telegram_number') }}">
                    @error('telegram_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="province" class="form-label">استان (اختیاری)</label>
                    <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}">
                    @error('province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">شهر (اختیاری)</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            دسترسی مدیر سیستم
                        </label>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_support" name="is_support" value="1" {{ old('is_support') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_support">
                            دسترسی پشتیبان
                        </label>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_verified">
                            تایید شده
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.admin.users.index') }}" class="btn btn-secondary">انصراف</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">ذخیره</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    <button id="manualSubmitButton" class="btn btn-success d-none mx-2">تلاش مجدد برای ذخیره (روش 1)</button>
    <button id="fetchSubmitButton" class="btn btn-warning d-none mx-2">تلاش مجدد برای ذخیره (روش 2)</button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('userCreateForm');
        const manualButton = document.getElementById('manualSubmitButton');
        const fetchButton = document.getElementById('fetchSubmitButton');
        
        // Only log, don't prevent form submission
        form.addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            
            // Log all form fields for debugging
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
        });

        // Handle manual submission if the normal submit doesn't work
        manualButton.addEventListener('click', function() {
            console.log('Manual submission attempt (method 1)');
            // Create a temporary submit button with name/type attributes and directly click it
            const tempButton = document.createElement('button');
            tempButton.type = 'submit';
            tempButton.style.display = 'none';
            form.appendChild(tempButton);
            tempButton.click();
            form.removeChild(tempButton);
        });
        
        // Alternative fetch method 
        fetchButton.addEventListener('click', function() {
            console.log('Fetch submission attempt (method 2)');
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
                redirect: 'follow'
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .then(html => {
                if (html) {
                    // If we received HTML content, it's probably an error page - reload to show it
                    document.open();
                    document.write(html);
                    document.close();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('خطا در ارسال فرم: ' + error.message);
            });
        });
        
        // Show the buttons after 3 seconds
        setTimeout(function() {
            manualButton.classList.remove('d-none');
            fetchButton.classList.remove('d-none');
        }, 3000);
    });
</script>
@endpush
@endsection 