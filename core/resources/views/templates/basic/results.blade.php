@extends($activeTemplate . 'layouts.frontend')
<section class="inner-hero overlay--one">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title text-white">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
@section('content')
    <div class="pb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Lottery')</th>
                                <th>@lang('Draw ID')</th>
                                <th>@lang('Date & Time')</th>
                                <th>@lang('Winning Numbers')</th>
                                <th>@lang('Winning Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $index => $result)
                                <tr>
                                    <td data-label="Lottery">
                                        <div class="user">
                                            <div class="d-flex align-items-center">
                                                <div class="user__img user__img--md">
                                                    <img alt="image" class="user__img-is" src="{{ getImage(getFilePath('lottery') . '/' . $result->lottery->image, getFileSize('lottery')) }}">
                                                </div>
                                                <div class="user__content">
                                                    <h6 class="m-0 title">{{ __($result->lottery->name) }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Draw ID">
                                        {{ "#$result->id" }}
                                    </td>
                                    <td data-label="Date & Time">
                                        <p class="m-0 sm-text text-clr">{{ showDateTime($result->draw_at, 'd M, Y H:i A') }}</p>
                                    </td>
                                    <td data-label="Winning Numbers">
                                        <x-winning-numbers :index=$index :result=$result />
                                    </td>
                                    <td data-label="Winning Amount">
                                        {{ showAmount($result->lottery->maxPrize()) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">
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
