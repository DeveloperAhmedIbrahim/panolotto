@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Lottery') | @lang('Phase No')</th>
                                    <th>@lang('Draw Date')</th>
                                    <th>@lang('Is Set Winner')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phases as $phase)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __(@$phase->lottery->name) }}</span><br>
                                            <span>{{ __(showPhase($phase->phase_no)) }}</span>
                                        </td>
                                        <td>{{ showDateTime($phase->draw_date, 'd M, Y h:i A') }}
                                        </td>
                                        <td>
                                            @php
                                                echo $phase->winnerStatusBadge;
                                            @endphp
                                            @if ($phase->is_set_winner == Status::YES)
                                                <a href="{{ route('admin.report.winning.detail', $phase->id) }}">
                                                    <i class="las la-info-circle font-size-16"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                echo $phase->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button @if ($phase->lottery->auto_creation_phase == Status::ENABLE) disabled @endif class="btn btn-sm btn-outline--primary editBtn"
                                                    data-auto_phase="{{ $phase->lottery->auto_creation_phase }}"
                                                    data-draw_date="{{ showDateTime($phase->draw_date, 'Y-m-d h:i a') }}" data-id="{{ $phase->id }}"
                                                    data-lottery_id="{{ $phase->lottery_id }}" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>

                                                @if ($phase->status)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.lottery.phase.status', $phase->id) }}"
                                                        data-question="@lang('Are you sure, you want to disable the phase?')"><i class="la la-eye-slash"></i>@lang('Disable')</button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.lottery.phase.status', $phase->id) }}"
                                                        data-question="@lang('Are you sure, you want to enable the phase?')"><i class="la la-eye"></i>@lang('Enable')</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($phases->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($phases) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="phaseModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Lottery')</label>
                            <select class="form-control" name="lottery_id" required>
                                <option disabled selected value="">@lang('Select One')</option>
                                @foreach ($lotteries as $lottery)
                                    <option value="{{ $lottery->id }}">{{ __($lottery->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Draw Date')</label>
                            <input autocomplete="off" class="phase-datePicker form-control" name="draw_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch="yes" placeholder="Search..." />
    <button class="btn btn-outline--primary addBtn" type="button"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            var initDateRangePicker = (startDate = null) => {
                var options = {
                    singleDatePicker: true,
                    timePicker: true,
                    minDate: new Date(),
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'YYYY-MM-DD hh:mm A'
                    }
                }

                if (startDate != null) {
                    options.startDate = startDate;
                }

                $('.phase-datePicker').daterangepicker(options);
            }

            initDateRangePicker();

            $('.addBtn').on('click', function() {
                let modal = $('#phaseModal');
                let data = $(this).data();
                let action = "{{ route('admin.lottery.phase.save') }}";

                modal.find('form').attr('action', `${action}`);
                modal.find('.modal-title').text(`@lang('Add Phase')`);
                modal.find('[name=draw_date]').val('');
                modal.find('[name=lottery_id]').val('');

                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let modal = $('#phaseModal');
                let data = $(this).data();

                if (data.auto_phase == 1) {
                    return false;
                }

                let action = "{{ route('admin.lottery.phase.save') }}";
                modal.find('form').attr('action', `${action}/${data.id}`);

                modal.find('.modal-title').text(`@lang('Update Phase')`);
                modal.find('[name=lottery_id]').val(data.lottery_id);

                modal.find('.phase-datePicker').val(data.draw_date);
                initDateRangePicker(data.draw_date);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .font-size-16 {
            font-size: 16px;
        }
    </style>
@endpush
