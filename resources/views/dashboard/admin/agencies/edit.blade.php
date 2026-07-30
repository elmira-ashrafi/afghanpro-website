@extends('layouts.admin')

@section('title', 'ویرایش نمایندگی')

@section('page-title', 'ویرایش نمایندگی')

@section('content')
<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">ویرایش نمایندگی: {{ $agency->name }}</h5>
                <a href="{{ route('dashboard.admin.agencies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ri-arrow-go-back-line me-1"></i>
                    بازگشت به لیست
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.admin.agencies.update', $agency->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">نام نمایندگی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $agency->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="contact_person" class="form-label">نام مسئول</label>
                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $agency->contact_person) }}">
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="province" class="form-label">استان <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $agency->province) }}" required>
                            @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="city" class="form-label">شهر <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $agency->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">آدرس کامل <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $agency->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">شماره تماس <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $agency->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">ایمیل</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $agency->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">عرض جغرافیایی (Latitude)</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $agency->latitude) }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">طول جغرافیایی (Longitude)</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $agency->longitude) }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $agency->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نمایندگی فعال است</label>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">ساعات کاری</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $workingHours = old('working_hours', $agency->working_hours ? (is_array($agency->working_hours) ? $agency->working_hours : json_decode($agency->working_hours, true)) : [
                                    'saturday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'sunday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'monday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'tuesday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'wednesday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'thursday' => ['open' => '', 'close' => '', 'closed' => false],
                                    'friday' => ['open' => '', 'close' => '', 'closed' => false],
                                ]);
                            @endphp
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>روز هفته</th>
                                            <th>ساعت شروع</th>
                                            <th>ساعت پایان</th>
                                            <th>تعطیل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>شنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[saturday][open]" value="{{ $workingHours['saturday']['open'] ?? '' }}" {{ isset($workingHours['saturday']['closed']) && $workingHours['saturday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[saturday][close]" value="{{ $workingHours['saturday']['close'] ?? '' }}" {{ isset($workingHours['saturday']['closed']) && $workingHours['saturday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[saturday][closed]" value="1" id="saturday-closed" {{ isset($workingHours['saturday']['closed']) && $workingHours['saturday']['closed'] ? 'checked' : '' }} data-day="saturday">
                                                    <label class="form-check-label" for="saturday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>یکشنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[sunday][open]" value="{{ $workingHours['sunday']['open'] ?? '' }}" {{ isset($workingHours['sunday']['closed']) && $workingHours['sunday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[sunday][close]" value="{{ $workingHours['sunday']['close'] ?? '' }}" {{ isset($workingHours['sunday']['closed']) && $workingHours['sunday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[sunday][closed]" value="1" id="sunday-closed" {{ isset($workingHours['sunday']['closed']) && $workingHours['sunday']['closed'] ? 'checked' : '' }} data-day="sunday">
                                                    <label class="form-check-label" for="sunday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>دوشنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[monday][open]" value="{{ $workingHours['monday']['open'] ?? '' }}" {{ isset($workingHours['monday']['closed']) && $workingHours['monday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[monday][close]" value="{{ $workingHours['monday']['close'] ?? '' }}" {{ isset($workingHours['monday']['closed']) && $workingHours['monday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[monday][closed]" value="1" id="monday-closed" {{ isset($workingHours['monday']['closed']) && $workingHours['monday']['closed'] ? 'checked' : '' }} data-day="monday">
                                                    <label class="form-check-label" for="monday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>سه‌شنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[tuesday][open]" value="{{ $workingHours['tuesday']['open'] ?? '' }}" {{ isset($workingHours['tuesday']['closed']) && $workingHours['tuesday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[tuesday][close]" value="{{ $workingHours['tuesday']['close'] ?? '' }}" {{ isset($workingHours['tuesday']['closed']) && $workingHours['tuesday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[tuesday][closed]" value="1" id="tuesday-closed" {{ isset($workingHours['tuesday']['closed']) && $workingHours['tuesday']['closed'] ? 'checked' : '' }} data-day="tuesday">
                                                    <label class="form-check-label" for="tuesday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>چهارشنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[wednesday][open]" value="{{ $workingHours['wednesday']['open'] ?? '' }}" {{ isset($workingHours['wednesday']['closed']) && $workingHours['wednesday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[wednesday][close]" value="{{ $workingHours['wednesday']['close'] ?? '' }}" {{ isset($workingHours['wednesday']['closed']) && $workingHours['wednesday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[wednesday][closed]" value="1" id="wednesday-closed" {{ isset($workingHours['wednesday']['closed']) && $workingHours['wednesday']['closed'] ? 'checked' : '' }} data-day="wednesday">
                                                    <label class="form-check-label" for="wednesday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>پنجشنبه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[thursday][open]" value="{{ $workingHours['thursday']['open'] ?? '' }}" {{ isset($workingHours['thursday']['closed']) && $workingHours['thursday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[thursday][close]" value="{{ $workingHours['thursday']['close'] ?? '' }}" {{ isset($workingHours['thursday']['closed']) && $workingHours['thursday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[thursday][closed]" value="1" id="thursday-closed" {{ isset($workingHours['thursday']['closed']) && $workingHours['thursday']['closed'] ? 'checked' : '' }} data-day="thursday">
                                                    <label class="form-check-label" for="thursday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>جمعه</td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[friday][open]" value="{{ $workingHours['friday']['open'] ?? '' }}" {{ isset($workingHours['friday']['closed']) && $workingHours['friday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" name="working_hours[friday][close]" value="{{ $workingHours['friday']['close'] ?? '' }}" {{ isset($workingHours['friday']['closed']) && $workingHours['friday']['closed'] ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input day-closed" type="checkbox" name="working_hours[friday][closed]" value="1" id="friday-closed" {{ isset($workingHours['friday']['closed']) && $workingHours['friday']['closed'] ? 'checked' : '' }} data-day="friday">
                                                    <label class="form-check-label" for="friday-closed"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        بروزرسانی نمایندگی
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle time inputs based on 'closed' checkbox
        document.querySelectorAll('.day-closed').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const day = this.dataset.day;
                const openInput = document.querySelector(`input[name="working_hours[${day}][open]"]`);
                const closeInput = document.querySelector(`input[name="working_hours[${day}][close]"]`);
                
                if (this.checked) {
                    openInput.disabled = true;
                    closeInput.disabled = true;
                } else {
                    openInput.disabled = false;
                    closeInput.disabled = false;
                }
            });
        });
        
        // If using a map service like Google Maps or Leaflet, initialize it here
        // This is just a placeholder for future map implementation
    });
</script>
@endpush 