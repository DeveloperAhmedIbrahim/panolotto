<div class="card">
    @if (!blank($winners))
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th>@lang('Lottery')</th>
                            <th>@lang('Winning Balls')</th>
                            <th>@lang('Prize')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($winners as $winner)
                            <tr>
                                <td>
                                    <span class="fw-bold"> <span > {{ __(@$winner->phase->lottery->name) }}</span></span>
                                </td>
                                <td>
                                    @foreach ($winner->normal_balls as $normalBall)
                                        <span class="ball normal">{{ $normalBall }}</span>
                                    @endforeach
                                    @foreach ($winner->power_balls as $powerBall)
                                        <span class="ball power">{{ $powerBall }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    {{ showAmount($winner->prize_money) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card-body text-center">
            <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
        </div>
    @endif

    @if ($pagination && $winners->hasPages())
        <div class="card-footer">
            {{ paginateLinks($winners) }}
        </div>
    @endif
</div>

@push('style')
    <style>
        span.ball {
            display: inline-block;
            height: 35px;
            width: 35px;
            border-radius: 50%;
            line-height: 35px;
            text-align: center;
            margin: 2px;
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
