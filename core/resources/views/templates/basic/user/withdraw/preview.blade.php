@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h3 class="mb-2"> {{__(withrdawKeyword())}} @lang(' Confirmation')</h3>
                </div>
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{ route('user.cashout.submit') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="mb-2">
                                @php
                                    echo $withdraw->method->description;
                                @endphp
                            </div>
                            <x-viser-form identifierValue="{{ $withdraw->method->form_id }}" identifier="id" />
                            @if (auth()->user()->ts)
                                <div class="form-group">
                                    <label>@lang('Google Authenticator Code')</label>
                                    <input class="form-control form--control" name="authenticator_code" required type="text">
                                </div>
                            @endif
                            <div class="form-group">
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
