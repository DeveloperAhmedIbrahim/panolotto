@extends($activeTemplate.'layouts.frontend')
<style>
    .zegoContainer {
        width: 100% !important;
        height: 500px !important;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
    }

    .sCsSbKP9yxvw4LQAeaTz {
        min-width: 100% !important;
    }

</style>
<section class="inner-hero overlay--one">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title text-white">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
@section('content')
    @if($pageTitle == 'Live Draw')
        <div class="card-body p-0 mb-5">
            <div id="zegoContainer" class="zegoContainer"></div>
            @auth
                <input type="hidden" name="username" id="username" value="{{Auth::user()->name}}" />
            @endauth
        </div>
        <div class="container mt-5" >

        </div>
    @else
        @if($sections != null)
            @foreach(json_decode($sections) as $sec)
                @include($activeTemplate.'sections.'.$sec)
            @endforeach
        @endif
    @endif
@endsection
