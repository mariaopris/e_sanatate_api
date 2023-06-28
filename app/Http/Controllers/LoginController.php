<?php

namespace App\Http\Controllers;

use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FAQRCode\Google2FA;

class LoginController extends Controller
{

    public function attemptLogin(Request $request){
        $credentials = $request->only('email','password');
        if(! auth()->attempt($credentials)){
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials'
            ]);
        }
        return response()->json(true);
    }

    public function login(Request $request){
        $credentials = $request->only('email','password');
        if(! auth()->attempt($credentials)){
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials'
            ]);
        }

//        $request->session()->regenerate();
        $success['token'] =  auth()->user()->createToken('MyApp')->plainTextToken;
        $success['name'] =  auth()->user()->name;
        $success['email'] = auth()->user()->email;
        $role = ModelHasRole::select('role_id')->where('model_id', $request->user()->id)->first();
        $success['role'] = $role->role_id;

        $google2fa = new Google2FA();
        $secret = auth()->user()->twofa_secret;

        if ($google2fa->verify($request->input('twofa'), $secret)) {
            return response()->json($success);
        }

        throw ValidationException::withMessages([
            'otp' => 'Incorrect value. Please try again...'
        ]);

//        return $this->sendResponse($success, 'User login successfully.');
    }
}
