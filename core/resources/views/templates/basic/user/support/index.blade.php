@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="text-end mb-3 d-flex flex-wrap justify-content-between gap-1">
                    <h4>{{ __($pageTitle) }}</h4>
                    <a class="btn btn--secondary btn--smd" href="{{ route('ticket.open') }}">@lang('Open Support Ticket') <i class="las la-arrow-right"></i></a>
                </div>
                <div class="card">
                    @if (!blank($supports))
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table--responsive--md">
                                    <thead>
                                        <tr>
                                            <th>@lang('Subject')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Priority')</th>
                                            <th>@lang('Last Reply')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supports as $support)
                                            <tr>
                                                <td> <a class="fw-bold" href="{{ route('ticket.view', $support->ticket) }}"> [@lang('Ticket')#{{ $support->ticket }}]
                                                        {{ __($support->subject) }} </a></td>
                                                <td>
                                                    @php echo $support->statusBadge; @endphp
                                                </td>
                                                <td>
                                                    @if ($support->priority == 1)
                                                        <span class="badge badge--dark">@lang('Low')</span>
                                                    @elseif($support->priority == 2)
                                                        <span class="badge badge--success">@lang('Medium')</span>
                                                    @elseif($support->priority == 3)
                                                        <span class="badge badge--primary">@lang('High')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ diffForHumans($support->last_reply) }}
                                                </td>

                                                <td>
                                                    <a class="btn btn--icon btn--primary" href="{{ route('ticket.view', $support->ticket) }}">
                                                        <i class="fa fa-desktop"></i>
                                                    </a>
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
                    @if ($supports->hasPages())
                        <div class="card-footer">
                            {{ paginateLinks($supports) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
