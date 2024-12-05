@php
    $content = getContent('lottery.content', true);
    $lotteries = App\Models\Lottery::active()
        ->whereHas('winningSettings')
        ->whereHas('phases', function ($query) {
            $query
                ->active()
                ->where('draw_date', '>=', now()->toDateTimeString())
                ->where('is_set_winner', Status::NO);
        })
        ->limit(3)
        ->with('winningSettings', 'activePhase')
        ->get();
@endphp

<section class="ticket-section py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-heading">
                    <h6 class="section-heading__subtitle">{{ __(@$content->data_values->heading) }}</h6>
                    <h2 class="section-heading__title">{{ __(@$content->data_values->subheading) }}</h2>
                </div>
            </div>
        </div>
        @include($activeTemplate.'partials.lottery_cards', ['lotteries' => $lotteries, 'hasButton' => true])
    </div>
</section>
