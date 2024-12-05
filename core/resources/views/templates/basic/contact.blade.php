@extends($activeTemplate . 'layouts.frontend')
@php
    $content     = getContent('contact_us.content', true);
    $elements    = getContent('contact_us.element', orderById: true);
    $socialIcons = getContent('social_icon.element', false, 4, true);
    $user        = auth()->user();
@endphp
@section('content')
    <section class="contact-section">
        <div class="container">
            <div class="contact text-center">
                <h1 class="contact__title">{{ __(@$content->data_values->heading) }}</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact__wrapper">
                        <div class="contact__shape">
                            <img alt="" src="{{ getImage('assets/images/frontend/contact_us/' . @$content->data_values->image, '700x575') }}">
                        </div>
                        <div class="row gy-4">
                            <div class="col-xl-8 col-lg-7 pe-lg-5">
                                <form action="{{ route('contact.submit') }}" class="verify-gcaptcha" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Name')</label>
                                                <input @if ($user) readonly @endif class="form--control" name="name" required type="text" value="{{ old('name', @$user->fullname) }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Email')</label>
                                                <input @if ($user) readonly @endif class="form--control" name="email" required type="email" value="{{ old('email', @$user->email) }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Subject')</label>
                                                <input class="form--control" name="subject" required type="text" value="{{ old('subject') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form--label">@lang('Message')</label>
                                                <textarea class="form--control" name="message" required rows="6">{{ old('message') }}</textarea>
                                            </div>
                                        </div>

                                        <x-captcha :path="$activeTemplate . 'partials'" />

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button class="btn btn--base" type="submit"> @lang('Submit')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-4 col-lg-5 ps-lg-5">
                                @foreach ($elements as $item)
                                    <div class="contact-item">
                                        <div class="contact-item__icon">@php echo $item->data_values->icon @endphp</div>
                                        <div class="contact-item__content">
                                            <h5 class="contact-item__title"> {{ __($item->data_values->title) }} </h5>
                                            <p class="contact-item__desc">{{ __($item->data_values->subtitle) }}</p>
                                            <p class="contact-item__link"> {{ __($item->data_values->contact_address) }} </p>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="social-list-wrapper list-style">
                                    <h5 class="social-list-wrapper__title">@lang('Join Our Community')</h5>
                                    <ul class="social-list list-two">
                                        @foreach ($socialIcons as $social)
                                            <li class="social-list__item">
                                                <a class="social-list__link" href="{{ $social->data_values->url }}" target="_blank">@php echo $social->data_values->icon @endphp</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('style')
    <style>
        .info-card__content a {
            text-decoration: none;
            color: unset;
        }
    </style>
@endpush
