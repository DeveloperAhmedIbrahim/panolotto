@php
    $content = getContent('footer.content', true);
    $contact = getContent('contact_us.content', true);
    $contactElements = getContent('contact_us.element', orderById: true);
    $socialIcons = getContent('social_icon.element', false, 4, true);
    $policies = getContent('policy_pages.element', false, 5, true);
    $gatewayContent = getContent('gateway.content', true);
    $gatewayElements = getContent('gateway.element', orderById: true);
@endphp

<footer class="footer-area">
    <div class="footer-area__style">
        <div class="container">
            <div class="row justify-content-center gy-5">
                <div class="col-xl-6 col-sm-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}"> <img alt="" src="{{ siteLogo() }}"></a>
                        </div>
                        <p class="footer-item__desc">{{ __(@$content->data_values->description) }}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"> @lang('Quick Links') </h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('home') }}">@lang('Home')</a></li>
                            @php
                                $pages = App\Models\Page::where('is_default', Status::NO)
                                    ->where('tempname', $activeTemplate)
                                    ->get();
                            @endphp
                            @foreach ($pages as $item)
                                <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('pages', ['slug' => $item->slug]) }}">{{ __($item->name) }}</a></li>
                            @endforeach
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('blogs') }}">@lang('Blog')</a></li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('contact') }}">@lang('Contact')</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Explore')</h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('lottery.tickets') }}">@lang('Lotteries')</a></li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('results') }}">@lang('Results')</a></li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('faqs') }}">@lang('FAQs')</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"> @lang('Contact Us') </h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item">
                                <p class="mb-0">
                                    <i class="las la-map-marker"></i>
                                    {{ __(@$contact->data_values->address) }}
                                </p>
                            </li>

                            @foreach ($contactElements as $item)
                                <li class="footer-menu__item">
                                    <p class="mb-0"> @php echo $item->data_values->icon @endphp {{ $item->data_values->contact_address }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row gy-4 justify-content-between mt-4">
                <div class="col-sm-6">
                    <div class="social-list-wrapper">
                        <h5 class="social-list-wrapper__title"> @lang('Join Our Community') </h5>
                        <ul class="social-list">
                            @foreach ($socialIcons as $social)
                                <li class="social-list__item">
                                    <a class="social-list__link" href="{{ $social->data_values->url }}" target="_blank">@php echo $social->data_values->icon @endphp</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="payment-gateway">
                        <h5 class="payment-gateway__title"> {{ __(@$gatewayContent->data_values->heading) }} </h5>
                        <div class="payment-gateway__wrapper">
                            @foreach ($gatewayElements as $gatewayElement)
                                <div class="payment-gateway__thumb">
                                    <img alt="" src="{{ getImage('assets/images/frontend/gateway/' . $gatewayElement->data_values->image, '120x20') }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Top End-->

    <!-- bottom Footer -->
    <div class="bottom-footer">
        <div class="container">
            <div class="bottom-footer__style py-3">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="bottom-footer__text"> &copy; {{ date('Y') }} {{ gs()->site_name }} @lang('All Rights to Reserved.') </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bottom-footer__right">
                            <div class="bottom-footer__right-text">
                                @foreach ($policies as $policy)
                                    <a class="@if ($loop->first) bottom-footer__right-link @else bottom-footer__right-link-inner @endif" href="{{ route('policy.pages', $policy->slug) }}"> {{ __($policy->data_values->title) }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
