@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.lottery.winning.setting.store', $lottery->id) }}" method="POST">
                        @csrf
                        @foreach ($lottery->winningCombinations() as $key => $winningCombination)
                            @php
                                $winningSetting = $lottery->winningSettings
                                    ->where('power_ball', $winningCombination['power_ball'])
                                    ->where('normal_ball', $winningCombination['normal_ball'])
                                    ->first();
                            @endphp
                            <input name="winning[{{ $key }}][lottery_id]" type="hidden" value="{{ $lottery->id }}">
                            <div class="parent">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>@lang('No. Of Power Ball')</label>
                                            <input class="form-control" name="winning[{{ $key }}][power_ball]" readonly type="number" value="{{ $winningCombination['power_ball'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>@lang('No. Of Ball')</label>
                                            <input class="form-control" name="winning[{{ $key }}][normal_ball]" readonly type="number" value="{{ $winningCombination['normal_ball'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('Win')</label>
                                            <div class="input-group">
                                                <input class="form-control win_times" min="1" name="winning[{{ $key }}][win_times]" value="{{ @$winningSetting ? getAmount($winningSetting->win_times) : '' }}" required step="any" type="number">
                                                <span class="input-group-text">@lang('Times')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('Win Amount')</label>
                                            <div class="input-group">
                                                <input class="form-control total_amount" name="winning[{{ $key }}][prize_money]" readonly step="any" value="{{ @$winningSetting ? getAmount($winningSetting->prize_money) : '' }}" type="number" required>
                                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>@lang('Price On/Off')</label>
                                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" @if($winningSetting && $winningSetting->status === 1) checked @endif name="winning[{{ $key }}][status]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <span class="fw-bold text--primary">@lang('Lottery Price') : {{ showAmount($lottery->price) }}</span>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.win_times').on('input', function() {
                let price = "{{ $lottery->price }}" * 1;
                $(this).parents('.parent').find('.total_amount').val($(this).val() * price);
            });
        })(jQuery);
    </script>
@endpush
