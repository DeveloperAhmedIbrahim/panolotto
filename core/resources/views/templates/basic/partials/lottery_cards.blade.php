<div class="row justify-content-center gy-4">
    @foreach ($lotteries as $lottery)
        @if (@$lottery->activePhase)
            <div class="col-lg-4 col-sm-6">
                <div class="ticket-item">
                    <div class="ticket-item__thumb">
                        <img src="{{ getImage(getFilePath('lottery') . '/' . $lottery->image, getFileSize('lottery')) }}">
                    </div>
                    <div class="ticket-item__shape">
                        <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/t-1.png') }}">
                    </div>
                    <div class="ticket-item__shape-one">
                        <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/lottery-1.png') }}">
                    </div>
                    <h5 class="ticket-item__name">{{ __($lottery->name) }}</h5>
                    <h3 class="ticket-item__prize">
                        <span>{{ shortNumber($lottery->maxPrize()) }} {{ gs()->cur_text }}
                    </h3>
                    <div class="countdown" data-Date="{{ $lottery->activePhase->draw_date, 'd-m-Y H:i:s' }}">
                        <h5 class="countdown__title">@lang('Draw closes in')</h5>
                        <div class="running">
                            <timer class="countdown__menu">
                                <li class="countdown__list">@lang('Days')<span class="countdown__time days"></span>
                                </li>
                                <li class="countdown__list"> @lang('Hours') <span
                                        class="countdown__time hours"></span></li>
                                <li class="countdown__list"> @lang('Mins') <span
                                        class="countdown__time minutes"></span></li>
                                <li class="countdown__list"> @lang('Secs') <span
                                        class="countdown__time seconds"></span></li>
                            </timer>
                        </div>
                    </div>
                    <div class="ticket-item__button">
                        <a class="btn btn--white btn--sm" href="{{ route('lottery.play', ['slug' => slug($lottery->name), 'id' => $lottery->id]) }}">
                            @lang('PURCHASE NOW')
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if ($hasButton)
        <div class="ticket-item__button">
            <a class="btn btn--base" href="{{ route('lottery.tickets') }}"> @lang('VIEW ALL') </a>
        </div>
    @endif
</div>

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/multi-countdown.js') }}"></script>
@endpush

@push('style')
    <style>
        .running {
            display: block !important;
        }
    </style>
@endpush
