@php
    $content = getContent('banner.content', true);
@endphp

<section class="banner-section mb-80">
    <div class="banner-section__shape">
        <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/banner-shape.png') }}">
    </div>
    <span class="hero-powered">@lang('Powered by') <b>XRP</b></span>
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 col-sm-7 col-xsm-8">
                <div class="banner-content">
                    <span class="hero-badge"><i class="las la-star"></i> @lang('Win Big with Crypto')</span>

                    <h2 class="banner-content__title" s-break="-1">{{ __(@$content->data_values->heading) }}</h2>

                    <p class="banner-content__desc mt-0">{{ __(@$content->data_values->subheading) }}</p>

                    <input type="hidden" id="counterDateTime" value="{{ $heroCounter->datetime }}">
                    <input type="hidden" id="serverTimezone" value="{{ config('app.timezone') }}">

                    {{-- Bara price block hataya (mock me hero me nahi, stats strip me already hai). Wapas chahiye to uncomment:
                    <div class="banner-content__button d-flex align-items-start text-light flex-column mt-0">
                        <div class="fs-1"><b>{{ $heroCounter->price }} XRP</b></div>
                        <div class="fs-6">Panolotto lets the good times roll.</div>
                    </div>
                    --}}

                    <div class="hero-countdown-wrap">
                        <span class="hero-countdown__title">@lang('Next Draw in')</span>
                        <div class="hero-countdown">
                            <div class="hero-countdown__box">
                                <span class="hero-countdown__num" id="counterDays">00</span>
                                <span class="hero-countdown__label">@lang('Days')</span>
                            </div>
                            <div class="hero-countdown__box">
                                <span class="hero-countdown__num" id="counterHours">00</span>
                                <span class="hero-countdown__label">@lang('Hours')</span>
                            </div>
                            <div class="hero-countdown__box">
                                <span class="hero-countdown__num" id="counterMints">00</span>
                                <span class="hero-countdown__label">@lang('Minutes')</span>
                            </div>
                            <div class="hero-countdown__box">
                                <span class="hero-countdown__num" id="counterSeconds">00</span>
                                <span class="hero-countdown__label">@lang('Seconds')</span>
                            </div>
                        </div>
                    </div>

                    <div class="banner-content__button">
                        <a class="btn btn--white hero-btn" href="{{ @$content->data_values->button_url }}">
                            {{ __(@$content->data_values->button_text) }} <i class="las la-arrow-right"></i>
                        </a>
                    </div>

                    <div class="hero-secure"><i class="las la-shield-alt"></i> @lang('Secure. Transparent. Fair.')</div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-5 col-xsm-4">
                <div class="banner-right"></div>
            </div>
        </div>
    </div>
</section>

<div class="overview-section pb-50">
    <div class="container">
        <div class="row gy-4 gy-lg-0 overview-wrapper wow fadeInUp">
            <div class="col-lg-3 col-sm-6 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount">12,450+</h3>
                        <p class="stat-label">Happy Players</p>
                        <p class="stat-sub">From around the world</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="las la-ticket-alt"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount">8,320+</h3>
                        <p class="stat-label">Tickets Sold</p>
                        <p class="stat-sub">Across all lotteries</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="las la-trophy"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount">2,890+</h3>
                        <p class="stat-label">Winners</p>
                        <p class="stat-sub">Big rewards won</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="fab fa-bitcoin"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount">&#8383; 1.25M+</h3>
                        <p class="stat-label">Rewards Paid</p>
                        <p class="stat-sub">In XRP to our winners</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Get user's timezone
function getUserTimezone() {
    return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

// Function to start the countdown
function startCounter() {
    // Get the target date and server timezone
    const targetDateStr = document.getElementById("counterDateTime").value;
    const serverTimezone = document.getElementById("serverTimezone").value;

    // Get user's timezone
    const userTimezone = getUserTimezone();

    console.log('Server Timezone:', serverTimezone);
    console.log('User Timezone:', userTimezone);

    // Get the HTML elements where the countdown will be displayed
    const counterDays = document.getElementById("counterDays");
    const counterHours = document.getElementById("counterHours");
    const counterMints = document.getElementById("counterMints");
    const counterSeconds = document.getElementById("counterSeconds");

    // Function to update the countdown every second
    const updateCounter = () => {
        // Parse the target date as UTC and get timestamp
        const targetTime = new Date(targetDateStr + ' UTC').getTime();

        // Get the current time in user's timezone
        const currentTime = new Date().getTime();

        // Calculate the time difference in milliseconds
        const timeDifference = targetTime - currentTime;

        // If the target time has passed, display zeros and stop
        if (timeDifference <= 0) {
            counterDays.innerHTML = "00";
            counterHours.innerHTML = "00";
            counterMints.innerHTML = "00";
            counterSeconds.innerHTML = "00";
            return;
        }

        // Calculate days, hours, minutes, and seconds
        const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

        // Add leading zeros for single-digit numbers
        const formattedDays = String(days).padStart(2, "0");
        const formattedHours = String(hours).padStart(2, "0");
        const formattedMinutes = String(minutes).padStart(2, "0");
        const formattedSeconds = String(seconds).padStart(2, "0");

        // Update the countdown display
        counterDays.innerHTML = formattedDays;
        counterHours.innerHTML = formattedHours;
        counterMints.innerHTML = formattedMinutes;
        counterSeconds.innerHTML = formattedSeconds;
    };

    // Update the countdown immediately
    updateCounter();
    // Set an interval to update the countdown every second (1000 milliseconds)
    const counterInterval = setInterval(updateCounter, 1000);
}

// Start the countdown when the page loads
startCounter();
</script>
