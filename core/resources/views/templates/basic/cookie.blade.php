@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="policy-section py-80">
        <div class="container">
            @php
                echo $cookie->data_values->description;
            @endphp
        </div>
    </div>
@endsection
