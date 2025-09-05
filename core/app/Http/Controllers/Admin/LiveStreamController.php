<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\Recording;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LiveStreamController extends Controller
{
    public function index()
    {
        $pageTitle = 'New Stream';
        $phases = Phase::active()->winnerNotSet()->whereDate('draw_date', '<=', now())->with('lottery:id,name')->orderBy('draw_date', 'desc')->get();
        return view('admin.livestream.index', compact('pageTitle', 'phases'));
    }

    public function stream(Request $request)
    {
        $request->validate([
            'lottery' => 'required'
        ]);

        $lottery = $request->lottery;
        return view('admin.livestream.stream', compact('lottery'));
    }

    /**
     * Handle recording upload
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recording' => 'required|file|mimes:webm,mp4|max:102400', // 100MB max
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('recording');
            $title = $request->input('phase');
            
            // Generate unique filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '_' . $timestamp . '.' . $extension;

            $path = fileUploader($request->file('recording'), getFilePath('recordings'), filename: $filename);

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store file'
                ], 500);
            }

            // Save recording details to database
            $recording = Recording::create([
                'title' => $title,
                'filename' => $filename,
                'file_path' => $path,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording uploaded successfully',
                'recording' => [
                    'id' => $recording->id,
                    'title' => $recording->title,
                    'filename' => $recording->filename,
                    'file_size' => $recording->file_size,
                    'created_at' => $recording->created_at
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Recording upload failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of all recordings
     */
    public function list()
    {
        try {
            $pageTitle = 'Live Stream Recordings';
            $recordings = Recording::orderBy('created_at', 'desc')->get();
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