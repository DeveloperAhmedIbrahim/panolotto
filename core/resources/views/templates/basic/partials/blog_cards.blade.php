<div class="row gy-4 justify-content-center">
    @foreach ($blogs as $blog)
        @php
            $url = route('blog.details', $blog->slug);
        @endphp
        <div class="col-lg-4 col-xsm-6">
            <div class="blog-item">
                <div class="blog-item__thumb">
                    <a class="blog-item__thumb-link" href="{{ $url }}">
                        <img alt="" src="{{ getImage('assets/images/frontend/blog/thumb_' . $blog->data_values->image, '430x230') }}">
                    </a>
                </div>
                <div class="blog-item__content">
                    <h5 class="blog-item__title"><a class="blog-item__title-link border-effect" href="{{ $url }}">{{ __($blog->data_values->title) }}</a></h5>
                    <p class="blog-item__desc">@php echo strLimit(strip_tags($blog->data_values->description), 140) @endphp</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a class="blog-item__btn" href="{{ $url }}">@lang('Read More')<span class=" blog-item__icon"><i class="fas fa-arrow-right"></i></span></a>
                        <span class="blog-item__date">{{ showDateTime($blog->created_at, 'd M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
