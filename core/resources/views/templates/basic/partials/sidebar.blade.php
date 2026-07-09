<div class="dashboard-sidebar" id="dashboard-sidebar">
    <button class="btn-close dash-sidebar-close d-xl-none"></button>
    <a class="logo" href="{{ route('home') }}"><img alt="images" src="{{ siteLogo('dark') }}"></a>
    <div class="bg--lights">
        <div class="profile-info">
            <p class="fs--13px mb-3 fw-bold">@lang('ACCOUNT BALANCE')</p>
            <h4 class="usd-balance text--base mb-2 fs--30">{{ showAmount(auth()->user()->balance, currencyFormat:false) }} <sub class="top-0 fs--13px">{{ __(gs()->cur_text) }}</sub></h4>
            <div class="mt-4 d-flex flex-wrap gap-1">
                <a class="btn btn--base btn--smd" href="{{ route('user.deposit.index') }}">@lang('Purchase') {{__(gs('cur_text'))}}</a>
                <a class="btn btn--secondary btn--smd" href="{{ route('user.cashout')}}">{{withrdawKeyword()}}</a>
            </div>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li><a class="{{ menuActive('user.home') }}" href="{{ route('user.home') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/dashboard.png') }}"> @lang('Dashboard')</a></li>
        <li>
            <a class="{{ menuActive('user.lottery.draw.pending') }}" href="{{ route('user.lottery.draw.pending') }}">
                <img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/pending_draw.png') }}">@lang('Pending Draw')
            </a>
        </li>
        <li>
            <a class="{{ menuActive('user.lottery.purchase.history') }}" href="{{ route('user.lottery.purchase.history') }}">
                <img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/purchase.png') }}">@lang('Purchase Ticket')
            </a>
        </li>
        <li>
            <a class="{{ menuActive('user.lottery.winning.history') }}" href="{{ route('user.lottery.winning.history') }}">
                <img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/winning.png') }}">@lang('Winning History')
            </a>
        </li>


        <li><a class="{{ menuActive('user.deposit*') }}" href="{{ route('user.deposit.index') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/wallet.png') }}"> @lang('Purchase') {{__(gs('cur_text'))}}</a></li>

        <li><a class="{{ menuActive('user.cashout*') }}" href="{{ route('user.cashout') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/withdraw.png') }}">
            {{withrdawKeyword()}}</a></li>
        <li>
            <a class="{{ menuActive('user.transactions') }}" href="{{ route('user.transactions') }}">
                <img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/transaction.png') }}"> @lang('Transactions')
            </a>
        </li>
        <li><a class="{{ menuActive('user.referrals') }}" href="{{ route('user.referrals') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/referral.png') }}"> @lang('My Referrals')</a></li>
        <li><a class="{{ menuActive(['ticket.index', 'ticket.view', 'ticket.open']) }}" href="{{ route('ticket.index') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/ticket.png') }}"> @lang('Support Ticket')</a></li>
        <li><a class="{{ menuActive('user.twofactor') }}" href="{{ route('user.twofactor') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/2fa.png') }}"> @lang('2FA Security')</a></li>
        <li><a class="{{ menuActive('user.profile.setting') }}" href="{{ route('user.profile.setting') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/profile.png') }}"> @lang('My Profile')</a></li>
        <li><a class="{{ menuActive('user.change.password') }}" href="{{ route('user.change.password') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/password.png') }}"> @lang('Change Password')</a></li>
        <li><a class="{{ menuActive('user.logout') }}" href="{{ route('user.logout') }}"><img alt="icon" src="{{ asset($activeTemplateTrue . '/images/icon/logout.png') }}"> @lang('Logout')</a></li>
    </ul>
</div>
