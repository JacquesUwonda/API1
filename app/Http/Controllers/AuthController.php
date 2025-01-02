<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //register
    public function register(Request $request){

        $validated=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6|confirmed',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }
        try {
            $user=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            $token=$user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token'=>$token,
                'user'=>$user,
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$exception->getMessage()
            ],500);
        }
    }

    //Login
    public function login(Request $request){
        $validated=validator::make($request->all(),[
            'email'=>'required|string|email|max:255',
            'password'=>'required|string|min:6',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        };

        $credentials=['email'=>$request->email,'password'=>$request->password];
        try {
            if(auth()->attempt($credentials)){
                $user=User::where('email',$request->email)->firstOrFail();
                $token=$user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'access_token'=>$token,
                    'user'=>$user,
                ],200);
            } else {
                return response()->json([
                   'message'=>'Invalid Credentials'
                ],401);
            }
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],500);
        }
    }
    //logout
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
           'message'=>'Logged Out Successfully'
        ],200);
    }
}
