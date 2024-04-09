<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

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
            return response()->json($user);
    }


}
