<!doctype html>
<html itemscope itemtype="http://schema.org/WebPage" lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet">

    <link href="{{ asset($activeTemplateTrue . 'css/owl-theme.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/owl-main.css') }}" rel="stylesheet">

    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    @stack('style-lib')
    @stack('style')
    <link
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?base_color={{ gs()->base_color }}&secondary_color={{ gs()->secondary_color }}"
        rel="stylesheet" type="text/css">
</head>

@php echo  loadExtension('google-analytics') @endphp

<body>
    @stack('fbComment')

    <div class="preloader">
        <div class="loader-p"></div>
    </div>
    <div class="body-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @yield('panel')

    @if (
        !request()->routeIs('user.login') &&
            !request()->routeIs('user.register') &&
            gs('request_for_coin') &&
            !gs('maintenance_mode'))
        @include($activeTemplate . 'partials.request_coin')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/owl-filter.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl-main.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        
        $.each($('input, select, textarea'), function(i, element) {
            var elementType = $(element);
            if (elementType.attr('type') != 'checkbox') {
                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            }
        });

        var inputElements = $('[type=text],[type=password],[type=email],[type=number],select,textarea');
        $.each(inputElements, function(index, element) {
            element = $(element);
            element.closest('.form-group').find('label').attr('for', element.attr('name'));
            element.attr('id', element.attr('name'))
        });

        let disableSubmission = false;
        $('.disableSubmission').on('submit', function(e) {

            if (disableSubmission) {
                e.preventDefault()
            } else {
                disableSubmission = true;
            }
        });
    </script>
</body>

</html>
