@php
    $content = getContent('testimonial.content', true);
    $elements = getContent('testimonial.element');
@endphp
<section class="testimonials py-80">
    <!-- owl slider start  -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="section-heading">
                    <h2 class="section-heading__title">
                        {{ __(@$content->data_values->heading) }}
                    </h2>
                    <p class="section-heading__desc"> {{ __(@$content->data_values->subheading) }} </p>
                </div>
            </div>
            <div class="col-lg-12 px-0">
                <div class="testimonial owl-carousel">
                    @foreach ($elements as $item)
                        <div class="testimonials-card">
                            <div class="testimonial-item">
                                <div class="testimonial-item__rating">
                                    <ul class="rating-list">
                                        <li class="rating-list__item">@php echo showStars($item->data_values->star) @endphp</li>
                                    </ul>
                                </div>
                                <p class="testimonial-item__desc">{{ __($item->data_values->statement) }}</p>
                                <div class="testimonial-item__info">
                                    <div class="testimonial-item__thumb">
                                        <img alt="" src="{{ getImage('assets/images/frontend/testimonial/' . $item->data_values->image, '30x30') }}">
                                    </div>
                                    <div class="testimonial-item__details">
                                        <h5 class="testimonial-item__name">{{ __($item->data_values->name) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
