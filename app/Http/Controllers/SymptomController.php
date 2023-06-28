<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SymptomController extends Controller
{

    public function index()
    {
        $symptoms = Symptom::all();
        return response()->json(['symptoms' => $symptoms]);
    }

    public function getSymptomsByType(Request $request) {
        $type = $request->type;
        $symptoms = Symptom::where('type', $type)->get();
        return response()->json(['symptoms' => $symptoms]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()],400);
        }

        $name = $request->name;
        $type = $request->type;
        $description = $request->description;

        try {
            $symptom = Symptom::create([
                'name' => $name,
                'type' => $type,
                'description' => $description,
            ]);
        }catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'Symptom saved successfully !']);
    }

    public function show($id)
    {
        $symptom = Symptom::where('id', $id)->first();
        return response()->json(['symptom' => $symptom]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        $symptom = Symptom::where('id', $id)->first();

        if(isset($request->name)){$symptom->name = $request->name;}
        if(isset($request->description)){$symptom->description = $request->description;}

        $symptom->save();
        return response()->json(['status' => true, 'message' => 'Symptom updated successfully !']);
    }

    public function destroy($id)
    {
        Symptom::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'Symptom was successfully deleted!']);
    }
}
