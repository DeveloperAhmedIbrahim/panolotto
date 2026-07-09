@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('admin/live-stream/stream') }}" method="get">
                        <div class="row align-items-center">
                            <div class="col-md-6 py-2">
                                <select name="lottery" id="lottery" class="form-control">
                                    @foreach($phases as $phase)
                                        <option value="Live Draw: {{ __(@$phase->lottery->name) }} {{ __(showPhase($phase->phase_no)) }}">
                                            <span class="fw-bold">{{ __(@$phase->lottery->name) }}</span><br>
                                            <span>{{ __(showPhase($phase->phase_no)) }}</span>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 py-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Go To Stream 
                                </button>
                                <a href="{{ url('admin/live-stream/status') }}?status={{ $stream->status === 0 ? "activate" : "deactivate" }}" class="btn btn-{{ $stream->status === 0 ? "success" : "warning" }} btn-lg">
                                    {{ $stream->status === 0 ? "Activate" : "Deactivate" }} Stream
                                </a>
                                <a href="{{ url('admin/live-stream/update') }}" class="btn btn-info btn-lg">
                                    Update Streams
                                </a>                                                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
