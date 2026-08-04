@php
    $content = getContent('cta.content', true);
@endphp
<div class="cta-section py-80">
    <div class="container">
        <a class="cta-section__bg" href="{{ $content->data_values->url }}">
            <img alt="" src="{{ getImage('assets/images/frontend/cta/' . $content->data_values->image, '1340x400') }}">
        </a>
    </div>
</div>