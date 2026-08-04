@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="policy-section py-80">
        <div class="container">
            @php
                echo $policy->data_values->details;
            @endphp
        </div>
    </div>
@endsection
