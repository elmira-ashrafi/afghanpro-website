@extends('layouts.admin')

@section('title', 'تنظیمات سیستم')

@section('content')
<div class="main-header d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-1">تنظیمات سیستم</h5>
        <p class="text-muted mb-0">مدیریت پارامترها و تنظیمات سیستم</p>
    </div>
    <div>
        <a href="{{ route('dashboard.admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="ri-dashboard-line me-1"></i>بازگشت به داشبورد
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="{{ route('dashboard.admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Fee Settings -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">تنظیمات کارمزد حواله</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="domestic_transfer_fee" class="col-md-5 col-form-label">کارمزد ارسال حواله داخل افغانستان (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="domestic_transfer_fee" name="domestic_transfer_fee" value="{{ $settings['domestic_transfer_fee'] ?? 2 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای ارسال حواله به مقصد افغانستان.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="neighboring_transfer_fee" class="col-md-5 col-form-label">کارمزد ارسال حواله به کشورهای همسایه (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="neighboring_transfer_fee" name="neighboring_transfer_fee" value="{{ $settings['neighboring_transfer_fee'] ?? 3 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای ارسال حواله به کشورهای همسایه (ایران، پاکستان، تاجیکستان، ترکیه).</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="international_transfer_fee" class="col-md-5 col-form-label">کارمزد ارسال حواله به سایر کشورها (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="international_transfer_fee" name="international_transfer_fee" value="{{ $settings['international_transfer_fee'] ?? 5 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای ارسال حواله به سایر کشورها.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="trade_withdrawal_fee" class="col-md-5 col-form-label">کارمزد نقد کردن پول ترید (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="trade_withdrawal_fee" name="trade_withdrawal_fee" value="{{ $settings['trade_withdrawal_fee'] ?? 5 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای نقد کردن پول ترید.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="dollar_to_afghani_fee" class="col-md-5 col-form-label">کارمزد تبدیل دلار به افغانی (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="dollar_to_afghani_fee" name="dollar_to_afghani_fee" value="{{ $settings['dollar_to_afghani_fee'] ?? 0.5 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای تبدیل دلار به افغانی.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="afghani_to_dollar_fee" class="col-md-5 col-form-label">کارمزد تبدیل افغانی به دلار (%)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="afghani_to_dollar_fee" name="afghani_to_dollar_fee" value="{{ $settings['afghani_to_dollar_fee'] ?? 1 }}">
                                <span class="input-group-text">درصد</span>
                            </div>
                            <div class="form-text">درصد کارمزد برای تبدیل افغانی به دلار.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Exchange Rates -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">نرخ‌های تبدیل ارز</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="usd_to_afn_rate" class="col-md-5 col-form-label">نرخ تبدیل دلار به افغانی</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="usd_to_afn_rate" name="usd_to_afn_rate" value="{{ $settings['usd_to_afn_rate'] ?? 83.5 }}">
                                <span class="input-group-text">افغانی</span>
                            </div>
                            <div class="form-text">هر یک دلار آمریکا معادل چند افغانی است.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="afn_to_usd_rate" class="col-md-5 col-form-label">نرخ تبدیل افغانی به دلار</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="0.000001" min="0" class="form-control" id="afn_to_usd_rate" name="afn_to_usd_rate" value="{{ $settings['afn_to_usd_rate'] ?? 0.012 }}">
                                <span class="input-group-text">دلار</span>
                            </div>
                            <div class="form-text">هر یک افغانی معادل چند دلار آمریکا است.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Amount Limits -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">محدودیت‌های مبلغ</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="min_transfer_amount" class="col-md-5 col-form-label">حداقل مبلغ حواله (دلار)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="1" min="0" class="form-control" id="min_transfer_amount" name="min_transfer_amount" value="{{ $settings['min_transfer_amount'] ?? 10 }}">
                                <span class="input-group-text">دلار</span>
                            </div>
                            <div class="form-text">حداقل مبلغ قابل ارسال برای حواله.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="max_transfer_amount" class="col-md-5 col-form-label">حداکثر مبلغ حواله (دلار)</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="number" step="1" min="0" class="form-control" id="max_transfer_amount" name="max_transfer_amount" value="{{ $settings['max_transfer_amount'] ?? 10000 }}">
                                <span class="input-group-text">دلار</span>
                            </div>
                            <div class="form-text">حداکثر مبلغ قابل ارسال برای حواله.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>ذخیره تنظیمات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection