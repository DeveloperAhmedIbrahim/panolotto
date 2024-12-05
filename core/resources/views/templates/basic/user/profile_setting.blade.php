@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="mb-4">
            <h4 class="mb-2">@lang('My Profile')</h4>
        </div>
        <div class="card custom--card">
            <div class="card-body">
                <form action="{{ route('user.profile.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="form-label">@lang('Image')</label>
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview d-flex justify-content-center">
                                            <div class="profilePicPreview" style="background-image: url( {{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }})">
                                                <label class="edit-btn" for="profilePicUpload1"><i class="las la-pen"></i></label>
                                            </div>
                                        </div>

                                        <div class="avatar-edit">
                                            <input accept=".png, .jpg, .jpeg" class="profilePicUpload" hidden id="profilePicUpload1" name="image" type="file">
                                            <small class="mt-2  ">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b> @lang('Image will be resized into') {{ getFileSize('userProfile') }}@lang('px') </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('First Name')</label>
                                        <input class="form-control form--control" name="firstname" required type="text" value="{{ $user->firstname }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Last Name')</label>
                                        <input class="form-control form--control" name="lastname" required type="text" value="{{ $user->lastname }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Username')</label>
                                        <input class="form-control form--control" disabled type="text" value="{{ $user->username }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Mobile')</label>
                                        <input class="form-control form--control" disabled type="text" value="{{ $user->mobile }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Email')</label>
                                        <input class="form-control form--control" disabled type="text" value="{{ $user->email }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Address')</label>
                                        <input class="form-control form--control" name="address" type="text" value="{{ $user->address }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('State')</label>
                                        <input class="form-control form--control" name="state" type="text" value="{{ $user->state }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Zip Code')</label>
                                        <input class="form-control form--control" name="zip" type="text" value="{{ $user->zip }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('City')</label>
                                        <input class="form-control form--control" name="city" type="text" value="{{ $user->city }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Country')</label>
                                        <input class="form-control form--control" disabled type="text" value="{{ $user->country_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.thumb').find('.profilePicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".profilePicUpload").on('change', function() {
                proPicURL(this);
            });

            $(".remove-image").on('click', function() {
                $(this).parents(".profilePicPreview").css('background-image', 'none');
                $(this).parents(".profilePicPreview").removeClass('has-image');
                $(this).parents(".thumb").find('input[type=file]').val('');
            });
        })(jQuery);
    </script>
@endpush