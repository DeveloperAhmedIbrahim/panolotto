@php
    $content = getContent('footer.content', true);
    $contact = getContent('contact_us.content', true);
    $contactElements = getContent('contact_us.element', orderById: true);
    $socialIcons = getContent('social_icon.element', false, 4, true);
    $policies = getContent('policy_pages.element', false, 5, true);
    $gatewayContent = getContent('gateway.content', true);
    $gatewayElements = getContent('gateway.element', orderById: true);
@endphp

<footer class="footer-area">
    <div class="footer-area__style">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-6 footer-quick-links">
                    <h4>Useful Links</h4>
                    <ul class="">
                        <li><a class="" href="{{ url('/') }}">Home</a></li>
                        <li><a class="" href="{{ url('faqs') }}">FAQ</a></li>
                        <li><a class="" href="{{ url('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-6 footer-account">
                   <h4>Account</h4>
                    <ul class="">
                        <li><a class="" href="{{ url('user/login') }}">Login</a></li>
                        <li><a class="" href="{{ url('user/register') }}">Register</a></li>
                        <li><a href="{{ url('user/password/reset') }}">Forgot Password</a></li>
                    </ul>
                  </div>
                <div class="col-lg-3 col-md-3 col-5 footer-legals">
                    <h4>Legals</h4>
                    <ul class="">
                        <li><a href="{{ url('/policy/privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/policy/terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ url('/policy/game-policy') }}">Game Policy</a></li>
                    </ul>
                </div>

<!-- REPLACE the existing Talk To Us column with this block -->
<div class="col-lg-3 col-md-3 col-7 footer-legals">
    <div class="d-flex align-items-start justify-content-between footer-talk-wrapper" style="gap:12px;">
        <!-- left: contact + social (keeps existing markup) -->
        <div class="footer-talk-left" style="min-width:0;">
            <h4>Talk To Us</h4>
            <ul class="">
                <li><a href="mailto:info@panolotto.com">info@panolotto.com</a></li>
            </ul>
            <ul class="inline-social-links d-flex mt-3">
            </ul>
        </div>

        <!-- right: visitor counter (keeps script here so injected markup appears here) -->
        {{-- <div class="visitor-counter-box" aria-hidden="false">
            <!-- FreeVisitorCounters embed (placed here to render in this spot) -->
            <script type='text/javascript' src='https://www.freevisitorcounters.com/auth.php?id=43116cbedd0717d8d769f5108762c9a2040e781f'></script>
            <script type="text/javascript" src="https://freevisitorcounters.com/en/home/counter/1444721/t/0"></script>
        </div> --}}
    </div>
</div>

    <!-- Footer Top End-->

    <!-- bottom Footer -->
    <div class="bottom-footer">
        <div class="container">
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 mt-md-0">
                        <a class="footer-logo" href="{{ url('/') }}">
                            <img src="{{ url('assets/images/logo_icon/logo.png') }}" alt="image" style="max-height: 75px;" class="pb-3">
                        </a>
                    </div>
                    <div class="col-md-6 text-md-end text-center">
                        <span class="footer-content__left-text">
                            Copyright © 2026, All Right Reserved By Panolotto
                            <a class="text--base" href="{{ url('/') }}">.</a>
                        </span>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</footer>

<!-- CSS override to prevent the counter from being fixed to bottom-left -->
<style>
/* IMPORTANT: place after other CSS so it overrides the injected styles */
.visitor-counter-box { position: relative; z-index: 1; }

/* The widget injects a container with id="fvcounter" and often sets it fixed/absolute.
   These rules force it to act as a normal element inside your layout. */
#fvcounter,
#fvcounter * {
    position: relative !important;
    left: 0 !important;
    bottom: 0 !important;
    right: 0 !important;
    top: auto !important;
    margin: 0 !important;
    transform: none !important;
}

/* adjust spacing inside your footer if needed */
.visitor-counter-box { margin-top: 12px; }

/* (Optional) if you want the counter to match footer text size */
#fvcounter { font-size: 13px; }

/* If the widget still overflows, constrain its width */
.visitor-counter-box #fvcounter { max-width: 220px; overflow: hidden; }
</style>
