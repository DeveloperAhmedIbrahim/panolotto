@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (!blank($phases))
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table--responsive--md">
                                    <thead>
                                        <tr>
                                            <th>@lang('S.N')</th>
                                            <th>@lang('Lottery')</th>
                                            <th>@lang('Draw Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($phases as $phase)
                                            <tr>
                                                <td>{{ $phases->firstItem() + $loop->index }}</td>
                                                <td><span class="fw-bold">{{ __(@$phase->lottery->name) }}</span></td>
                                                <td>{{ showDateTime($phase->draw_date, 'd M, Y H:i A') }}</td>
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

                    @if ($phases->hasPages())
                        <div class="card-footer">
                            {{ paginateLinks($phases) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
