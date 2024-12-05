@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-bg py-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Reset Password')</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                            </div>
                            <form action="{{ route('user.password.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <input name="email" type="hidden" value="{{ $email }}">
                                    <input name="token" type="hidden" value="{{ $token }}">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Password')</label>
                                            <input class="form--control" name="password" required type="password">
                                            @if (gs()->secure_password)
                                                <div class="input-popup">
                                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                                    <p class="error number">@lang('1 number minimum')</p>
                                                    <p class="error special">@lang('1 special character minimum')</p>
                                                    <p class="error minimum">@lang('6 character password')</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Confirm Password')</label>
                                            <input class="form--control" name="password_confirmation" required type="password">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn--base w-100" type="submit"> @lang('Submit')</button>
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

@if (gs()->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
