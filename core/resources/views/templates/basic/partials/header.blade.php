<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img alt="" src="{{ siteLogo() }}"></a>
            <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler header-button" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    <li class="nav-item has-dropdown">
                        <a class="nav-link {{ menuActive('lottery.tickets') }}" href="{{ route('lottery.tickets') }}">
                            @lang('Lotteries') <i class="las la-angle-down nav-caret"></i>
                        </a>
                        <ul class="nav-dropdown">
                            @foreach($lotteries as $lottery)
                                @if (@$lottery->activePhase)
                                    <li class="nav-dropdown__item">
                                        <a class="nav-dropdown__link" href="{{ route('lottery.play', ['slug' => slug($lottery->name), 'id' => $lottery->id]) }}">{{ __($lottery->name) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    @php
                        $pages = App\Models\Page::where('is_default', Status::NO)
                            ->where('tempname', $activeTemplate)
                            ->get();
                    @endphp

                    @foreach ($pages as $item)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('pages', param: $item->slug) }}" href="{{ route('pages', ['slug' => $item->slug]) }}">{{ __($item->name) }}</a>
                        </li>
                    @endforeach

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('results') }}" href="{{ route('results') }}">@lang('Results')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="https://www.youtube.com/playlist?list=PLACPz8AooxS1cJ6LLWpufLkad_kFDAYcN">@lang('How It Works')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('about') }}" href="#">@lang('About Us')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('faqs') }}" href="{{ url('faqs') }}">@lang('FAQ')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('contact') }}" href="{{ url('contact') }}">@lang('Contact')</a>
                    </li>
                </ul>

                <div class="top-button flex-between">
                    <ul class="login-registration-list flex-align">
                        <li class="login-registration-list__item">
                            <div class="dropdown account-dropdown">
                                <button class="btn btn-account dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="las la-user-circle"></i>
                                    <span>@lang('Account')</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end account-dropdown__menu">
                                    @guest
                                        <li><a class="dropdown-item" href="{{ route('user.login') }}">@lang('Login')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.register') }}">@lang('Register')</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                                    @endguest
                                </ul>
                            </div>
                        </li>
                        <li class="login-registration-list__item">
                            <a class="btn btn-buy-tickets" href="{{ route('lottery.tickets') }}">@lang('Buy Tickets')</a>
                        </li>
                    </ul>

                    @if (gs()->multi_language)
                        @php
                            $language = App\Models\Language::all();
                            $selectedLang = $language->where('code', config('app.locale'))->first();
                        @endphp
                        <div class="custom--dropdown">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <div class="thumb">
                                    <img alt="image" src="{{ getImage(getFilePath('language') . '/' . @$selectedLang->image, getFileSize('language')) }}">
                                </div>
                            </div>
                            <ul class="dropdown-list">
                                @foreach ($language as $lang)
                                    <li class="dropdown-list__item" data-value="{{ $lang->code }}">
                                        <a class="thumb" href="{{ route('lang', $lang->code) }}">
                                            <img alt="image" src="{{ getImage(getFilePath('language') . '/' . @$lang->image, getFileSize('language')) }}">
                                            <span class="text">{{ strtoupper($lang->code) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</header>
