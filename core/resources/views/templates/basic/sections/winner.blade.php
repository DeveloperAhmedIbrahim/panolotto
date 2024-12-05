@php
    $content = getContent('winner.content', true);
    $winners = App\Models\Winner::with('user', 'phase', 'phase.lottery:id,name,image')
        ->groupBy('phase_id', 'user_id')
        ->selectRaw('*,SUM(prize_money) as prize_money')
        ->orderBy('prize_money', 'desc')
        ->limit(12)
        ->get();
@endphp

@if ($winners->count())
    <section class="winner-section py-80">
        <div class="container">
            <div class=" winner-section__wrapper flex-between">
                <div class="section-heading style-left">
                    <h6 class="section-heading__subtitle">{{ __(@$content->data_values->heading) }}</h6>
                    <h2 class="section-heading__title" s-break="-3" s-color="text--base">{{ __(@$content->data_values->subheading) }}</h2>
                </div>
                <div class="winner-time d-md-block d-none">
                    <a class="btn btn--base" href="{{ @$content->data_values->button_url }}"> {{ __(@$content->data_values->button_text) }} </a>
                </div>
            </div>
            <div class="flex-between">
                <div class="top-winner">
                    <h5 class="top-winner__title">{{ __(@$content->data_values->title) }}</h5>
                </div>
                <div class="winner-time d-md-none d-block">
                     <a class="btn btn--base" href="{{ @$content->data_values->button_url }}"> {{ __(@$content->data_values->button_text) }} </a>
                </div>
            </div>
            <div class="row gy-4 justify-content-center align-items-center">
                @foreach ($winners as $winner)
                    <div class="col-xl-4 col-md-6">
                        <div class="winner-item @if ($loop->iteration % 3 == 2) item-style @else section-bg @endif">
                            <div class="winner-item__shape">
                                <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/w-2.png') }}">
                            </div>
                            <div class="flex-between">
                                <div class=" flex-between">
                                    <div class="winner-item__thumb">
                                        <img alt="" src="{{ getImage(getFilePath('lottery') . '/' . @$winner->phase->lottery->image, getFileSize('lottery')) }}">
                                    </div>
                                    <div>
                                        <span class="winner-item__content-name"> @lang('Game') </span>
                                        <h6 class="winner-item__content-info"> {{ __(@$winner->phase->lottery->name) }} </h6>
                                    </div>
                                </div>
                                <div class="winner-item__content">
                                    <span class="winner-item__content-name"> @lang('Player') </span>
                                    <h6 class="winner-item__content-info"> {{ __(@$winner->user->fullname) }} </h6>
                                </div>
                                <div class="winner-item__content  content-style">
                                    <span class="winner-item__content-name"> @lang('Price') </span>
                                    <h6 class="winner-item__content-info text--red">{{ showAmount($winner->prize_money, exceptZeros: true) }} {{  gs()->cur_text }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
