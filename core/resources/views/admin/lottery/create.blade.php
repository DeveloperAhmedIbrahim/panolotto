@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.lottery.store', @$lottery->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader image="{{ @$lottery->image }}" class="w-100" type="lottery" :required="@$lottery ? false : true" />
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-12 col-md-6">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group ">
                                            <label>@lang('Name')</label>
                                            <input class="form-control" name="name" required type="text" value="{{ old('name', @$lottery->name) }}">
                                        </div>
                                    </div>
                                    @php
                                        $amount = old('price', @$lottery->price);
                                    @endphp
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="form-group ">
                                            <label>@lang('Price')</label>
                                            <div class="input-group">
                                                <input class="form-control" name="price" required step="any" type="number" value="{{ $amount > 0 ? getAmount($amount) : '' }}">
                                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        @php
                                            $lineVariation = old('line_variations');
                                            if (@$lottery) {
                                                $lineVariation = implode(',', $lottery->line_variations);
                                            }
                                        @endphp
                                        <div class="form-group">
                                            <label>@lang('Line Variation') <i class="las la-info-circle text--primary" title="@lang('Enter line variation numbers and separate the number by comma. For example: 4,7,10')"></i></label>
                                            <input class="form-control lineVariation" name="line_variations" placeholder="@lang('Eg: 4,7,10')" required type="text" value="{{ $lineVariation }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="form-group ">
                                            <label>@lang('No. Of Balls')</label>
                                            <input class="form-control" min="1" name="no_of_ball" required type="number" value="{{ old('no_of_ball', @$lottery->no_of_ball) }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="form-group ">
                                            <label>@lang('Ball Start From') <i class="las la-info-circle text--primary" title="@lang('The ball starts from must be 0 or 1')"></i> </label>
                                            <input class="form-control" min="0" name="ball_start_from" placeholder="@lang('Enter 0 or 1')" required type="number" value="{{ old('ball_start_from', @$lottery->ball_start_from) }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="form-group ">
                                            <label>@lang('No. of Picking Ball')</label>
                                            <input class="form-control" min="1" name="total_picking_ball" required type="number" value="{{ @$lottery->total_picking_ball ?? old('total_picking_ball') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <div class="form-group">
                                            <label>@lang('Multi Draw Option')</label>
                                            <select class="form-control" name="has_multi_draw" required>
                                                <option disabled selected value="">@lang('Select One')</option>
                                                <option @selected(old('has_multi_draw', @$lottery->has_multi_draw) == 1) value="1">@lang('Enable')</option>
                                                <option @selected(old('has_multi_draw', @$lottery->has_multi_draw) === 0) value="0">@lang('Disable')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input @checked(old('has_power_ball', @$lottery->has_power_ball)) class="form-check-input" id="hasPowerBall" name="has_power_ball" type="checkbox" value="1">
                                    <label class="form-check-label" for="hasPowerBall">@lang('Has Power Ball')</label>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label>@lang('No. Of Power Ball')</label>
                                    <input class="form-control powerBallInputs" min="0" name="no_of_pw_ball" type="number" value="{{ old('no_of_pw_ball', @$lottery->no_of_pw_ball) }}">
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label>@lang('Power Ball Start From')</label>
                                    <input class="form-control powerBallInputs" min="0" name="pw_ball_start_from" placeholder="" type="number" value="{{ old('pw_ball_start_from', @$lottery->pw_ball_start_from) }}">
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label>@lang('No. of Picking Power Ball')</label>
                                    <input class="form-control powerBallInputs" min="0" name="total_picking_power_ball" type="number" value="{{ old('total_picking_power_ball', @$lottery->total_picking_power_ball) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input @checked(old('auto_creation_phase', @$lottery->auto_creation_phase)) class="form-check-input" id="autoCreationPhase" name="auto_creation_phase" type="checkbox" value="1">
                                    <label class="form-check-label" for="autoCreationPhase">@lang('Phase Auto Creation')</label>
                                </div>

                                <div class="phaseDayTimeDiv @if (!old('auto_creation_phase', @$lottery->auto_creation_phase)) d-none @endif">
                                    @php
                                        $phaseType = null;
                                        if (old('phase_type')) {
                                            $phaseType = old('phase_type');
                                        } elseif (@$lottery) {
                                            $phaseType = $lottery->phaseCreationSchedules->first()->phase_type ?? 0;
                                        }
                                    @endphp
                                    <div class="form-check form-check-inline">
                                        <input @checked($phaseType == 1) class="form-check-input" id="weekly" name="phase_type" type="radio" value="1">
                                        <label class="form-check-label" for="weekly">
                                            @lang('Weekly')
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input @checked($phaseType == 2) class="form-check-input" id="monthly" name="phase_type" type="radio" value="2">
                                        <label class="form-check-label" for="monthly">
                                            @lang('Monthly')
                                        </label>
                                    </div>
                                    @php
                                        $oldDays = old('days') ?? null;
                                        $drawTimes = old('draw_times') ?? null;
                                    @endphp
                                    @if ($oldDays && count($oldDays) > 0)
                                        @foreach ($oldDays as $index => $oldDay)
                                            <div class="row parentDiv">
                                                <div class="col-xl-6 col-lg-6">
                                                    @if ($phaseType == 1)
                                                        <div class="form-group">
                                                            <label class="required">@lang('Draw Day')</label>
                                                            <select class="form-control" name="days[]" required>
                                                                <option disabled selected value="">@lang('Select One')</option>
                                                                @foreach (days() as $key => $day)
                                                                    <option @selected($oldDay == $key) value="{{ $key }}">{{ __($day) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label class="required">@lang('Draw Date')</label>
                                                            <input autocomplete="off" class="form-control" max="31" min="1" name="days[]" placeholder="@lang('Enter a day of the month (1-31)')" required value="{{ $oldDay }}">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="required">@lang('Draw Time')</label>
                                                        <div class="input-group clockpicker">
                                                            <input autocomplete="off" class="form-control" name="draw_times[]" placeholder="--:--" required type="text" value="{{ $drawTimes[$index] ? showDateTime($drawTimes[$index], 'H:i') : '' }}">

                                                            <button class="input-group-text ms-2 btn icon-btn @if ($loop->first) btn--success addBtn @else btn--danger removeBtn @endif" data-type="{{ $phaseType }}" type="button">
                                                                @if ($loop->first)
                                                                    <i class="las la-plus"></i>
                                                                @else
                                                                    <i class="las la-times"></i>
                                                                @endif
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif (@$lottery && $lottery->phaseCreationSchedules->count())
                                        @foreach ($lottery->phaseCreationSchedules as $schedule)
                                            <div class="row parentDiv">
                                                <div class="col-xl-6 col-lg-6">
                                                    @if ($schedule->phase_type == 1)
                                                        <div class="form-group">
                                                            <label class="required">@lang('Draw Day')</label>
                                                            <select class="form-control" name="days[]" required>
                                                                <option disabled selected value="">@lang('Select One')</option>
                                                                @foreach (days() as $key => $day)
                                                                    <option @selected($schedule->day == $key) value="{{ $key }}">{{ __($day) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label class="required">@lang('Draw Date')</label>
                                                            <input autocomplete="off" class="form-control" max="31" min="1" name="days[]" placeholder="@lang('Enter a day of the month (1-31)')" required value="{{ $schedule->day }}">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="required">@lang('Draw Time')</label>
                                                        <div class="input-group clockpicker">
                                                            <input autocomplete="off" class="form-control" name="draw_times[]" placeholder="--:--" required type="text" value="{{ showDateTime($schedule->time, 'H:i') }}">

                                                            <button class="input-group-text ms-2 btn icon-btn @if ($loop->first) btn--success addBtn @else btn--danger removeBtn @endif" data-type="{{ $lottery->phase_type }}" type="button">
                                                                @if ($loop->first)
                                                                    <i class="las la-plus me-0"></i>
                                                                @else
                                                                    <i class="las la-times me-0"></i>
                                                                @endif
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button class="btn btn--primary h-45 w-100 mt-3" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-clockpicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/vendor/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
@endpush

@if ($lottery)
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.lottery.winning.setting', $lottery->id) }}"><i class="las la-cog"></i>@lang('Winning Setting')</a>
    @endpush
@endif

@push('script')
    <script>
        (function($) {
            "use strict";

            let hasPowerBall = "{{ @$lottery->has_power_ball ?? 0 }}" * 1;

            @if (old('has_power_ball'))
                hasPowerBall = 1;
            @endif

            updatePowerBallsDOM(hasPowerBall);

            $('[name=has_power_ball]').on('click', function() {
                let condition = $(this).is(':checked') ? true : false;
                updatePowerBallsDOM(condition);
            });

            function updatePowerBallsDOM(condition) {
                let powerBallInputs = $('.powerBallInputs');
                if (condition == true) {
                    powerBallInputs.prop('disabled', false);
                    powerBallInputs.attr('required', true);
                    powerBallInputs.siblings('label').addClass('required');
                    $('[name=pw_ball_start_from]').attr('placeholder', "@lang('Enter 0 or 1')");
                } else {
                    powerBallInputs.val('');
                    powerBallInputs.prop('disabled', true);
                    powerBallInputs.removeAttr('required');
                    powerBallInputs.siblings('label').removeClass('required');
                    $('[name=pw_ball_start_from]').attr('placeholder', "");
                }
            }

            let phaseDayTimeDiv = $('.phaseDayTimeDiv');
            $('[name=auto_creation_phase]').on('click', function() {
                phaseDayTimeDiv.find('[name=phase_type]').prop('checked', false);
                if ($(this).is(':checked')) {
                    phaseDayTimeDiv.removeClass('d-none');
                } else {
                    phaseDayTimeDiv.find('.parentDiv').remove();
                    phaseDayTimeDiv.addClass('d-none');
                }
            });

            $('[name=phase_type]').on('click', function() {
                phaseDayTimeDiv.find('.parentDiv').remove();
                phaseDayTimeDiv.append(weekMonthField($(this).val(), 'add'));

                initClockPicker();
            });

            $(document).on('click', '.addBtn', function() {
                let type = $(this).data('type');
                phaseDayTimeDiv.append(weekMonthField(type));

                initClockPicker();
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).parents('.parentDiv').remove();
            });

            function weekMonthField(type, button = 'remove') {
                let buttonHtml = '';
                let weekMonthInput = '';
                if (button == 'remove') {
                    buttonHtml = `
                    <button type="button" class="input-group-text ms-2 btn icon-btn btn--danger removeBtn">
                        <i class="las la-times"></i>
                    </button>
                    `;
                } else {
                    buttonHtml = `
                    <button type="button" data-type="${type}" class="input-group-text ms-2 btn icon-btn btn--success addBtn">
                        <i class="las la-plus me-0"></i>
                    </button>
                    `;
                }

                if (type == 1) {
                    weekMonthInput = `<div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                            <label class="required">@lang('Draw Day')</label>
                            <select class="form-control" name="days[]" required>
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach (days() as $key => $day)
                                    <option value="{{ $key }}" >{{ __($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>`;
                } else {
                    weekMonthInput = `<div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                            <label class="required">@lang('Draw Date')</label>
                            <input autocomplete="off" class="form-control" min="1" max="31" placeholder="@lang('Enter a day of the month (1-31)')" name="days[]" required>
                        </div>
                    </div>`;
                }


                let html = `
                <div class="row parentDiv">
                    ${weekMonthInput}
                    <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                            <label class="required">@lang('Draw Time')</label>
                            <div class="input-group clockpicker">
                                <input autocomplete="off" class="form-control" name="draw_times[]" placeholder="--:--" type="text" required>
                                ${buttonHtml}
                            </div>
                        </div>
                    </div>
                </div>
                `;

                return html;
            }

            function initClockPicker() {
                $('.clockpicker').clockpicker({
                    placement: 'top',
                    align: 'left',
                    donetext: 'Done',
                    autoclose: true,
                });
            }

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        button.icon-btn {
            border-top-left-radius: 3px !important;
            border-bottom-left-radius: 3px !important;
        }

        .popover{
            position: absolute;
        }
    </style>
@endpush
