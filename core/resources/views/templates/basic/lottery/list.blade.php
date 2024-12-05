@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="py-70">
        <div class="container">
            @if ($lotteries->count())
                @include($activeTemplate . 'partials.lottery_cards', ['lotteries' => $lotteries, 'hasButton' => false])

                @if ($lotteries->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ paginateLinks($lotteries) }}
                    </div>
                @endif
            @else
                <div class="empty-message text-center">
                    <i class="las la-frown"></i>
                    <h3 class="title text-muted">@lang('There is no running lottery')</h3>
                </div>
            @endif
        </div>
    </div>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('style')
    <style>
        .empty-message i {
            font-size: 80px;
        }

        .empty-message .title {
            font-family: unset;
            margin-top: 0;
        }
    </style>
@endpush
