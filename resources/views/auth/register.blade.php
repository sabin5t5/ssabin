@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">First Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus>
                                @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-end">Middle Name</label>
                            <div class="col-md-6">
                                <input id="middle_name" type="text"
                                    class="form-control @error('middle_name') is-invalid @enderror"
                                    name="middle_name" value="{{ old('middle_name') }}">
                                @error('middle_name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Current Address</label>
                            <div class="col-md-6">
                                <input type="text" name="street_address" class="form-control mb-2"
                                    placeholder="Street Address" value="{{ old('street_address') }}">

                                <input type="text" name="city" class="form-control mb-2"
                                    placeholder="City" value="{{ old('city') }}" required>

                                <input type="text" name="state_province" class="form-control mb-2"
                                    placeholder="State / Province" value="{{ old('state_province') }}">

                                <input type="text" name="postal_code" class="form-control mb-2"
                                    placeholder="Postal Code" value="{{ old('postal_code') }}">

                                <select name="country" class="form-select form-control" required id="country">
                                    <option value="">Select Country</option>
                                    @foreach (config('countries') as $code => $value)
                                        <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>
                                            {{ $value['flag'] }}{{ $value['name'] }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="mobile" class="col-md-4 col-form-label text-md-end">Mobile Number</label>
                            <div class="col-md-6 d-flex gap-2">
                                <select name="country_code" class="form-select form-control w-50" required id="country_code">
                                    @foreach (config('countries') as $code => $value)
                                        <option value="{{ $value['code'] }}" {{ old('country_code') == $code ? 'selected' : '' }}>
                                            {{ $value['flag'] }} {{ $value['code'] }} 
                                        </option>
                                    @endforeach
                                </select>
                                <input id="mobile" type="text"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    name="mobile" placeholder="7041234567"
                                    value="{{ old('mobile') }}" required>
                            </div>
                            @error('mobile') <span class="text-danger offset-md-4">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const countrySelect = document.getElementById('country');
    const codeInput = document.getElementById('country_code');

    // 1️⃣ Helper: Update phone code when country changes
    function updateCode() {
        const selected = countrySelect.selectedOptions[0];
        codeInput.value = selected.getAttribute('data-code') || '';
        localStorage.setItem('country', countrySelect.value);
        localStorage.setItem('country_code', codeInput.value);
    }

    countrySelect.addEventListener('change', updateCode);

    // 2️⃣ Load from browser cache first
    const cachedCountry = localStorage.getItem('country');
    const cachedCode = localStorage.getItem('country_code');
    if (cachedCountry && cachedCode) {
        countrySelect.value = cachedCountry;
        codeInput.value = cachedCode;
        return;
    }

    // 3️⃣ Try browser locale
    const locale = navigator.language || navigator.userLanguage; // e.g., "en-US"
    if (locale && locale.includes('-')) {
        const iso = locale.split('-')[1].toUpperCase();
        if (document.querySelector(`#country option[value="${iso}"]`)) {
            countrySelect.value = iso;
            updateCode();
            return;
        }
    }

    // 4️⃣ Fallback: IP Geolocation via free API
    try {
        const res = await fetch('https://ipapi.co/json/');
        const data = await res.json();
        if (data.country && document.querySelector(`#country option[value="${data.country}"]`)) {
            countrySelect.value = data.country;
            updateCode();
        }
    } catch (e) {
        console.warn('IP geolocation failed:', e);
    }

    // 5️⃣ Ensure phone code is synced
    updateCode();
});
</script>

@endsection
