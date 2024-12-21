@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @include($activeTemplate.'sections.banner')
    <section class="how_work_section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-header text-center">
                        <h2 class="section-title">It’s easy to join and play!</h2>
                        <p class="mt-3">Joining is simple and rewarding! Sign up today to start winning big with our easy-to-use lottery platform.</p>
                    </div>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInLeft;">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">1</div>
                        <h3 class="title mt-4">Create an Account</h3>
                        <p class="mt-2">Create an account quickly. Join now to access exciting lottery games and exclusive rewards.</p>
                    </div><!-- how-work-card end -->
                </div>
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInLeft;">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">2</div>
                        <h3 class="title mt-4">Make a play for 2XRP per line</h3>
                        <p class="mt-2">Choose from a variety of thrilling lottery games. Pick your favorite and get ready to win big!</p>
                    </div><!-- how-work-card end -->
                </div>
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInLeft;">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">3</div>
                        <h3 class="title mt-4">Pick the weeks to play</h3>
                        <p class="mt-2">Pick your preferred lottery game and start playing for a chance to win amazing prizes!</p>
                    </div><!-- how-work-card end -->
                </div>
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInLeft;">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">4</div>
                        <h3 class="title mt-4">Watch live draw</h3>
                        <p class="mt-2">Win the lottery and turn your dreams into reality. Play now for your chance to strike it rich!</p>
                    </div><!-- how-work-card end -->
                </div>
            </div>
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
