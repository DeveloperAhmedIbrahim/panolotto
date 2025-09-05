@extends($activeTemplate.'layouts.frontend')
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
    @if($pageTitle == 'Live Draw')
        <div id="streamContainer" class="container" style="width: 100%; height: 80vh;">

        </div>
    <script src="https://sdk.videosdk.live/rtc-js-prebuilt/0.3.43/rtc-js-prebuilt.js"></script>

    <script>
        const meeting = new VideoSDKMeeting();

        meeting.init({
            //parameters
            apiKey: "382f472d-a53d-4594-8247-6c92bc3745bd",
            meetingId: "DS7A9S8",
            name: "{{ auth()->guard('admin')->user()->name }}",

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
                name: "Main Draw 11 Phase#17",
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

            // joinScreen: {
            //     visible: true, // Show the join screen ?
            //     title: "Main Draw 11 Phase#17", // Meeting title
            //     meetingUrl: "{{ url('livedraw') }}", // Meeting joining url
            // },

            // leftScreen: {
            //     // visible when redirect on leave not provieded
            //     actionButton: {
            //         // optional action button
            //         label: "Save & Go Back", // action button label
            //         href: "{{ url('admin/live-stream/new') }}", // action button href
            //     },
            // },

            notificationSoundEnabled: true,

            debug: true, // pop up error during invalid config or netwrok error

            maxResolution: "hd", // "hd" or "sd"            
        });
    </script>        
    @else
        @if($sections != null)
            @foreach(json_decode($sections) as $sec)
                @include($activeTemplate.'sections.'.$sec)
            @endforeach
        @endif
    @endif
@endsection
