{{-- modal-- --}}
<div class="modal fade" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Cron Job Setting Instruction')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center border-bottom mb-4">
                            <div class="text--primary">
                                <i class="las la-info-circle"></i>
                                @lang('Set the Cron time ASAP')
                            </div>
                            <p class="fst-italic">
                                @lang('Once per 5-15 minutes is ideal while once every minute is the best option')
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>@lang('Cron Command')</label>
                        <div class="input-group">
                            <input type="text" class="form-control copyText" value="curl -s {{ route('cron') }}" readonly>
                            <button class="input-group-text btn--primary copyBtn border-0"> @lang('COPY')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        'use strict';
        $(document).ready(function(e) {
            setTimeout(() => {
                $('#cronModal').modal('show');
            }, 1000);

            $('.copyBtn').on('click', function() {
                var copyText = $(this).siblings('.copyText')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                $(this).addClass('copied');
                setTimeout(() => {
                    $(this).removeClass('copied');
                }, 1500);
            });
        });
    </script>
@endpush
