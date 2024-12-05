@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.setting.coin.instruction.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group ">
                                    <label> @lang('Maximum Request') {{ __(gs('cur_text')) }}</label>
                                    <div class="input-group">
                                        <input class="form-control" name="request_amount" value="{{ getAmount(gs('request_amount')) }}" required type="number" step="any">
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group ">
                                    <label>@lang('Instruction')</label>
                                    <textarea class="form-control nicEdit" name="request_instruction">{{ gs('request_instruction') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
