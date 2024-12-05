@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp
@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif
@if ($customCaptcha)
    <div class="col-12">
        <div class="form-group">
            <div class="mb-3">
                @php echo $customCaptcha @endphp
            </div>
            <label class="form--label">@lang('Captcha')</label>
            <input class="form--control" name="captcha" required type="text">
        </div>
    </div>
@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML = '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });
            })(jQuery);
        </script>
    @endpush
@endif
