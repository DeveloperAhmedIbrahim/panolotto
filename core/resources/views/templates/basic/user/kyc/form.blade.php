@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h4>@lang('KYC Form')</h4>
                </div>
                <div class="card custom--card">
                    <div class="card-body">
                        <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <x-viser-form identifier="act" identifierValue="kyc" />

                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
