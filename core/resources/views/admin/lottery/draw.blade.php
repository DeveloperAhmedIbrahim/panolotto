@extends('admin.layouts.app')
@section('panel')
    <form action="" method="POST">
        <div class="row gy-4">
            @csrf
            <div class="hiddenFields d-none"></div>
            <div class="col-xl-6">
                <div class="card phase-card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Waiting For Draw')</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="info d-flex justify-content-start gap-3">
                                <span class="normal-ball"></span>
                                <span class="power-ball"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Lottery')</label>
                            <select name="phase_id"class="form-control phase">
                                <option disabled selected value="">@lang('Select One')</option>
                                @foreach ($phases as $phase)
                                    <option data-lottery="{{ $phase->lottery }}" data-phase_no="{{ __($phase->phase_no) }}" data-sold_tickets="{{ $phase->sold_tickets }}" value="{{ $phase->id }}" @selected(old('phase_id') == $phase->id)>{{ __(@$phase->lottery->name) }} - {{ __($phase->phase_no) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="showMessage d-none"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">@lang('Details')</h5>
                        <button class="btn btn-sm btn-outline--primary submitBtn" disabled type="submit"> <i class="las la-trophy"></i>@lang('Set Winner Now')</button>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <span>@lang('Lottery')</span>
                                <span class="lottery">@lang('Lottery Name (Phase)')</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <span>@lang('Total Selled Tickets')</span>
                                <span><span class="totalSoldTicket">0</span> @lang('tickets') <span class="totalSoldAmount">0</span> {{ gs('cur_text') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <span>@lang('Total Picking Normal Ball')</span>
                                <span class="nbPick">0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <span>@lang('Total Picking Power Ball')</span>
                                <span class="pwPick">0</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Winning Balls')</h5>
                    </div>
                    <div class="card-body">
                        <div class="winning-balls-area text-center">
                            <div class="winning-balls">
                                <h5>@lang('Please Select Lottery First')</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 balls-card d-none">
                <div class="card">
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            let nbPick = 0;
            let pwPick = 0;
            let isSetWinningSetting = false;
            let winningBalls = $('.winning-balls');
            let hiddenFields = $('.hiddenFields');

            $('.phase').on('change', function() {
                let data = $(this).find(':selected').data();
                let soldTickets = data.sold_tickets;
                let phase_no = data.phase_no;
                let lottery = data.lottery;
                $('.submitBtn').attr('disabled', true);

                if(lottery == undefined){
                    return false;
                }

                let showMessage = $('.showMessage');
                if(!lottery.winning_settings.length){
                    isSetWinningSetting = false;
                    showMessage.html(`
                    <small>
                        <span class="text--danger"><i class="las la-info-circle"></i> @lang('The winning settings for this lottery have not been completed yet and you need to set the winning settings before the lottery can take place.')</span>
                        <a href="{{ route('admin.lottery.winning.setting', '') }}/${lottery.id}">@lang('Set Now')</a>
                    </small>`);

                    showMessage.removeClass('d-none');
                }else{
                    isSetWinningSetting = true;
                    showMessage.html('');
                    showMessage.addClass('d-none');
                }

                let price = lottery.price;
                let maxNbBall = lottery.no_of_ball;
                let maxPwBall = lottery.no_of_pw_ball;

                nbPick = lottery.total_picking_ball;
                pwPick = lottery.total_picking_power_ball;

                if (lottery.ball_start_from == 1) {
                    maxNbBall += 1;
                }
                if (lottery.pw_ball_start_from == 1) {
                    maxPwBall += 1;
                }

                //set details
                $('.lottery').text(`${lottery.name} (${phase_no})`);
                $('.totalSoldTicket').text(`${soldTickets}`);
                $('.totalSoldAmount').text(soldTickets * lottery.price);
                $('.nbPick').text(nbPick);
                $('.pwPick').text(pwPick);

                //set winning balls
                let html = '';
                for (var i = 0; i < nbPick; i++) {
                    html += `<span class="normalBall" data-normal_ball="${i}">#</span>`;
                }

                for (var i = 0; i < pwPick; i++) {
                    html += `<span class="pwb powerBall" data-power_ball="${i}">#</span>`;
                }

                winningBalls.html(html);

                //set balls
                let normalBalls = '';
                let powerBalls = '';
                for (var n = lottery.ball_start_from; n < maxNbBall; n++) {
                    normalBalls += `<span class="normalBtn" data-normal_ball="${n}">${n}</span>`;
                }

                for (var p = lottery.pw_ball_start_from; p < maxPwBall; p++) {
                    powerBalls += `<span class="pwb powerBtn" data-power_ball="${p}">${p}</span>`;
                }
                html = `<div class="lottery-balls">
                            ${normalBalls}
                        </div><hr>
                        <div class="lottery-balls">
                            ${powerBalls}
                        </div>`;

                $('.balls-card .card-body').html(html);
                $('.balls-card').removeClass('d-none');
            }).change();

            $(document).on('click', '.normalBtn, .powerBtn', function() {
                if ($(this).hasClass('normalBtn') && $(this).hasClass('powerBtn')) {
                    return false;
                }

                let ballNo           = null;
                let type             = null;
                let className        = null;
                let winningClassName = null;
                let balls            = '';
                let pickCount        = 0;

                if ($(this).hasClass('normalBtn')) {
                    balls            = winningBalls.find('.normalBall');
                    type             = "normal_ball";
                    className        = 'normalBtn';
                    winningClassName = 'normalBall';
                    pickCount        = nbPick;
                }

                if ($(this).hasClass('powerBtn')) {
                    balls            = winningBalls.find('.powerBall');
                    type             = "power_ball";
                    className        = 'powerBtn';
                    winningClassName = 'powerBall';
                    pickCount        = pwPick;
                }

                ballNo = $(this).data(type);

                if ($(this).hasClass('selected')) {
                    winningBalls.find(`[data-${type}="${ballNo}"]`).removeClass('added');
                    winningBalls.find(`[data-${type}="${ballNo}"]`).text('#');
                    winningBalls.find(`[data-${type}="${ballNo}"]`).attr(`data-${type}`, 0);
                    $(`.${className}`).not('.selected').removeClass('disabled');
                    removeInput(ballNo, type);
                    $(this).removeClass('selected');
                } else {
                    if (pickCount <= winningBalls.find(`.${winningClassName}.added`).length) {
                        return false;
                    }

                    $(this).addClass('selected');
                    let firstElement = balls.not('.added').first();
                    firstElement.text(ballNo);
                    firstElement.attr(`data-${type}`, ballNo);
                    firstElement.addClass('added');

                    if (pickCount - 1 == winningBalls.find(`.${winningClassName}.added`).length - 1) {
                        $(`.${className}`).not('.selected').addClass('disabled');
                    }
                    appendInput(ballNo, type);
                }

                enableDisableBtn();
            });

            function appendInput(ballNo, type) {
                hiddenFields.append(`<input type="hidden" name="winning_${type}[]" data-${type}="${ballNo}" value="${ballNo}">`);
            }

            function removeInput(ballNo, type) {
                hiddenFields.find(`input[data-${type}="${ballNo}"]`).remove();
            }

            function enableDisableBtn() {
                let submitBtn  = $('.submitBtn');
                let normalBall = winningBalls.find('.normalBall.added').length;
                let powerBall  = winningBalls.find('.powerBall.added').length;
                if (nbPick == normalBall && pwPick == powerBall && isSetWinningSetting) {
                    submitBtn.removeAttr('disabled');
                } else {
                    submitBtn.attr('disabled', true);
                }
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .phase-card {
            min-height: 236px;
        }

        .winning-balls-area .winning-balls span {
            height: 7#px;
            width: 70px;
            display: inline-block;
            text-align: center;
            line-height: 70px;
            border-radius: 50px;
            font-size: 30px;
            border: 1px solid #d3d3d3;
            background: #e5e5e5;
            color: #8d8d8d;
            margin: 5px;
        }

        .winning-balls-area .winning-balls span.added {
            color: #fff;
            border: 1px solid #4634ff;
            background: #4634ff;
        }

        .winning-balls-area .winning-balls span.pwb {
            border: 1px solid #959595;
            background: #a1a0a0;
            color: #d9d9d9;
        }

        .winning-balls-area .winning-balls span.pwb.added {
            color: #fff;
            border: 1px solid #e35353;
            background: #e35353;
        }

        .winning-balls-area .winning-balls span.selected {
            opacity: 0.5;
        }

        .lottery-balls span {
            display: inline-block;
            height: 90px;
            width: 90px;
            border-radius: 50px;
            text-align: center;
            line-height: 90px;
            font-size: 45px;
            color: #fff;
            border: 1px solid #4634ff;
            background: #4634ff;
            cursor: pointer;
            margin: 5px;
            transition: all .3s
        }

        .lottery-balls span:hover {
            background: #1808aa;
            border-color: #1808aa;
        }

        .lottery-balls span.pwb {
            border: 1px solid #e35353;
            background: #e35353;
        }

        .lottery-balls span.pwb:hover {
            background: #c91d1d;
            border-color: #c91d1d;
        }

        .lottery-balls span.selected {
            opacity: 0.5;
        }

        .lottery-balls span.disabled,
        .lottery-balls span.pwb.disabled {
            cursor: no-drop;
        }
    </style>
@endpush
