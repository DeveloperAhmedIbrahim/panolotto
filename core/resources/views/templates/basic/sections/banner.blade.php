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
                        <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}">
                    </div>
                    <p class="banner-content__desc">{{ __(@$content->data_values->subheading) }}</p>
                    <div class="banner-content__button d-flex align-items-center gap-3">
                        <a class="btn btn--white" href="{{@$content->data_values->button_url }}">{{ __(@$content->data_values->button_text) }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-5 col-xsm-4">
                <div class="banner-right">
                    <div class="banner-thumb">
                        <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}">
                    </div>
                </div>
                <div class="banner-thumb d-msm-block d-sm-none">
                    <img alt="" src="{{ getImage('assets/images/frontend/banner/'.@$content->data_values->image, '825x680') }}">
                </div>
                <div class="banner-shape">
                    <img alt="" src="{{ getImage($activeTemplateTrue . 'images/shapes/b-1.png') }}">
                </div>
            </div>
        </div>
    </div>
</section>
