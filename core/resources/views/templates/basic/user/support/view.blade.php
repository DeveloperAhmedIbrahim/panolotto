@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
    <div class="@if ($layout == 'frontend') py-80 section-bg @else dashboard-inner @endif">
        @if ($layout == 'frontend')
            <div class="container">
        @endif
        <div class="text-end mb-3 d-flex flex-wrap justify-content-between gap-1">
            <h3>{{ __($pageTitle) }}</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <h5>
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        @if ($myTicket->status != 3 && $myTicket->user)
                            <button class="btn btn--danger btn--sm confirmationBtn" data-action="{{ route('ticket.close', $myTicket->id) }}" data-question="@lang('Are you sure to close this ticket?')" type="button"><i class="fa fa-lg fa-times-circle"></i>
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($myTicket->status != 4)
                            <form action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control form--control" rows="4" placeholder="@lang('Message..')" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <button type="button" class="btn btn--dark btn--sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row fileUploadsContainer">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn--base w-100 my-2" type="submit"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        @forelse ($messages as $message)
                            @if ($message->admin_id == 0)
                                <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                    <div class="col-md-3 border-end text-end">
                                        <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}
                                        </p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment')
                                                        {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row border border-warning border-radius-3 my-3 py-3 mx-2" style="background-color: #ffd96729">
                                    <div class="col-md-3 border-end text-end">
                                        <h5 class="my-3">{{ $message->admin->name }}</h5>
                                        <p class="lead text-muted">@lang('Staff')</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on')
                                            {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment')
                                                        {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="empty-message text-center">
                                <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                                <h5 class="text-muted">@lang('No replies found here!')</h5>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
        @if ($layout == 'frontend')
    </div>
    @endif
    </div>
    <x-confirmation-modal isCustom="true" />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group attachment">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
