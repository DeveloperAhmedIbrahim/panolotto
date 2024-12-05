@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row">
            <div class="col-12">
                @include($activeTemplate . 'partials.winning_table', ['winners' => $winners, 'pagination' => true])
            </div>
        </div>
    </div>
@endsection