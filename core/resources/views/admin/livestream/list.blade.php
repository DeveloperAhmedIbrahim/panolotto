@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Lottery') | @lang('Phase No')</th>
                                    <th>@lang('Draw Date')</th>
                                    <th>@lang('Video')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recordings as $recording)
                                    <tr>
                                        <td>{{ $recording->title }}</td>
                                        <td>{{ showDateTime($recording->created_at, 'd M, Y h:i A') }}</td>
                                        <td>
                                            @foreach(json_decode($recording->videos) as $video)
                                                <a href="{{$video}}" target="_blank">Watch</a>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.livestream.delete', $recording->id) }}" class="btn btn-sm btn-outline--danger"><i class="las la-trash-alt"></i>@lang('Delete')</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
