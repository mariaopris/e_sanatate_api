<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiseaseController extends Controller
{

    public function index()
    {
        $diseases = Disease::all();
        return response()->json(['diseases' => $diseases]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()],400);
        }

        $name = $request->name;
        $symptoms_ids= $request->symptoms_ids;
        $description = $request->description;
        $treatment = $request->treatment;

        try {
            $disease = Disease::create([
                'name' => $name,
                'symptoms_ids' => $symptoms_ids,
                'description' => $description,
                'treatment' => $treatment,
            ]);
        }catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'Disease saved successfully !']);
    }

    public function show($id)
    {
        $disease = Disease::where('id', $id)->first();
        return response()->json(['disease' => $disease]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $disease = Disease::where('id', $id)->first();

        if(isset($request->name)){$disease->name = $request->name;}
        if(isset($request->symptoms_ids)){$disease->symptoms_ids = $request->symptoms_ids;}
        if(isset($request->description)){$disease->description = $request->description;}
        if(isset($request->treatment)){$disease->treatment = $request->treatment;}
        if(isset($request->treatment)){$disease->treatment = $request->treatment;}
        if(isset($request->weights)){$disease->weights = $request->weights;}

        $disease->save();
        return response()->json(['status' => true, 'message' => 'Disease updated successfully !']);
    }

    public function destroy($id)
    {
        Disease::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Disease was successfully deleted!']);
    }

    public function getDiseasesByType(Request $request) {
        $type = $request->type;

        $diseases = Disease::where('type', $type)->get();
        return response()->json(['diseases' => $diseases]);
    }
}
