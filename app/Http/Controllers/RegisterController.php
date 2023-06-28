<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $google2fa = new Google2FA();
        $secret = $request->secret;
        if ($google2fa->verify($request->input('twofa'), $secret)) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'twofa_secret' => $secret,
            ]);
            $user->assignRole(2);
            try {
                $file = File::create([
                    'user_id' => $user->id,
                ]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
            $credentials = $request->only('email','password');

            if(! auth()->attempt($credentials)){
                throw ValidationException::withMessages([
                    'email' => 'Invalid credentials'
                ]);
            }

            $success['token'] =  auth()->user()->createToken('MyApp')->plainTextToken;
            $success['name'] =  auth()->user()->name;
            $success['email'] = auth()->user()->email;

            return response()->json($success);
        }
        return response()->json('Invalid 2fa code.');
    }

    public function attemptRegister(Request $request)
    {
//        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);

        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        $qr_code = $google2fa->getQRCodeInline(
            "iStethoscope",
            $request->email,
            $secret
        );

        return response()->json(["qr_code" => $qr_code, "secret"=>$secret]);
    }
}
