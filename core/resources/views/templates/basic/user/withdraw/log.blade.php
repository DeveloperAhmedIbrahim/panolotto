@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="mb-2">{{__(withrdawKeyword())}} @lang('History')</h4>
                <span>
                    <a class="btn btn--secondary btn--smd" href="{{ route('user.cashout') }}"> {{__(withrdawKeyword())}} @lang(' Now') <i class="las la-long-arrow-alt-right"></i></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="accordion table--acordion" id="transactionAccordion">
                    @forelse($withdraws as $withdraw)
                        <div class="accordion-item transaction-item">
                            <h2 class="accordion-header" id="h-{{ $loop->iteration }}">
                                <button aria-controls="c-1" aria-expanded="false" class="accordion-button collapsed" data-bs-target="#c-{{ $loop->iteration }}" data-bs-toggle="collapse" type="button">
                                    <div class="col-lg-4 col-sm-5 col-8 order-1 icon-wrapper">
                                        <div class="left">
                                            @if ($withdraw->status == 1)
                                                <div class="icon icon-success">
                                                    <i class="las la-check"></i>
                                                </div>
                                            @elseif($withdraw->status == 2)
                                                <div class="icon icon-warning">
                                                    <i class="las la-spinner fa-spin"></i>
                                                </div>
                                            @elseif($withdraw->status == 3)
                                                <div class="icon icon-danger">
                                                    <i class="las la-ban"></i>
                                                </div>
                                            @endif
                                            <div class="content">
                                                <h6 class="trans-title">{{ __(@$withdraw->method->name) }}</h6>
                                                <span class="text-muted font-size--14px mt-2">{{ showDateTime($withdraw->created_at, 'M d Y @g:i:a') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 order-sm-2 order-3 content-wrapper mt-sm-0 mt-3">
                                        <p class="text-muted font-size--14px"><b>#{{ $withdraw->trx }}</b></p>
                                    </div>
                                    <div class="col-lg-4 col-sm-3 col-4 order-sm-3 order-2 text-end amount-wrapper">
                                        <p><b>{{ showAmount($withdraw->amount) }} {{ __(gs()->cur_text) }}</b></p>
                                    </div>
                                </button>
                            </h2>
                            <div aria-labelledby="h-1" class="accordion-collapse collapse" data-bs-parent="#transactionAccordion" id="c-{{ $loop->iteration }}">
                                <div class="accordion-body">
                                    <ul class="caption-list">
                                        <li>
                                            <span class="caption">@lang('Charge')</span>
                                            <span class="value">{{ showAmount($withdraw->charge) }} {{ __(gs()->cur_text) }}</span>
                                        </li>
                                        <li>
                                            <span class="caption">@lang('After Charge')</span>
                                            <span class="value">{{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __(gs()->cur_text) }}</span>
                                        </li>
                                        <li>
                                            <span class="caption">@lang('Conversion')</span>
                                            <span class="value">{{ gs()->cur_sym . showAmount($withdraw->amount - $withdraw->charge) }} x {{ gs()->cur_sym . showAmount($withdraw->rate) }} = {{ gs()->cur_sym . showAmount($withdraw->final_amount) }}</span>
                                        </li>
                                        <li>
                                            <span class="caption">@lang('Status')</span>
                                            <span class="value">
                                                @php echo $withdraw->statusBadge @endphp <a href="javascript:void(0)"><i @if ($withdraw->status == 3) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif class="las la-info-circle detailBtn" data-user_data="{{ json_encode($withdraw->withdraw_information) }}"></i></a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="accordion-body text-center">
                            <h4 class="text--muted"><i class="far fa-frown"></i> {{ __($emptyMessage) }}</h4>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ paginateLinks($withdraws) }}
                </div>
            </div>
        </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div class="modal" id="detailModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark btn--sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
