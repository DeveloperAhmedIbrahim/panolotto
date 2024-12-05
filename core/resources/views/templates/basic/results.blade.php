@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Lottery')</th>
                                <th>@lang('Draw At')</th>
                                <th>@lang('Winning Numbers')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                                <tr>
                                    <td data-label="Lottery">
                                        <div class="user">
                                            <div class="d-flex align-items-center">
                                                <div class="user__img user__img--md">
                                                    <img alt="image" class="user__img-is" src="{{ getImage(getFilePath('lottery') . '/' . $result->lottery->image, getFileSize('lottery')) }}">
                                                </div>
                                                <div class="user__content">
                                                    <h6 class="m-0 title">{{ __($result->lottery->name) }}</h6>
                                                    <p class="m-0 sm-text text-clr">
                                                        {{ showAmount($result->lottery->maxPrize()) }} {{ gs()->cur_text }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Draw At">
                                        <p class="m-0 sm-text text-clr">{{ showDateTime($result->draw_at, 'd M, Y H:i A') }}</p>
                                    </td>
                                    <td data-label="Winning Numbers">
                                        <ul class="list list--row flex-wrap justify-content-end gap-1">
                                            @foreach ($result->winning_normal_balls as $winningNormalBall)
                                                <li>
                                                    <span class="result-card__number result-card__number--light">{{ $winningNormalBall }}</span>
                                                </li>
                                            @endforeach
                                            @foreach ($result->winning_power_balls as $winningPowerBall)
                                                <li>
                                                    <span class="result-card__number result-card__number--light active">{{ $winningPowerBall }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="3">
                                        @lang('There is no results found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($results->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ paginateLinks($results) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
