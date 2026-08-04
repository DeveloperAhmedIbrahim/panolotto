@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="show-filter mb-3 text-end">
                <button class="btn btn-outline--primary showFilterBtn btn-sm" type="button"><i class="las la-filter"></i> @lang('Filter')</button>
            </div>
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.report.ticket.purchase.history') }}">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Username')</label>
                                <input class="form-control" name="search" type="text" value="{{ request()->search }}">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Lottery')</label>
                                <select class="form-control" name="lottery_id">
                                    <option value="">@lang('All')</option>
                                    @foreach ($lotteries as $lottery)
                                        <option @selected($lottery->id == request()->lottery_id) value="{{ $lottery->id }}">{{ __($lottery->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Phase')</label>
                                <select class="form-control" name="phase_no">
                                    <option value="">@lang('All')</option>
                                    @for ($i = 1; $i <= $maxPhase; $i++)
                                        <option @selected($i == request()->phase_no) value="{{ $i }}">{{ __(showPhase($i)) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="datepicker-here form-control bg--white pe-2 date-range" placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Ticket') | @lang('Phase')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Purchased At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userPicks as $userPick)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __(@$userPick->phase->lottery->name) }}</span><br>
                                            <small>{{ __(showPhase(@$userPick->phase->phase_no)) }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ @$userPick->user->fullname }}</span><br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $userPick->user_id) }}"><span>@</span>{{ @$userPick->user->username }}</a>
                                            </span>
                                        </td>
                                        <td><span class="fw-bold">{{ showAmount($userPick->amount) }}</span> </td>
                                        <td>{{ showDateTime($userPick->created_at, 'd M, Y H:i A') }}</td>
                                        @php
                                            $hasPowerBall = 0;
                                            if (!blank($userPick->pickedTickets->first()->power_balls)) {
                                                $hasPowerBall = 1;
                                            }
                                        @endphp
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary detailBtn" data-has_power_ball="{{ $hasPowerBall }}" data-picked_tickets="{{ $userPick->pickedTickets }}"><i class="las la-desktop"></i>@lang('Detail')</button>
                                        </td>
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
                @if ($userPicks->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($userPicks) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('All Tickets')</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush ticketsUl">
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }

            $('.date-range').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event, picker.startDate, picker.endDate));


            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

            $('.status').on('change', function() {
                $('#search-form').submit();
            });

            $('.detailBtn').on('click', function() {
                let data = $(this).data();
                let modal = $('#detailModal');

                let html = `<li class="list-item d-flex justify-content-between">
                                <span class="fw-bold">@lang('SL')</span>
                                <span class="fw-bold">@lang('Normal Balls')</span>
                                ${data.has_power_ball == 1 ? `<span class="fw-bold">@lang('Power Balls')</span>` : '' }
                            </li>`;


                $.each(data.picked_tickets, function(index, ticket) {

                    html += `<li class="list-item d-flex justify-content-between align-items-center">
                        <span class="me-3">@lang('Ticket#')${formattedNumber(index + 1)}</span>`;

                    let normalBall = getBalls(ticket.normal_balls, 'normal');
                    html += normalBall;

                    if (data.has_power_ball == 1) {
                        let powerBall = getBalls(ticket.power_balls, 'power');
                        html += powerBall;
                    }

                    html += '</li>';
                });

                $('.ticketsUl').html(html);
                modal.modal('show');
            });

            function getBalls(balls, type) {
                let html = `<div class="${type}">`;

                if (balls.length > 0) {
                    $.each(balls, function(index, ball) {
                        html += `<span class="ball">${ball}</span>`;
                    });
                }

                html += '</div>';
                return html;
            }

            function formattedNumber(n) {
                return n > 9 ? "" + n : "0" + n;
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .status {
            background-color: #fff;
        }

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

        .normal .ball {
            background: #d7d7d7;
            color: #757575;
        }

        .power .ball {
            background: #e35353;
            color: #fff;
        }

        .list-item {
            border-bottom: 1px solid #f3f3f3;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .list-item:last-child {
            border-bottom: unset;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    </style>
@endpush
