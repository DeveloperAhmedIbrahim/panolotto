@php
    $content = getContent('about_us.content', true);
    $elements = getContent('about_us.element', orderById: true);
@endphp
<div class="about-section py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-heading">
                    <h6 class="section-heading__subtitle">{{ __(@$content->data_values->heading) }}</h6>
                    <h2 class="section-heading__title"> {{ __(@$content->data_values->subheading) }} </h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($elements as $element)
                <div class="col-lg-4 col-sm-6">
                    <div class="about-item">
                        <div class="about-item__thumb">
                            <img alt="" src="{{ getImage('assets/images/frontend/about_us/' . $element->data_values->image, '115x115') }}">
                        </div>
                        <h5 class="about-item__title">{{ __($element->data_values->heading) }}</h5>
                        <p class="about-item__desc">{{ __($element->data_values->description) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
