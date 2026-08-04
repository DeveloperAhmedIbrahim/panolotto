@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Request Number')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Requested Time')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coinRequests as $coinRequest)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $coinRequest->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $coinRequest->user->id) }}"><span>@</span>{{ $coinRequest->user->username }}</a>
                                            </span>
                                        </td>
                                        <td> {{ $coinRequest->request_number }}</td>
                                        <td>
                                            {{ showAmount($coinRequest->amount) }}
                                        </td>
                                        <td>
                                            @php echo $coinRequest->statusBadge; @endphp
                                        </td>
                                        <td>
                                            {{ showDateTime($coinRequest->created_at) }}
                                        </td>
                                        <td>
                                            @if ($coinRequest->status == 0)
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.coin.request.approve', $coinRequest->id) }}" data-question="@lang('Are you sure, you want to approve this request')?"><i class="la la-check"></i>@lang('Approve')</button>
                                            @endif
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
                @if ($coinRequests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coinRequests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search ..." />
@endpush
