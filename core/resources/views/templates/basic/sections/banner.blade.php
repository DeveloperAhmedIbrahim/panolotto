@php
    $content = getContent('banner.content', true);
@endphp

<section class="banner-section mb-80">
    <div class="banner-section__shape">
        <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/banner-shape.png') }}">
    </div>
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 col-sm-7 col-xsm-8">
                <div class="banner-content">
                    <h2 class="banner-content__title" s-break="-1">{{ __(@$content->data_values->heading) }}</h2>
                    <div class="banner-thumb d-none">
                        {{-- <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}"> --}}
                    </div>
                    <p class="banner-content__desc mt-0">{{ __(@$content->data_values->subheading) }}</p>
                    <input type="hidden" id="counterDateTime" value="{{ $heroCounter->datetime }}">
                    <input type="hidden" id="serverTimezone" value="{{ config('app.timezone') }}">
                    <div class="banner-content__button d-flex align-items-start text-light flex-column mt-0">
                        <div class="fs-1"><b>XRP{{ $heroCounter->price }}M</b></div>
                        <div class="fs-6">Panolotto lets the good times roll.</div>
                    </div>
                    <div class="banner-content__button d-flex align-items-center gap-3 text-light">
                        <div class="p-3 py-2 bg-dark border-2 border-light rounded-2 text-center">
                            <div class="fs-5 fw-bold" id="counterDays">02</div>
                            <div class="fs-6 fw-thin" style="font-size: 14px !important;">Days</div>
                        </div>
                        <span><i class="fas fa-ellipsis-v"></i></span>
                        <div class="p-3 py-2 bg-dark border-2 border-light rounded-2 text-center">
                            <div class="fs-5 fw-bold" id="counterHours">04</div>
                            <div class="fs-6 fw-thin" style="font-size: 14px !important;">Hours</div>
                        </div>
                        <span><i class="fas fa-ellipsis-v"></i></span>
                        <div class="p-3 py-2 bg-dark border-2 border-light rounded-2 text-center">
                            <div class="fs-5 fw-bold" id="counterMints">23</div>
                            <div class="fs-6 fw-thin" style="font-size: 14px !important;">Mints</div>
                        </div>
                    </div>
                    <div class="banner-content__button d-flex align-items-center gap-3">
                        <a class="btn btn--white" href="{{@$content->data_values->button_url }}">{{ __(@$content->data_values->button_text) }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-5 col-xsm-4">
                <div class="banner-right">
                    <div class="banner-thumb">
                        {{-- <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}"> --}}
                    </div>
                </div>
                <div class="banner-thumb d-msm-block d-sm-none">
                    {{-- <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}"> --}}
                </div>
                <div class="banner-shape">
                    {{-- <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/b-1.png') }}"> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<div class="overview-section pb-50">
    <div class="container">
        <div class="row gy-sm-0 gy-4 overview-wrapper wow fadeInUp">
            <div class="col-sm-4 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount text--base">Just 2 XRP!</h3>
                        <p>Play for just 2XRP Per Line</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="las la-trophy"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount text--base">1500XRP</h3>
                        <p>This week's Jackpot</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 overview-item">
                <div class="overview-card">
                    <div class="overview-card__icon">
                        <i class="las la-eye"></i>
                    </div>
                    <div class="overview-card__content">
                        <h3 class="amount text--base">6</h3>
                        <p>Days until draw</p>
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
    };

    // Update the countdown immediately
    updateCounter();
    // Set an interval to update the countdown every second (1000 milliseconds)
    const counterInterval = setInterval(updateCounter, 1000);
}

// Start the countdown when the page loads
startCounter();
</script>