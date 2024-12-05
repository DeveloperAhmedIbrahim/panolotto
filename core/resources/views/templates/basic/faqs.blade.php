@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $elements = getContent('faq.element', orderById: true);
    @endphp
    <div class="faq-section pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <ul class="list faq-card--list">
                                @foreach ($elements as $item)
                                    @if ($loop->even)
                                        @continue
                                    @endif
                                    <li>
                                        <div class="faq-card">
                                            <div class="faq-card__question">
                                                <div class="faq-card__icon">
                                                    <span class="faq-card__icon-text">@lang('Q')</span>
                                                </div>
                                                <h4 class="m-0">{{ __($item->data_values->question) }}</h4>
                                            </div>
                                            <div class="faq-card__answer">
                                                <div class="faq-card__icon">
                                                    <span class="faq-card__icon-text">@lang('A')</span>
                                                </div>
                                                <p class="mb-0">{{ __($item->data_values->answer) }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list faq-card--list">
                                @foreach ($elements as $item)
                                    @if ($loop->odd)
                                        @continue
                                    @endif
                                    <li>
                                        <div class="faq-card">
                                            <div class="faq-card__question">
                                                <div class="faq-card__icon">
                                                    <span class="faq-card__icon-text">@lang('Q')</span>
                                                </div>
                                                <h4 class="m-0">{{ __($item->data_values->question) }}</h4>
                                            </div>
                                            <div class="faq-card__answer">
                                                <div class="faq-card__icon">
                                                    <span class="faq-card__icon-text">@lang('A')</span>
                                                </div>
                                                <p class="mb-0">{{ __($item->data_values->answer) }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
