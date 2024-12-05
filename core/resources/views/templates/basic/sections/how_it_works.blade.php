@php
    $content = getContent('how_it_works.content', true);
    $elements = getContent('how_it_works.element', orderById: true);
@endphp

<section class="how-to-work-section py-80 my-80">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 pe-lg-5">
                <div class="how-to-work-section__left">
                    <h2 class="how-to-work-section__title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="how-to-work-section__desc">{{ __(@$content->data_values->subheading) }}</p>
                    <div class="how-to-work-section__button">
                        <a class="btn btn--base" href="{{ __(@$content->data_values->button_url) }}">{{ __(@$content->data_values->button_text) }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 ps-lg-5">
                <div class="row gy-4 justify-content-center how-to-work-wrapper">
                    @foreach ($elements as $item)
                        <div class="col-sm-6">
                            <div class="how-to-work">
                                <span class="how-to-work__number">{{ $loop->iteration }}</span>
                                <div class="how-to-work__thumb">
                                    <img alt="" src="{{ getImage('assets/images/frontend/how_it_works/' . $item->data_values->image, '80x80') }}">
                                </div>
                                <h5 class="how-to-work__title">{{ __($item->data_values->title) }}</h5>
                                <p class="how-to-work__desc">{{ __($item->data_values->description) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
