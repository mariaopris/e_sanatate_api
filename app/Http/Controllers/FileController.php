<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;

class FileController extends Controller
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
        //
    }

    public function show($id)
    {
        $file = File::where('user_id', $id)->first();
        $user = User::where('id',$id)->first();

        return response()->json(['file' => $file, 'user' => $user]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $file = File::where('id', $id)->first();

        if(isset($request->address)){$file->address = $request->address;}
        if(isset($request->birthday)){$file->birthday = $request->birthday;}
        if(isset($request->gender)){$file->gender = $request->gender;}
        if(isset($request->other_affections)){$file->email = $request->other_affections;}
        if(isset($request->phone)){$file->phone = $request->phone;}
        if(isset($request->weight)){$file->weight = $request->weight;}
        if(isset($request->height)){$file->height = $request->height;}

        $file->update();
        return response()->json(['status' => true, 'message' => 'File updated successfully!']);
    }

    public function destroy($id)
    {
        //
    }
}
