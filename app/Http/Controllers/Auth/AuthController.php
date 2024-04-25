<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::where('email', $request->email)->with('role')->first();

        if ($user) {
            if($user->is_verified == 0){
                throw ValidationException::withMessages([
                    'authentication' => 'Error! Admin has not yet verified the account.',
                ]);
            }

            if (Hash::check($request->password, $user->password)) {
                

                if ($user->tokens) {
                    foreach ($user->tokens as $token) {
                        $token->delete();
                    }
                }


                $result = [
                    'token' => $user->createToken(config('app.name'))->plainTextToken,
                    'user' => $user,
                ];

                $response = [
                    'message' => 'User login successfully',
                    'data' => $result
            
                ];

                return response()->json($response,201);

            }

            throw ValidationException::withMessages([
                'authentication' => 'Sign In Failed! Wrong Password',
            ]);


        }

        throw ValidationException::withMessages([
            'authentication' => 'Sign In Failed! User Not Found',
        ]);

       
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

       return response()->json([
                'message' => 'User logout successfully',
                'data' => []

       ],200);
    }


    public function AuthUser()
    {
             $user = User::find(Auth::user()->id);
             if($user->is_verified == 0){
                $user->tokens()->delete();
                return response()->json([
                    'message' => 'User logout successfully',
                    'data' => []
    
                ],200);
             }
            return response()->json($user);
    }

    public function forgot(Request $request)
    {
             $user = User::where('email', $request->email)->first();
             if($user){

                $password = Str::random(16);

                $user->password = Hash::make($password);
                $user->save();
             
                Mail::to($user->email)->send(new ForgotPassword($user->email, $password));
                return response()->json($user);
             }else{
                throw ValidationException::withMessages([
                    'authentication' => 'Email Not Found!',
                ]);
             }
            // return response()->json($user);
    }


}
