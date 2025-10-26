@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-deposit text-center">
                    <div class="card-header card-header-bg">
                        <h3>@lang('Payment Preview')</h3>
                    </div>
                    <div class="card-body card-body-deposit text-center">
                        <!-- Status Badge -->
                        <div id="statusBadge" class="mb-3">
                            @if($deposit->status === 0) 
                                <span class="badge badge-warning badge-lg" style="color: lightslategray; font-size: 16px; padding: 10px 20px;">
                                    <i class="fa fa-clock"></i> Waiting...
                                </span>
                            @elseif($deposit->status === 2)
                                <span class="badge badge-success badge-lg" style="color: coral; font-size: 16px; padding: 10px 20px;">
                                    <i class="fa fa-clock"></i> Pending...
                                </span>
                            @endif
                        </div>

                        <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text--success"> {{ $data->amount }}</span> {{__($data->currency)}}</h4>
                        <h5 class="mb-2">@lang('TO') <span class="text--success"> {{ $data->sendto }}</span></h5>
                        <h5 class="mb-2">@lang('Destination Tag') <span class="text--success"> {{ $data->destination_tag }}</span></h5>
                        <img src="{{$data->img}}" alt="@lang('Image')">
                        <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script-lib')
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <script>
        const depositId = {{ $deposit->id ?? 'null' }};
        let statusCheckInterval;

        function checkDepositStatus() {
            fetch(`{{ route('user.deposit.status', $deposit->id) }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 1) {
                        // Deposit successful
                        clearInterval(statusCheckInterval);
                        
                        // Update UI to show success
                        document.getElementById('statusBadge').innerHTML = `
                            <span class="badge badge-success badge-lg" style="color: #28C870; font-size: 16px; padding: 10px 20px;">
                                <i class="fa fa-check-circle"></i> Confirmed
                            </span>
                        `;
                        
                        // Show beautiful success popup
                        Swal.fire({
                            icon: 'success',
                            title: 'Deposit Successful!',
                            text: 'Your deposit has been confirmed successfully.',
                            confirmButtonText: 'Go to Dashboard',
                            confirmButtonColor: '#28a745',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('user.home') }}";
                            }
                        });
                    }
                    else if(data.status === 2) {
                        document.getElementById('statusBadge').innerHTML = `
                            <span class="badge badge-success badge-lg" style="color: coral; font-size: 16px; padding: 10px 20px;">
                                <i class="fa fa-clock"></i> Pending...
                            </span>
                        `;                        
                    }
                    // If status is 2, it's pending - UI already shows pending, no action needed
                })
                .catch(error => {
                    console.error('Error checking deposit status:', error);
                });
        }

        // Start checking status every 2 seconds
        if (depositId) {
            statusCheckInterval = setInterval(checkDepositStatus, 2000);
        }

        // Clean up interval when page is closed
        window.addEventListener('beforeunload', function() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
        });
    </script>
@endsection