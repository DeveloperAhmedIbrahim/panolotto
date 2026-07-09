@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-detials py-70">
        <div class="container">
            <div class="row gy-5 justify-content-center ">
                <div class="col-xl-9 col-lg-8">
                    <div class="blog-details">
                        <div class="blog-details__thumb">
                            <img alt="" src="{{ getImage('assets/images/frontend/blog/' . $blog->data_values->image, '860x460') }}">
                        </div>
                        <div class="blog-details__content">
                            <span class="blog-item__time mb-2"><span class="blog-item__date-icon"><i class="las la-clock"></i></span> {{ showDateTime($blog->created_at, 'd M Y') }}</span>
                            <h3 class="blog-details__title"> {{ __($blog->data_values->title) }}</h3>
                            <div class="blog-details__desc">
                                @php
                                    echo $blog->data_values->description;
                                @endphp
                            </div>
                        </div>
                    </div>
                    <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" data-numposts="5"></div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="blog-sidebar-wrapper">
                        <div class="blog-sidebar">
                            <h5 class="blog-sidebar__title">@lang('Latest Blog')</h5>
                            @foreach ($latestBlogs as $latestBlog)
                                <div class="latest-blog">
                                    <div class="latest-blog__thumb">
                                        <a href="{{ route('blog.details', ['slug' => slug($latestBlog->data_values->title), 'id' => $latestBlog->id]) }}"> <img alt="" src="{{ getImage('assets/images/frontend/blog/thumb_' . $latestBlog->data_values->image, '430x230') }}"></a>
                                    </div>
                                    <div class="latest-blog__content">
                                        <h6 class="latest-blog__title"><a href="{{ route('blog.details', ['slug' => slug($latestBlog->data_values->title), 'id' => $latestBlog->id]) }}">{{ __($latestBlog->data_values->title) }}</a></h6>
                                        <span class="latest-blog__date">{{ showDateTime($latestBlog->created_at, 'd M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- ============================= Blog Details Sidebar End ======================== -->
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
