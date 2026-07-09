@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.header')
    @if (!Route::is('home') && !Route::is('contact') && !Route::is('user.*') && !Route::is('ticket.*'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif
    @yield('content')

    @include($activeTemplate . 'partials.footer')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == 1 && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a class="text-primary" href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <button class="btn btn--base text-white w-100 policy" type="button">@lang('Allow')</button>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            let elements = document.querySelectorAll('[s-break]');

            Array.from(elements).forEach(element => {
                let html = element.innerHTML;

                if (typeof html != 'string') {
                    return false;
                }
                let breakLength = parseInt(element.getAttribute('s-break'));
                html = html.split(" ");

                var colorText = [];

                if (breakLength < 0) {
                    colorText = html.slice(breakLength);
                } else {
                    colorText = html.slice(0, breakLength);
                }

                let solidText = [];

                html.filter(ele => {
                    if (!colorText.includes(ele)) {
                        solidText.push(ele);
                    }
                });

                var color = element.getAttribute('s-color') || "text--white";

                colorText = `<span class="${color}">${colorText.toString().replaceAll(',', ' ')}</span>`;
                solidText = solidText.toString().replaceAll(',', ' ');

                breakLength < 0 ? element.innerHTML = `${solidText} ${colorText}` : element.innerHTML = `${colorText} ${solidText}`

            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);
        })(jQuery);
    </script>
@endpush
