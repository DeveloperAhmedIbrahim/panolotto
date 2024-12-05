<li class="ticket" data-ticket_number="{{ $lotteryNumber }}">
    <div class="lottery">
        <div class="lottery__head">
            <span class="lottery__head-title ">
                @lang('Pick') {{ $lottery->total_picking_ball }} @lang('Number')
            </span>
            <ul class="list list--row d-flex justify-content-center" style="--gap: .5rem;">
                <li>
                    <button class="btn lottery-btn quick_pick quickPickBtn" type="button">
                        <span class="lottery-btn__icon">
                            <i class="las la-magic"></i>
                        </span>
                        <span class="lottery-btn__text">
                            @lang('Quick Pick')
                        </span>
                    </button>
                </li>
                <li>
                    <button class="btn lottery-btn clearBtn" type="button">
                        <span class="lottery-btn__icon">
                            <i class="las la-trash-alt"></i>
                        </span>
                        <span class="lottery-btn__text">
                            @lang('Clear All')
                        </span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="lottery__body">
            <span class="lottery__number lotteryNumber">{{ $lotteryNumber }}</span>
            <ul class="list--row flex-wrap lottery__list nbBallList">

                @for ($i = $lottery->ball_start_from; $i < $normalBallLimit; $i++)
                    <li class="normalBallNo-{{ $i }}">
                        <button class="lottery__btn normalBtn" data-no="{{ $i }}">
                            {{ $i }}
                        </button>
                    </li>
                @endfor
            </ul>
        </div>
        @if ($lottery->has_power_ball == Status::YES)
            <div class="lottery__footer">
                <span class="lottery__head-title mb-2">
                    @lang('Pick') {{ $lottery->total_picking_power_ball }} @lang('Number')
                </span>
                <ul class="list--row flex-wrap lottery__list pwBallList">
                    @for ($i = $lottery->pw_ball_start_from; $i < $pwBallLimit; $i++)
                        <li class="btnNo-{{ $i }}">
                            <button class="lottery__btn powerBtn" data-no="{{ $i }}">
                                {{ $i }}
                            </button>
                        </li>
                    @endfor
                </ul>
            </div>
        @endif
    </div>
</li>
