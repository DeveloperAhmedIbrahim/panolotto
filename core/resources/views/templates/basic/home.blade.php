@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @include($activeTemplate.'sections.banner')
    <section class="how_work_section" style="background: #f9f6f0; padding: 80px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-header text-center mb-5">
                        <span style="color: #f5a623; font-weight: 600; font-size: 15px;">Simple Steps</span>
                        <h2 class="section-title mt-2" style="font-size: 2.2rem; font-weight: 800; color: #1a1a1a;">How Panolotto Works</h2>
                        <p class="mt-3" style="color: #555;">Get started in just a few easy steps and win big.</p>
                    </div>
                </div>
            </div>
            <div class="row gy-4">

                {{-- Step 1: Create Account --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div style="
                            min-width: 80px; width: 80px; height: 80px; border-radius: 50%;
                            background: #f5e6c0;
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#d4891a" viewBox="0 0 24 24">
                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 5px;">1. Create Account</h3>
                            <p style="color: #777; font-size: 0.85rem; line-height: 1.5; margin: 0;">Sign up for free and set up your wallet.</p>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Buy Tickets --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div style="
                            min-width: 80px; width: 80px; height: 80px; border-radius: 50%;
                            background: #f5e6c0;
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#d4891a" viewBox="0 0 24 24">
                                <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c1.1 0 2 .9 2 2s-.9 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-1.46c-1.19.69-2 1.99-2 3.46s.81 2.77 2 3.46V18H4v-2.54c1.19-.69 2-1.99 2-3.46 0-1.48-.8-2.77-2-3.46V6h16v2.54zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 5px;">2. Buy Tickets</h3>
                            <p style="color: #777; font-size: 0.85rem; line-height: 1.5; margin: 0;">Choose your lucky numbers and pay with XRP.</p>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Watch the Draw --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div style="
                            min-width: 80px; width: 80px; height: 80px; border-radius: 50%;
                            background: #f5e6c0;
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#d4891a" viewBox="0 0 24 24">
                                <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 5px;">3. Watch the Draw</h3>
                            <p style="color: #777; font-size: 0.85rem; line-height: 1.5; margin: 0;">Join the live draw and see if you're a winner.</p>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Win & Get Paid --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div style="
                            min-width: 80px; width: 80px; height: 80px; border-radius: 50%;
                            background: #f5e6c0;
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#d4891a" viewBox="0 0 24 24">
                                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 5px;">4. Win & Get Paid</h3>
                            <p style="color: #777; font-size: 0.85rem; line-height: 1.5; margin: 0;">Winnings are sent directly to your wallet in XRP.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include($activeTemplate.'sections.testimonial')
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
