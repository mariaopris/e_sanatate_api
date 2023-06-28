<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $file = $request->audio;
        $user_id = $request->userId;
        $date = Carbon::now();

        try {
            Storage::disk('public')->put('/audio/'.$user_id.'/audio_'.$date.'.'.$file->extension(),file_get_contents($file));
            $recording = Recording::create([
                'user_id' => $user_id,
                'file_name' => 'audio_'.$date.'.'.$file->extension(),
                'path' => '/audio/'.$user_id.'/',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'Audio saved successfully !', 'recording_id' => $recording->id]);

    }

    public function show($id)
    {
        $recordings = Recording::where('user_id', $id)->get();
        return response()->json(['recordings' => $recordings]);
    }

    public function showAudio(Request $request)
    {
        $recording = Recording::where('id', $request->id)->first();
        return response()->json(['recording' => $recording]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $recording = Recording::where('id', $id)->first();

        if(Storage::disk('public')->exists($recording->path.$recording->file_name)) {
            Storage::disk('public')->delete($recording->path.$recording->file_name);
        }
        Recording::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Recording was successfully deleted!']);
    }

    public function editAudioName(Request $request) {
        $id = $request->id;
        $file_name = $request->file_name;
        $recording = Recording::where('id', $id)->first();

        try {
            Storage::disk('public')->move($recording->path.$recording->file_name, $recording->path.$file_name);
            $recording->file_name = $file_name;
            $recording->save();
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'File name changed successfully !']);

    }

    public function getAudio(Request $request)
    {
        return Storage::disk('public')->download($request->filename);
    }
}
