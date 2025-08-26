@extends('admin.layouts.app')
@section('panel')
    <style>
        #zegoContainer {
            width: 100%;
            height: 600px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .recording-container {
            max-width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            margin-bottom: 10px;
        }
        .btn {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-weight: bold;
        }
        .status.recording { background: #d4edda; color: #155724; }
        .status.stopped { background: #f8d7da; color: #721c24; }
        .preview-video {
            max-width: 100%;
            margin: 20px 0;
            border: 1px solid #ccc;
        }

        .recording-item {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
    </style>
    <div class="recording-container">
        <div id="status" class="status" style="display: none;"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="controls">
                <button id="startBtn" class="btn btn-primary">Start Recording</button>
                <button id="stopBtn" class="btn btn-danger" disabled>Stop Recording</button>
            </div>
            <div>
                <select name="phase" class="form-control" id="phase">
                    <option value="" selected disabled>Select Phase</option>
                    @foreach($phases as $phase)
                        <option value="{{$phase->lottery->name}} | {{showPhase($phase->phase_no)}}">{{$phase->lottery->name}} | {{showPhase($phase->phase_no)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="uploadStatus" style="margin-top: 20px;"></div>
        <video id="preview" class="preview-video" controls style="display: none;"></video>
    </div>    
    <div class="row">
        <div class="col-lg-12">
            <div class="card  ">
                <div class="card-body p-0">
                    <div id="zegoContainer"></div>
                    <input type="hidden" name="username" id="username" value="Panolotto Admin" />
                </div>
            </div>
        </div>
    </div>

    <script>
        let mediaRecorder;
        let recordedChunks = [];
        let stream;
        let phase = '';

        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const preview = document.getElementById('preview');
        const status = document.getElementById('status');
        const uploadStatus = document.getElementById('uploadStatus');

        // CSRF token for Laravel
        const csrfToken = "{{ csrf_token() }}";

        startBtn.addEventListener('click', startRecording);
        stopBtn.addEventListener('click', stopRecording);

        async function startRecording() {
            try {

                let confirmStart = confirm("Screen recording will start. Please select the screen/window to share.");
                if (!confirmStart) return;

                phase = document.getElementById('phase').value;
                if (!phase) {
                    alert('Please select a phase before starting the recording.');
                    return;
                }

                // Request screen capture
                stream = await navigator.mediaDevices.getDisplayMedia({
                    video: {
                        mediaSource: 'screen',
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    },
                    audio: true // Include system audio if available
                });

                // Create MediaRecorder
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: 'video/webm;codecs=vp9'
                });

                recordedChunks = [];

                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = handleRecordingStop;

                // Start recording
                mediaRecorder.start();
                
                // Update UI
                startBtn.disabled = true;
                stopBtn.disabled = false;
                status.textContent = 'Recording in progress...';
                status.className = 'status recording';
                status.style.display = 'block';

                // Handle stream ending (user stops sharing)
                stream.getVideoTracks()[0].onended = () => {
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        stopRecording();
                    }
                };

            } catch (err) {
                console.error('Error starting recording:', err);
                alert('Failed to start recording. Please ensure you grant screen sharing permission.');
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                
                // Stop all tracks
                stream.getTracks().forEach(track => track.stop());
                
                // Update UI
                startBtn.disabled = false;
                stopBtn.disabled = true;
                status.textContent = 'Recording stopped. Processing...';
                status.className = 'status stopped';
            }
        }

        async function handleRecordingStop() {
            const blob = new Blob(recordedChunks, { type: 'video/webm' });
            
            // Show preview
            const url = URL.createObjectURL(blob);
            preview.src = url;
            preview.style.display = 'block';
            
            // Upload to server
            await uploadRecording(blob);
        }

        async function uploadRecording(blob) {
            const formData = new FormData();
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            const filename = `screen-recording-${timestamp}.webm`;
            
            formData.append('recording', blob, filename);
            formData.append('phase', phase);
            formData.append('title', `Screen Recording - ${new Date().toLocaleString()}`);
            formData.append('_token', csrfToken);

            uploadStatus.innerHTML = '<p style="color: blue;">Uploading recording...</p>';

            try {
                const response = await fetch("{{ url('admin/live-stream/upload') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    uploadStatus.innerHTML = `<div class="alert alert-success alert-dismissible fade show p-3" role="alert">
                        <strong>Uploaded!</strong> &nbsp; Recording saved sucessfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                } else {
                    uploadStatus.innerHTML = '<p style="color: red;">Upload failed: ' + result.message + '</p>';
                }
            } catch (error) {
                console.error('Upload error:', error);
                uploadStatus.innerHTML = '<p style="color: red;">Upload failed: Network error</p>';
            }

            status.style.display = 'none';
        }
    </script>
@endsection
