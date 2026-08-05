<style>

</style>
<div class="row justify-content-center gy-4">
    @foreach ($lotteries as $lottery)
        @if (@$lottery->activePhase)
            @php $lcTheme = ($loop->index % 9) + 1; @endphp
            <div class="col-xxl-3 col-lg-4 col-md-6">
                <div class="lottery-card lottery-theme-{{ $lcTheme }}">
                    {{-- Optional badge (chahiye to uncomment + text set karo):
                    <span class="lottery-card__badge"><i class="las la-fire"></i> @lang('Popular')</span>
                    --}}

                    <div class="lottery-card__illustration">
                        <svg viewBox="0 0 128 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="26" y="16" width="84" height="58" rx="14" fill="currentColor" opacity="0.12"/>
                            <rect x="16" y="26" width="84" height="54" rx="14" fill="currentColor" opacity="0.20"/>
                            <rect x="16" y="26" width="84" height="54" rx="14" fill="none" stroke="currentColor" stroke-width="2.5" opacity="0.5"/>
                            <circle cx="58" cy="53" r="19" fill="currentColor"/>
                            <path d="M49 44 L67 62 M67 44 L49 62" stroke="#fff" stroke-width="3.4" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h5 class="lottery-card__name">{{ __($lottery->name) }}</h5>

                    <p class="lottery-card__win-label">@lang('Win up to')</p>
                    <h3 class="lottery-card__prize">{{ number_format($lottery->maxPrize(), 2, '.', ',') }} {{ gs()->cur_text }}</h3>

                    <div class="countdown lottery-card__countdown" data-Date="{{ $lottery->activePhase->draw_date, 'd-m-Y H:i:s' }}">
                        <div class="running">
                            <timer class="countdown__menu">
                                <li class="countdown__list">
                                    <span class="countdown__time days"></span>
                                    <span class="lottery-card__unit">@lang('Days')</span>
                                </li>
                                <li class="countdown__list">
                                    <span class="countdown__time hours"></span>
                                    <span class="lottery-card__unit">@lang('Hours')</span>
                                </li>
                                <li class="countdown__list">
                                    <span class="countdown__time minutes"></span>
                                    <span class="lottery-card__unit">@lang('Minutes')</span>
                                </li>
                                <li class="countdown__list">
                                    <span class="countdown__time seconds"></span>
                                    <span class="lottery-card__unit">@lang('Seconds')</span>
                                </li>
                            </timer>
                        </div>
                    </div>

                    <div class="lottery-card__meta">
                        <div class="lottery-card__meta-col">
                            <span class="lottery-card__meta-label">@lang('Total Prize Pool')</span>
                            <span class="lottery-card__meta-value">{{ number_format($lottery->maxPrize(), 2, '.', ',') }} {{ gs()->cur_text }}</span>
                        </div>
                        <div class="lottery-card__meta-col text-end">
                            <span class="lottery-card__meta-label">@lang('Ticket Price')</span>
                            {{-- TODO: real ticket price field pata chale to yahan lagana --}}
                            <span class="lottery-card__meta-value">{{ number_format($lottery->price, 2 , '.', ',') }} {{ gs()->cur_text }}</span>
                        </div>
                    </div>

                    <div class="lottery-card__button">
                        <a class="lottery-card__btn" href="{{ route('lottery.play', ['slug' => slug($lottery->name), 'id' => $lottery->id]) }}">
                            @lang('Buy Tickets Now') <i class="las la-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if ($hasButton)
        <div class="col-12 text-center mt-2">
            <a class="btn btn--base" href="{{ route('lottery.tickets') }}"> @lang('VIEW ALL') </a>
        </div>
    @endif
</div>

<input type="hidden" name="lotteryTimeZone" id="lotteryTimeZone" value="{{ config('app.timezone') }}">

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
