<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveStream;
use App\Models\Phase;
use App\Models\Recording;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LiveStreamController extends Controller
{
    public function index()
    {
        $pageTitle = 'New Stream';
        $phases = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery:id,name')->orderBy('draw_date', 'desc')->get();
        $stream = ActiveStream::find(1);
        return view('admin.livestream.index', compact('pageTitle', 'phases', 'stream'));
    }

    public function stream(Request $request)
    {
        $request->validate([
            'lottery' => 'required'
        ]);

        $stream = ActiveStream::find(1);

        if($stream->status == 0) 
        {
            $notify[] = ['error', "Please activate stream first!"];
            return back()->withNotify($notify);
        }

        $lottery = $request->lottery;
        
        ActiveStream::whereId(1)->update(['title' => $lottery]);

        return view('admin.livestream.stream', compact('lottery'));
    }


    public function status(Request $request)
    {
        $stream = ActiveStream::find(1);
        if($request->status == "activate") {
            $stream->status = 1;
            $message = "Stream activated successfully!";
        } else {
            $stream->status = 0;
            $message = "Stream deactivated successfully!";
        }
        $stream->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }


    public function generateToken()
    {
        $VIDEOSDK_API_KEY = "382f472d-a53d-4594-8247-6c92bc3745bd";
        $VIDEOSDK_SECRET_KEY = "ea06c7e86ef386a0af6dffafbe3e03adf3fe69d9ca5d74c609acb41a5c23c4a9";

        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+2 hours')->getTimestamp();

        $payload = [
            "apikey"       => $VIDEOSDK_API_KEY,
            "version"      => 2,
            "iat"          => $issuedAt->getTimestamp(),
            "exp"          => $expire,
        ];

        $jwt = JWT::encode($payload, $VIDEOSDK_SECRET_KEY, 'HS256');
    
        return $jwt;
    }

    public function fetchSessions()
    {
        $token = $this->generateToken();

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type'  => 'application/json',
        ])->get('https://api.videosdk.live/v2/sessions', [
            'page'    => 1,
            'perPage' => 5,
        ]);

        return $response->json();
    }

    public function fetchRecordings($id)
    {
        $token = $this->generateToken();

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type'  => 'application/json',
        ])->get("https://api.videosdk.live/v2/recordings/{$id}");

        return $response->json();
    }

    public function update()
    {        
        Recording::truncate();
        $title = "Uknown Title";
        $sessions = $this->fetchSessions();
        foreach($sessions["data"] as $session) {
            $recordings = [];
            foreach($session["participants"] as $participant) {
                if($participant["mode"] == "CONFERENCE") {
                    if(isset(explode('- ', $participant["name"])[1])) {
                        $title = explode('- ', $participant["name"])[1];              
                    }
                }
            }

            foreach($session["recordingLog"] as $recordingLog) {
                $recording = $this->fetchRecordings($recordingLog["recordingId"]);
                $recordings[] = $recording["file"]["fileUrl"];
            }

            $recModel = new Recording();
            $recModel->title = $title;
            $recModel->videos = json_encode($recordings);
            $recModel->created_at = $session["start"];
            $recModel->save();
        }

        $notify[] = ['success', "Recordings updated successfully!"];
        return back()->withNotify($notify);
    }

    /**
     * Get list of all recordings
     */
    public function list()
    {
        try {
            $pageTitle = 'Live Stream Recordings';
            $recordings = Recording::all();
            return view('admin.livestream.list', compact('pageTitle', 'recordings'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch recordings'
            ], 500);
        }
    }

    /**
     * Delete a recording
     */
    public function delete($id)
    {
        try {
            $recording = Recording::findOrFail($id);
            
            // Delete file from storage
            if (file_exists(url('assets/recordings/' . $recording->file_path))) {
                unlink('assets/recordings/' . $recording->file_path);
            }
            
            // Delete database record
            $recording->delete();
            
            $notify[] = ['success','Recording deleted successfully'];
            return back()->withNotify($notify);
            
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /**
     * Download a recording
     */
    public function download($id)
    {
        try {
            $recording = Recording::findOrFail($id);
            $filePath = storage_path('app/public/' . $recording->file_path);
            
            if (!file_exists($filePath)) {
                abort(404, 'Recording file not found');
            }
            
            return response()->download($filePath, $recording->filename);
            
        } catch (\Exception $e) {
            abort(404, 'Recording not found');
        }
    }
}

?>