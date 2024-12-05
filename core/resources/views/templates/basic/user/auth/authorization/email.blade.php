@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-bg py-80">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <div class="d-flex justify-content-between gap-2 flex-wrap border-bottom mb-3">
                            <h5 class="text-center ">@lang('Verify Email Address')</h5>
                            <a href="{{ route('user.logout') }}" class="text--base">@lang('Logout')</a>
                        </div>
                        <form action="{{ route('user.verify.email') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="verification-text mb-2">@lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(auth()->user()->email) }}</p>

                            @include($activeTemplate . 'partials.verification_code')

                            <div class="mb-3">
                                <button type="submit" class="btn btn--md btn--base w-100">@lang('Submit')</button>

                                <small class="d-block mt-2">
                                    @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span id="countdown" class="fw-bold">--</span> @lang('seconds')</span> <a href="{{ route('user.send.verify.code', 'email') }}" class="try-again-link text--base d-none"> @lang('Try again')</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
       <script>
        var distance =Number("{{@$user->ver_code_send_at->addMinutes(2)->timestamp-time()}}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
