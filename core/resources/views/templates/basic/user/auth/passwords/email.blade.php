@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-bg py-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                            </div>
                            <form action="{{ route('user.password.email') }}" method="POST" class="verify-gcaptcha">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Email or Username')</label>
                                            <input autofocus="off" class="form--control" name="value" required type="text" value="{{ old('value') }}">
                                        </div>
                                    </div>

                                    <x-captcha :path="$activeTemplate . 'partials'" />

                                    <div class="col-12">
                                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
