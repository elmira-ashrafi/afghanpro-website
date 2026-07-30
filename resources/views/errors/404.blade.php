@extends('layouts.app')

@section('title', 'صفحه یافت نشد')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">صفحه مورد نظر یافت نشد</h2>
                <p class="lead mb-5">متأسفانه صفحه‌ای که به دنبال آن هستید وجود ندارد یا حذف شده است.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">بازگشت به صفحه اصلی</a>
            </div>
        </div>
    </div>
</div>
@endsection 