@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  bg--transparent shadow-none">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two bg-white">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lotteries as $lottery)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <img alt="" class="thumb" src="{{ getImage(getFilePath('lottery') . '/' . $lottery->image, getFileSize('lottery')) }}">
                                                <a href="{{ route('admin.lottery.create', $lottery->id) }}">
                                                    <span class="fw-bold ms-2">{{ __($lottery->name) }}</span>
                                                </a>
                                            </div>

                                        </td>
                                        <td>{{ showAmount($lottery->price) }}</td>
                                        <td>
                                            @php
                                                echo $lottery->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <button aria-expanded="false" class="btn btn-sm dropdown-toggle btn-outline--primary" data-bs-toggle="dropdown" type="button"> <i class="las la-down-arrow"></i> @lang('Action')</button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.lottery.create', $lottery->id) }}"><i class="la la-pencil-alt"></i> @lang('Edit')</a>

                                                <a class="dropdown-item" href="{{ route('admin.lottery.winning.setting', $lottery->id) }}"><i class="la la-coins"></i> @lang('Winning Settings')</a>

                                                @if ($lottery->has_multi_draw)
                                                    <a class="dropdown-item" href="{{ route('admin.lottery.multi.draw.setting', $lottery->id) }}"><i class="las la-ticket-alt"></i> @lang('Multi Draw Settings')</a>
                                                @endif

                                                @if ($lottery->status)
                                                    <a class="dropdown-item confirmationBtn" data-action="{{ route('admin.lottery.status', $lottery->id) }}" data-question="@lang('Are you sure to disable this lottery')?" href="javascript:void(0)"><i class="la la-eye-slash"></i> @lang('Disable')</a>
                                                @else
                                                    <a class="dropdown-item confirmationBtn" data-action="{{ route('admin.lottery.status', $lottery->id) }}" data-question="@lang('Are you sure to enable this lottery')?" href="javascript:void(0)"><i class="la la-eye"></i> @lang('Enable')</a>
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
                        </table>
                    </div>
                </div>
                @if ($lotteries->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($lotteries) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search..." />
    <a class="btn btn-outline--primary h-45" href="{{ route('admin.lottery.create') }}"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('style')
    <style>
        .user .thumb {
            border-radius: 50%;
        }

        .table-responsive {
            background: transparent;
            min-height: 350px;
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
    </style>
@endpush
