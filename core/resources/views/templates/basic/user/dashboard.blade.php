@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner user-dashboard">
        @php
            $kyc = getContent('kyc.content', true);
        @endphp
        @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
            <div class="alert bg--danger text-white" role="alert">
                <div class="d-flex justify-content-between">
                    <h4 class="alert-heading">@lang('KYC Documents Rejected')</h4>
                    <button class="btn btn--secondary btn--sm" data-bs-toggle="modal" data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                </div>
                <hr>
                <p>{{ __(@$kyc->data_values->reject) }} <a href="{{ route('user.kyc.form') }}" class="text-dark fw-bold">@lang('Click Here to Re-submit Documents')</a>.</p>
                <a href="{{ route('user.kyc.data') }}" class="text-dark fw-bold">@lang('See KYC Data')</a>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
            <div class="alert bg--info text-white" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                <hr>
                <p class="mb-0">{{ __(@$kyc->data_values->required) }} <a href="{{ route('user.kyc.form') }}"
                        class="text-dark fw-bold">@lang('Click Here to Submit Documents')</a>
                </p>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_PENDING)
            <div class="alert bg--warning text-white" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification pending')</h4>
                <hr>
                <p class="mb-0">{{ __(@$kyc->data_values->pending) }} <a href="{{ route('user.kyc.data') }}"
                        class="text-dark fw-bold">@lang('See KYC Data')</a>
                </p>
            </div>
        @endif

        <div class="row mt-0 g-3">
            <div class="col-xxl-9 col-xl-8 col-lg-6">
                <div class="row gy-4 mb-4">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="las la-shopping-cart"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('user.lottery.purchase.history') }}" class="title">@lang('Purchase Ticket')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['total_purchase']) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('user.lottery.winning.history') }}" class="title">@lang('Total Win')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['total_win']) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="fa fa-spinner"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('user.lottery.draw.pending') }}" class="title">@lang('Pending Draw')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['pending_draw']) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="fas fa-bars"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a class="title">@lang('Total Draw')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['total_draw']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-4 mb-4">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="las la-comment-dollar"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('user.deposit.history') }}" class="title">@lang('Purchase') {{ __(gs('cur_text')) }}</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['deposited']) }} {{ __(gs()->cur_text) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="las la-comments-dollar"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('user.cashout.history') }}" class="title">@lang('Total Withdraw')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['withdrawn']) }} {{ __(gs()->cur_text) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="las la-ticket-alt"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a href="{{ route('ticket.index') }}" class="title">@lang('Total Support Ticket')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['ticket']) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="dashbaord-widget">
                            <div class="shape">
                                <i class="las la-arrows-alt-h"></i>
                            </div>
                            <div class="dashbaord-widget__header">
                                <a class="title" href="{{ route('user.transactions') }}">@lang('Total Transaction')</a>
                            </div>
                            <div class="dashbaord-widget__amount">
                                <h4>{{ getAmount($widget['transaction']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @include($activeTemplate . 'partials.winning_table', [
                    'winners' => $winners,
                    'pagination' => false,
                ])
            </div>
            <div class="col-xxl-3 col-xl-4 col-lg-6">
                <div class="dashboard-right-widget h-100">
                    <div class="text-center mb-2">
                        <h2>{{ getAmount($widget['total_referrals']) }}</h2>
                        <p>@lang('Direct Referrals')</p>
                    </div>
                    <div class="mt-3">
                        <p class="fs--16px referrr-title">@lang('Latest Referrals')</p>
                        <ul class="list-group list-group-flush">
                            @if ($latestReferrals->count())
                                <li class="list-group-item d-flex justify-content-between flex-wrap px-0">
                                    <span>@lang('User')</span>
                                    <span>@lang('Join At')</span>
                                </li>
                                @foreach ($latestReferrals as $latestReferral)
                                    <li class="list-group-item d-flex justify-content-between flex-wrap px-0">
                                        <span>{{ __($latestReferral->fullname) }}</span>
                                        <span>{{ showDateTime($latestReferral->created_at) }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-flush mt-3">
                                    <h4>@lang('No referrals users found')</h4>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="modal custom--modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <span aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('style')
    <style>
        .dashboard-container {
            max-width: unset !important;
        }

        .referrr-title {
            position: relative;
        }

        .referrr-title:after {
            position: absolute;
            content: "";
            left: 0;
            top: 1.563rem;
            background: hsl(var(--base));
            width: 3.938rem;
            height: 0.25rem;
            border-radius: 0.625rem;
        }
    </style>
@endpush
