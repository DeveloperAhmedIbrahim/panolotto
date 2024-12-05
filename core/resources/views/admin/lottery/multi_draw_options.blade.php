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
                                    <th>@lang('Total Draw')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lottery->multiDrawOptions as $option)
                                    <tr>
                                        <td>{{ $option->total_draw }}</td>
                                        <td>{{ showAmount($option->discount) }}%</td>
                                        <td>
                                            @php
                                                echo $option->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary updateBtn" data-discount="{{ getAmount($option->discount) }}" data-id="{{ $option->id }}" data-total_draw="{{ $option->total_draw }}" type="butotn">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>

                                                @if ($option->status)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.lottery.multi.draw.option.status', $option->id) }}" data-question="@lang('Are you sure, you want to disable this option?')"><i class="la la-eye-slash"></i>@lang('Disable')</button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.lottery.multi.draw.option.status', $option->id) }}" data-question="@lang('Are you sure, you want to enable this option?')"><i class="la la-eye"></i>@lang('Enable')</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="4">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Options for') {{ __($lottery->name) }}</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.lottery.multi.draw.setting.store', $lottery->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Total Draw')</label>
                                    <input class="form-control" min="0" name="total_draw[]" required type="number">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label>@lang('Discount')</label>
                                    <div class="input-group">
                                        <input class="form-control" min="0" step="any" name="discount[]" required type="number">
                                        <span class="input-group-text input-group-end">%</span>
                                        <button class="input-group-text ms-2 btn icon-btn btn--success addMoreBtn" type="button"><i class="las la-plus ms-1"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Multi Draw Option')</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Total Draw')</label>
                            <input class="form-control" min="0" name="total_draw" required type="number">
                        </div>
                        <div class="form-group">
                            <label>@lang('Discount')</label>
                            <div class="input-group">
                                <input class="form-control" min="0" step="any" name="discount" required type="number">
                                <span class="input-group-text">%</span>
                            </div>
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
    <button class="btn btn-sm btn-outline--primary addBtn" type="button"><i class="las la-plus"></i> @lang('Add Options')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addBtn').on('click', function() {
                let modal = $("#addModal");
                modal.modal('show');
            });

            $('.addMoreBtn').on('click', function() {
                $("#addModal").find('.modal-body').append(`
                <div class="row parentRow">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label class="required">@lang('Total Draw')</label>
                            <input class="form-control" min="0" name="total_draw[]" required type="number">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label class="required">@lang('Discount')</label>
                            <div class="input-group">
                                <input class="form-control" min="0" step="any" name="discount[]" required type="number">
                                <span class="input-group-text input-group-end">%</span>
                                <button class="input-group-text ms-2 btn icon-btn btn--danger removeBtn" type="button"><i class="las la-times ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                `);
            });

            $('.updateBtn').on('click', function() {
                let data = $(this).data();
                let modal = $('#updateModal');
                modal.find('form').attr('action', `{{ route('admin.lottery.multi.draw.option.update', '') }}/${data.id}`);
                modal.find('[name=total_draw]').val(data.total_draw);
                modal.find('[name=discount]').val(data.discount);
                modal.modal('show');
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).parents('.parentRow').remove();
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        button.icon-btn {
            border-top-left-radius: 3px !important;
            border-bottom-left-radius: 3px !important;
        }

        .input-group-end {
            border-top-right-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }
    </style>
@endpush
