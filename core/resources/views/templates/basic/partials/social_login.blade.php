@if (
    @gs('socialite_credentials')->linkedin->status ||
        @gs('socialite_credentials')->facebook->status == Status::ENABLE ||
        @gs('socialite_credentials')->google->status == Status::ENABLE)
    <div class=" d-flex gap-3 gap-md-4 flex-wrap mb-3">

        @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class=" provider flex-fill">
                <a href="{{ route('user.social.login', 'google') }}" class="btn w-100 ">
                    <span class="icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icon/google.png') }}" alt="Google">
                    </span> @lang("Continue with Google")
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
            <div class=" provider flex-fill">
                <a href="{{ route('user.social.login', 'facebook') }}" class="btn w-100 ">
                    <span class="icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icon/facebook.png') }}" alt="Facebook">
                    </span> @lang("Continue with Facebook")
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
            <div class="provider  flex-fill">
                <a href="{{ route('user.social.login', 'linkedin') }}" class="btn w-100 ">
                    <span class="icon">
                        <img src="{{ asset($activeTemplateTrue . 'images/icon/linkedin.png') }}" alt="Linkedin">
                    </span> @lang("Continue with Linkedin")
                </a>
            </div>
        @endif

    </div>
@endif
