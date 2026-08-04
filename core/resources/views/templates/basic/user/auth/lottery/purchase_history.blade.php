@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="mb-4">
            <h4 class="mb-2">@lang('Purchase History')</h4>
        </div>
        <div class="accordion table--acordion" id="transactionAccordion">
            @forelse($userPicks as $userPick)
                <div class="accordion-item transaction-item">
                    <h2 class="accordion-header" id="h-{{ $loop->iteration }}">
                        <button aria-controls="c-1" aria-expanded="false" class="accordion-button collapsed" data-bs-target="#c-{{ $loop->iteration }}" data-bs-toggle="collapse" type="button">
                            <div class="col-lg-4 col-sm-5 col-8 order-1 icon-wrapper">
                                <div class="left">
                                    <div class="content">
                                        <h6 class="trans-title">{{ __(@$userPick->phase->lottery->name) }}</h6>
                                        <span class="text-muted font-size--14px mt-2">{{ showDateTime($userPick->created_at, 'd M, Y @g:i:a') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-12 order-sm-2 order-3 content-wrapper mt-sm-0 mt-3">
                                <p class="text-muted font-size--14px">{{ $userPick->pickedTickets->count() }} x {{ $userPick->lotteryPrice() }} {{ __(gs("cur_text")) }}</p>
                            </div>
                            <div class="col-lg-4 col-sm-3 col-4 order-sm-3 order-2 text-end amount-wrapper">
                                <p><b>{{ showAmount($userPick->amount) }} {{ __(gs("cur_text")) }}</b></p>
                            </div>
                        </button>
                    </h2>
                    <div aria-labelledby="h-1" class="accordion-collapse collapse" data-bs-parent="#transactionAccordion" id="c-{{ $loop->iteration }}">
                        <div class="accordion-body">
                            <ul class="caption-list">
                                @foreach ($userPick->pickedTickets as $pickedTicket)
                                    <li>
                                        <span class="caption">@lang('Ticket')#{{ $loop->iteration }}</span>
                                        <div class="value">
                                            @foreach ($pickedTicket->normal_balls as $normalBall)
                                                <span class="ball normal">{{ $normalBall }}</span>
                                            @endforeach
                                            @foreach ($pickedTicket->power_balls as $powerBall)
                                                <span class="ball power">{{ $powerBall }}</span>
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach

                            </ul>

                        </div>
                    </div>
                </div><!-- transaction-item end -->
            @empty
                <div class="accordion-body text-center bg-white">
                    <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ paginateLinks($userPicks) }}
        </div>
    </div>
@endsection

@push('style')
    <style>
        .ball {
            display: inline-block;
            height: 35px;
            width: 35px;
            border-radius: 50%;
            line-height: 35px;
            text-align: center;
            margin: 2px;
        }

        .ball:last-child {
            margin-right: 0px;
        }

        .ball.normal {
            background: #d7d7d7;
            color: #757575;
        }

        .ball.power {
            background: #e35353;
            color: #fff;
        }
    </style>
@endpush
