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
                                    <th>@lang('Given Prize')</th>
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
                                        <td>{{ showDateTime($phase->draw_date, 'd M, Y H:i:s') }}</td>
                                        <td>{{ showAmount($phase->total_prize) }}</td>
                                        <td>
                                            <a href="{{ route('admin.report.winning.detail', $phase->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-desktop"></i>@lang('Detail')
                                            </a>
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
                @if ($phases->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($phases) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch="yes" placeholder="Search..." />
@endpush
