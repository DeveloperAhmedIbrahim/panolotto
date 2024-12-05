@php
    $content = getContent('faq.content', true);
    $elements = getContent('faq.element', limit: 5, orderById: true);
@endphp
<section class="faq-section py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row gy-4 flex-wrap-reverse">
                    <div class="col-lg-5">
                        <div class="faq-left">
                            <h2 class="faq-left__title">{{ __(@$content->data_values->heading) }}</h2>
                            <p class="faq-left__desc">{{ __(@$content->data_values->subheading) }}</p>
                            <div class="faq-left__thumb">
                                <img alt="" src="{{ getImage('assets/images/frontend/faq/' . @$content->data_values->image, '210x180') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ps-lg-5">
                        <div class="accordion custom--accordion" id="accordionExample">
                            @foreach ($elements as $element)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                        <button @if ($loop->first) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="collapse-{{ $loop->iteration }}" class="accordion-button @if (!$loop->first) collapsed @endif" data-bs-target="#collapse-{{ $loop->iteration }}" data-bs-toggle="collapse" type="button">
                                            {{ __($element->data_values->question) }}
                                        </button>
                                    </h2>
                                    <div aria-labelledby="heading-{{ $loop->iteration }}" class="accordion-collapse collapse @if ($loop->first) show @endif" data-bs-parent="#accordionExample" id="collapse-{{ $loop->iteration }}">
                                        <div class="accordion-body">
                                            <p>{{ __($element->data_values->answer) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
