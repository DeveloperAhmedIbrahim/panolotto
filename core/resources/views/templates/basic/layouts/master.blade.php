<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <!-- font  -->
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,400;1,500&family=Maven+Pro:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet">

    <link href="{{ asset($activeTemplateTrue . 'css/user/animate.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/user/main.css') }}" rel="stylesheet">

    @stack('style-lib')

    <link href="{{ asset($activeTemplateTrue . 'css/user/custom.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/color.php') }}?base_color={{ gs()->base_color }}&secondary_color={{ gs()->secondary_color }}" rel="stylesheet" type="text/css">

    @stack('style')
    <style>
        .pb-120 {
            padding-bottom: clamp(40px, 4vw, 40px);
        }

        .pt-120 {
            padding-top: clamp(40px, 4vw, 40px);
        }

        .container {
            max-width: 1140px;
        }
    </style>

</head>

@php echo  loadExtension('google-analytics') @endphp

<body>
    <div class="d-flex flex-wrap">
        @include($activeTemplate . 'partials.sidebar')
        <div class="dashboard-wrapper">
            @include($activeTemplate . 'partials.topbar')

            <div class="dashboard-container">
                @yield('content')
            </div>
        </div>
    </div>

    @if (gs('request_for_coin'))
        @include($activeTemplate . 'partials.request_coin')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Main js -->
    <script src="{{ asset($activeTemplateTrue . 'js/user/main.js') }}"></script>

    @stack('script-lib')

    @include('partials.notify')

    @php echo  loadExtension('tawk-chat') @endphp

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        $(".langSel").on("change", function() {
            window.location.href = "{{ route('home') }}/change/" + $(this).val();
        });

        Array.from(document.querySelectorAll('table')).forEach(table => {
            let heading = table.querySelectorAll('thead tr th');
            Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                    colum.setAttribute('data-label', heading[i].innerText)
                });
            });
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
