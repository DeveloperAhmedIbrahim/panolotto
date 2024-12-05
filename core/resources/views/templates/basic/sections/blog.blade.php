@php
    $content = getContent('blog.content', true);
    $blogs = getContent('blog.element', limit: 3);
@endphp
<section class="blog py-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading flex-between">
                    <h2 class="section-heading__title">{{ __(@$content->data_values->heading) }}</h2>
                    <a class="btn btn--base" href="{{ @$content->data_values->button_url }}"> {{ __(@$content->data_values->button_text) }} <span class="icon"><i class="fas fa-arrow-right"></i></span> </a>
                </div>
            </div>
        </div>

        @include($activeTemplate . 'partials.blog_cards', ['blogs' => $blogs])
    </div>
</section>
