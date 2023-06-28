<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function login(Request $request){
        try{
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (auth()->attempt($credentials)) {
                $token = auth()->user()->createToken('MyApp')->plainTextToken;

                return response()->json([
                    'id' => auth()->user()->id,
                    'email' => auth()->user()->email,
                    'password' => auth()->user()->password,
                    'function' => auth()->user()->function,
                    'name'=>auth()->user()->name,
                    'token'=> $token,
                    'state' => true,
                ]);
            }
            else{
                $response = [
                    'state' => false,
                    'message' => 'User login successfully',
                ];
                return response()->json($response, 400);
            }
        }catch(\Exception $exception) {
            $response = [
                'state' => false,
                'error' => $exception->getMessage(),
                'message' => 'Unauthorised',
            ];
            return response()->json($response, 400);
        }
    }

    public function logout(Request $request){
        try {
            $user = $request->user();
            if ($user !== null) {
                $user->currentAccessToken()->delete();
            }
            return response()->json(['message'=>'Logout successfully']);

        } catch (\Exception $exception) {
            $response = [
                'state' => false,
                'error' => $exception->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }

    public function getUser(Request $request)
    {

        $user = User::where('id',$request->user()->id)->first();
        $role = ModelHasRole::select('role_id')->where('model_id', $request->user()->id)->first();
        try{
            return response()->json([
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'password' => $request->user()->password,
                'name'=>$request->user()->name,
                'role'=>$role->role_id,
            ]);
        }catch (\Exception $exception) {
            $response = [
                'state' => false,
                'error' => $exception->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }

    public function index()
    {
        $users = User::with('roles')->get();
        return response()->json(['users' => $users]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation errors', 'errors' => $validator->errors()],400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        try {
            $user->save();
            $user->assignRole(2);
            try {
                $file = File::create([
                    'user_id' => $user->id,
                ]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'User saved successfully !']);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json(['user' => $user]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if(isset($request->name)){$user->name = $request->name;}
        if(isset($request->email)){$user->email = $request->email;}

        $user->save();
        return response()->json(['status' => true, 'message' => 'User details updated successfully!']);
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => 'User was successfully deleted!']);
    }

    public function changeRole(Request $request) {
        $role_id = $request->role_id;

        $user = User::where('id',$request->model_id)->first();

        try {
            $user->roles()->sync([$role_id]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'User role updated!']);
    }
}
