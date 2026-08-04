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
                                        <td>{{ showDateTime($phase->draw_date, 'd M, Y h:i A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.draw.ball.select', $phase->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-money-bill-alt"></i>@lang('Draw Now')</a>
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
            </div>
        </div>
    </div>
@endsection
