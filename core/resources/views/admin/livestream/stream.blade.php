<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/png" href="{{ url('assets/images/logo_icon/favicon.png') }}">
    <title>Panolotto - Stream</title>
</head>

<body>
    <script src="https://sdk.videosdk.live/rtc-js-prebuilt/0.3.43/rtc-js-prebuilt.js"></script>
    <script>
        const meeting = new VideoSDKMeeting();

        meeting.init({
            //parameters
            apiKey: "c09d7c7d-e4b5-4e36-b0e0-4e04a75eb114",
            meetingId: "DS7A9S8",
            name: "{{ auth()->guard('admin')->user()->name }} - {{ $lottery }}",

            containerId: null,

            micEnabled: true,
            webcamEnabled: true,
            participantCanToggleSelfWebcam: true,
            participantCanToggleSelfMic: true,
            participantCanLeave: true, // if false, leave button won't be visible

            chatEnabled: true,
            screenShareEnabled: true,
            pollEnabled: true,
            whiteboardEnabled: true,
            raiseHandEnabled: true,
            mode: "CONFERENCE", // VIEWER || CONFERENCE

            recording: {
                autoStart: true, // auto start recording on participant joined
                enabled: true,
                webhookUrl: "https://panolotto.com/stream/callback",
            },

            hls: {
                enabled: true,
                autoStart: true,
            },            

            layout: {
                type: "SPOTLIGHT", // "SPOTLIGHT" | "SIDEBAR" | "GRID"
                priority: "SPEAKER", // "SPEAKER" | "PIN",
                // gridSize: 3,
            },

            branding: {
                enabled: true,
                logoURL: "{{ url('assets/images/logo_icon/favicon.png') }}",
                name: "{{ $lottery }}",
                poweredBy: false,
            },

            permissions: {
                pin: true,
                askToJoin: false, // Ask joined participants for entry in meeting
                toggleParticipantMic: true, // Can toggle other participant's mic
                toggleParticipantWebcam: true, // Can toggle other participant's webcam
                toggleParticipantScreenshare: true, // Can toggle other participant's screen share
                toggleParticipantMode: true, // Can toggle other participant's mode
                canCreatePoll: true, // Can create a poll
                toggleHls: true, // Can toggle Start HLS button
                drawOnWhiteboard: true, // Can draw on whiteboard
                toggleWhiteboard: true, // Can toggle whiteboard
                toggleVirtualBackground: true, // Can toggle virtual background
                toggleRecording: true, // Can toggle meeting recording
                toggleLivestream: true, //can toggle live stream
                removeParticipant: true, // Can remove participant
                endMeeting: true, // Can end meeting
                changeLayout: true, //can change layout
            },

            joinScreen: {
                visible: true, // Show the join screen ?
                title: "{{ $lottery }}", // Meeting title
                meetingUrl: "{{ url('livedraw') }}", // Meeting joining url
            },

            leftScreen: {
                // visible when redirect on leave not provieded
                actionButton: {
                    // optional action button
                    label: "Return Back", // action button label
                    href: "{{ url('admin/live-stream/new') }}", // action button href
                },
            },

            notificationSoundEnabled: true,

            debug: true, // pop up error during invalid config or netwrok error

            maxResolution: "hd", // "hd" or "sd"
            
        });
    </script>
</body>

</html>
