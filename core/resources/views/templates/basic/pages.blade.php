@extends($activeTemplate . 'layouts.frontend')
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
    @if ($pageTitle == 'Live Draw')

        @if ($stream->status == 1)
            <div id="streamContainer" class="container" style="width: 100%; height: 80vh;">

            </div>
            <script src="https://sdk.videosdk.live/rtc-js-prebuilt/0.3.43/rtc-js-prebuilt.js"></script>

            <script>
                const meeting = new VideoSDKMeeting();

                meeting.init({
                    //parameters
                    apiKey: "382f472d-a53d-4594-8247-6c92bc3745bd",
                    meetingId: "DS7A9S8",
                    name: '',

                    containerId: "streamContainer",

                    micEnabled: false,
                    webcamEnabled: false,
                    participantCanToggleSelfWebcam: false,
                    participantCanToggleSelfMic: false,
                    participantCanLeave: true, // if false, leave button won't be visible

                    chatEnabled: true,
                    screenShareEnabled: false,
                    pollEnabled: false,
                    whiteboardEnabled: false,
                    raiseHandEnabled: true,
                    mode: "VIEWER", // VIEWER || CONFERENCE

                    layout: {
                        type: "SPOTLIGHT", // "SPOTLIGHT" | "SIDEBAR" | "GRID"
                        priority: "SPEAKER", // "SPEAKER" | "PIN",
                        // gridSize: 3,
                    },

                    branding: {
                        enabled: true,
                        logoURL: "{{ url('assets/images/logo_icon/favicon.png') }}",
                        name: "{{ $stream->title }}",
                        poweredBy: false,
                    },

                    permissions: {
                        pin: true,
                        askToJoin: false, // Ask joined participants for entry in meeting
                        toggleParticipantMic: false, // Can toggle other participant's mic
                        toggleParticipantWebcam: false, // Can toggle other participant's webcam
                        toggleParticipantScreenshare: false, // Can toggle other participant's screen share
                        toggleParticipantMode: false, // Can toggle other participant's mode
                        canCreatePoll: false, // Can create a poll
                        toggleHls: false, // Can toggle Start HLS button
                        drawOnWhiteboard: false, // Can draw on whiteboard
                        toggleWhiteboard: false, // Can toggle whiteboard
                        toggleVirtualBackground: false, // Can toggle virtual background
                        toggleRecording: false, // Can toggle meeting recording
                        toggleLivestream: false, //can toggle live stream
                        removeParticipant: false, // Can remove participant
                        endMeeting: false, // Can end meeting
                        changeLayout: false, //can change layout
                    },

                    joinScreen: {
                        visible: true, // Show the join screen ?
                        title: "{{ $stream->title }}", // Meeting title
                        meetingUrl: "{{ url('livedraw') }}", // Meeting joining url
                    },

                    notificationSoundEnabled: true,

                    debug: true, // pop up error during invalid config or netwrok error

                    maxResolution: "hd", // "hd" or "sd"            
                });
            </script>
        @else
            <div class="container"
                style="display: flex; justify-content: center; align-items: center; border: 4px solid lightslategray; height: 200px; border-radius: 15px;">
                <h4>No Live Stream Right Now!</h4>
            </div>
        @endif
        <div class="container my-5">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Draw Name')</th>
                        <th>@lang('Draw Date')</th>
                        <th class="text-center">@lang('Watch')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($streams as $stream)
                        <tr>
                            <td>
                                {{ $stream->title }}
                            </td>
                            <td>
                                {{ showDateTime($stream->created_at, 'd M, Y h:i A') }}
                            </td>

                            <td class="text-center">
                                @foreach(json_decode($stream->videos) as $video)
                                    <a href="{{$video}}" target="_blank">Video</a>
                                @endforeach
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @if ($sections != null)
            @foreach (json_decode($sections) as $sec)
                @include($activeTemplate . 'sections.' . $sec)
            @endforeach
        @endif
    @endif
@endsection
