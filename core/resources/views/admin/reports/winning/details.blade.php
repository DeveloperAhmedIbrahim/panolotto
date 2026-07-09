@extends('admin.layouts.app')

@section('panel')
    @php
        $prizeMoney = $phase->winners->sum('prize_money');
    @endphp
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Sold Amount')</th>
                                    <th>@lang('Given Prize')</th>
                                    @if ($phase->sold_amount >= $prizeMoney)
                                        <th class="bg--success text--white">@lang('Profit')</th>
                                    @else
                                        <th class="bg--danger text--white">@lang('Loss')</th>
                                    @endif
                                    <th>@lang('Winning Normal Balls')</th>
                                    @if ($phase->lottery->has_power_ball)
                                        <th>@lang('Winning Power Balls')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ showAmount($phase->sold_amount) }}</td>
                                    <td>{{ showAmount($prizeMoney) }}</td>
                                    <td>{{ showAmount(abs($phase->sold_amount - $prizeMoney)) }}</td>
                                    <td>
                                        <div class="ball">
                                            @foreach ($phase->winning_normal_balls as $normalBall)
                                                <span class="normal winning_ball">{{ $normalBall }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    @if ($phase->lottery->has_power_ball)
                                        <td>
                                            <div class="ball">
                                                @foreach ($phase->winning_power_balls as $powerBall)
                                                    <span class="power winning_ball">{{ $powerBall }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    @if ($phase->lottery->has_power_ball)
                                        <th>@lang('Power Balls')</th>
                                    @endif
                                    <th>@lang('Normal Balls')</th>
                                    <th>@lang('Prize')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phase->winners as $winner)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$winner->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $winner->user_id) }}"><span>@</span>{{ @$winner->user->username }}</a>
                                            </span>
                                        </td>
                                        @if ($phase->lottery->has_power_ball)
                                            <td>
                                                <div class="ball">
                                                    @foreach ($winner->pickedTicket->power_balls as $powerBall)
                                                        <span class="power @if (in_array($powerBall, $phase->winning_power_balls)) winning_ball @endif">{{ $powerBall }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="ball">
                                                @foreach ($winner->pickedTicket->normal_balls as $normalBall)
                                                    <span class="normal @if (in_array($normalBall, $phase->winning_normal_balls)) winning_ball @endif">{{ $normalBall }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>{{ showAmount($winner->prize_money) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('style')
    <style>
        .ball span {
            display: inline-block;
            width: 30px;
            height: 30px;
            text-align: center;
            background: #d7d7d7;
            color: #757575;
            border-radius: 50%;
            line-height: 30px;
            font-size: 12px !important;
        }

        .ball .power.winning_ball {
            background: #e38308;
            color: #fff;
        }

        .ball .normal.winning_ball {
            background: #609f4c;
            color: #fff;
        }
    </style>
@endpush
