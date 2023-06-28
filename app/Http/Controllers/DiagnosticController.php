<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosticController extends Controller
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
        $validator = Validator::make($request->form, [
            'user_id' => 'required',
            'recording_id' => 'required',
            'disease_id' => 'required',
            'type' => 'required',
            'result' => 'required',
            'result_short' => 'required',
            'predictions' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()],400);
        }

        $user_id = $request->form['user_id'];
        $recording_id = $request->form['recording_id'];
        $disease_id = $request->form['disease_id'];
        $type = $request->form['type'];
        $result = $request->form['result'];
        $result_short = $request->form['result_short'];
        $predictions = $request->form['predictions'];

        try {
            $diagnostic = Diagnostic::create([
                'user_id' => $user_id,
                'recording_id' => $recording_id,
                'disease_id' => $disease_id,
                'type' => $type,
                'result' => $result,
                'result_short' => $result_short,
                'predictions' => $predictions,
            ]);
        }catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'Diagnostic saved successfully !', 'id' => $diagnostic->id]);

    }

    public function show($id)
    {
        $diagnostic = Diagnostic::where('id', $id)->first();
        return response()->json(['diagnostic' => $diagnostic]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $diagnostic = Diagnostic::where('id', $id)->first();

        if(isset($request->symptoms)) {
            $diagnostic->symptoms = $request->symptoms;
        }

        if(isset($request->weighted_means_result)) {
            $diagnostic->weighted_means_result = $request->weighted_means_result;
        }

        if(isset($request->disease_id)) {
            $diagnostic->disease_id = $request->disease_id;
        }

        if(isset($request->result)) {
            $diagnostic->result = $request->result;
        }

        if(isset($request->result_short)) {
            $diagnostic->result_short = $request->result_short;
        }

        $diagnostic->save();
        return response()->json(['status' => true, 'message' => 'Diagnostic details updated successfully!']);

    }

    public function destroy($id)
    {
        Diagnostic::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Diagnostic was successfully deleted!']);
    }

    public function getDiagnosticsUserId($user_id) {
        $diagnostics = Diagnostic::where('user_id', $user_id)->get();
        return response()->json(['diagnostics' => $diagnostics]);
    }
}
