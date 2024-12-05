@extends($activeTemplate . 'layouts.app')
@php
    $content = getContent('login.content', true);
@endphp
@section('panel')
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
                    <img alt="" src="{{ getImage('assets/images/frontend/login/' . @$content->data_values->image, '560x420') }}">
                </div>
            </div>
            <div class="account-right-wrapper">
                <a class="account-right-wrapper__logo" href="{{ route('home') }}">
                    <img alt="" src="{{ getImage(getFilePath('logoIcon') . '/logo_dark.png') }}">
                </a>
                <div class="account-right">
                    <div class="account-content">
                        <div class="account-form">
                            <h2 class="account-form__title">{{ __(@$content->data_values->form_title) }}</h2>
                            <p class="account-form__desc">{{ __(@$content->data_values->form_subtitle) }}</p>
                            <form action="{{ route('user.login') }}" class="verify-gcaptcha" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Username or Email')</label>
                                            <input class="form--control" name="username" required type="text" value="{{ old('username') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="form--label">@lang('Password')</label>
                                            <a class="forget-password" href="{{ route('user.password.request') }}"> @lang('Forget Password?')</a>
                                        </div>
                                        <div class="position-relative">
                                            <input class="form-control form--control" id="password" name="password" required type="password">
                                            <div class="password-show-hide far fa-eye toggle-password fa-eye-slash" id="#password"></div>
                                        </div>
                                    </div>

                                    <x-captcha :path="$activeTemplate . 'partials'" />

                                    <div class="col-sm-12 form-group">
                                        <button class="btn btn--base w-100" type="submit">@lang('Login')</button>
                                    </div>

                                    @include($activeTemplate . 'partials.social_login')

                                    <p class="account-form__text"> @lang('Don\'t have an account?') <a class="account-form__text-link" href="{{ route('user.register') }}"> @lang('Register') </a> </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="account-form__footer">{{ __(@$content->data_values->footer_text) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
