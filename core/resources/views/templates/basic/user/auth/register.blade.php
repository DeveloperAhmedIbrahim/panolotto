@extends($activeTemplate . 'layouts.app')
@section('panel')
    @if (gs('registration'))
        @php

            $content = getContent('register.content', true);
        @endphp

        <section class="account">
            <div class="account-inner">
                <div class="account-left">
                    <div class="account-left__shape">
                        <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/login-1.png') }}">
                    </div>
                    <div class="account-left__content">
                        <h1 class="account-left__title">{{ __(@$content->data_values->heading) }}</h1>
                        <p class="account-left__desc">
                            <span class="account-left__icon"> <i class="fas fa-quote-left"></i> </span>
                            {{ __(@$content->data_values->subheading) }}
                            <span class="account-left__icon"> <i class="fas fa-quote-right"></i> </span>
                        </p>
                    </div>
                    <div class="account-left__thumb">
                        <img alt="" src="{{ getImage('assets/images/frontend/register/' . @$content->data_values->image, '560x420') }}">
                    </div>
                </div>
                <div class="account-right-wrapper">
                    <div class="w-100 text-center">
                        <a class="account-right-wrapper__logo" href="{{ route('home') }}">
                            <img alt="" src="{{ siteLogo('dark') }}">
                        </a>
                    </div>
                    <div class="account-right signup">
                        <div class="account-content">
                            <div class="account-form">
                                <h2 class="account-form__title">{{ __(@$content->data_values->form_title) }}</h2>
                                <p class="account-form__desc">{{ __(@$content->data_values->form_subtitle) }}</p>
                                <form action="{{ route('user.register') }}" class="verify-gcaptcha" method="post">
                                    @csrf
                                    <div class="row">
                                        @if (session()->get('reference'))
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Referred By')</label>
                                                    <input class="form--control" disabled type="text" value="{{ session()->get('reference') }}">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('First Name')</label>
                                                <input class="form--control" name="firstname" type="text" value="{{ old('firstname') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Last Name')</label>
                                                <input class="form--control" name="lastname" type="text" value="{{ old('lastname') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form--label">@lang('E-Mail Address')</label>
                                                <input class="form--control checkUser" name="email" required type="email"
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Password')</label>
                                                <div class="position-relative">
                                                    <input class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                                        name="password" required type="password">
                                                    <div class="password-show-hide far fa-eye-slash toggle-password fa-eye-slash" id="#password"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Confirm Password')</label>
                                                <div class="position-relative">
                                                    <input class="form-control form--control" id="password_confirmation" name="password_confirmation"
                                                        required type="password">
                                                    <div class="password-show-hide fas fa-eye-slash toggle-password fa-eye-slash"
                                                        id="#password_confirmation"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <x-captcha :path="$activeTemplate . 'partials'" />

                                        @if (gs()->agree)
                                            @php
                                                $policyPages = getContent('policy_pages.element', false, null, true);
                                                $content = getContent('register.content', true);
                                            @endphp
                                            <div class="form-group col-12">
                                                <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                                <label for="agree">@lang('I agree with')</label> <span>
                                                    @foreach ($policyPages as $policy)
                                                        <a class="text--base"
                                                            href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}"
                                                            target="_blank">{{ __($policy->data_values->title) }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>
                                        @endif

                                        <div class="col-sm-12 form-group">
                                            <button class="btn btn--base w-100" type="submit">@lang('Register')</button>
                                        </div>

                                        @include($activeTemplate . 'partials.social_login')

                                        <p class="account-form__text"> @lang('Already have an account?') <a class="account-form__text-link"  href="{{ route('user.login') }}">@lang('Log In')</a> </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-auto">
                        <div class="col-md-6">
                            <div class="account-form__footer">{{ __(@$content->data_values->footer_text) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div aria-hidden="true" aria-labelledby="existModalCenterTitle" class="modal custom--modal fade" id="existModalCenter" role="dialog"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark btn--sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                        <a class="btn btn--base btn--sm" href="{{ route('user.login') }}">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif
@endsection
@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
        <script>
            "use strict";
            (function($) {
                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';
                    var data = {
                        email: value,
                        _token: token
                    }
                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush

@push('style')
    <style>
        .account-form {
            max-width: 100%;
        }

        .continue-google a {
            font-size: 0.875rem !important;
        }
    </style>
@endpush
