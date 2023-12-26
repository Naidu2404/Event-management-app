<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            "email"=> "required|email",
            "password"=> "required"
        ]);

        //find the user with the email 
        $user = \App\Models\User::where("email", $request->email)->first();

        if(!$user){
            throw ValidationException::withMessages([
                "email"=> "The provided credentials are not correct",
            ]);
        }

        //we need to check the password as it is a hashed value use the Hash class
        if(!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                "email"=> "The provided credentials are not correct",
            ]);
        }

        //create a token for the user using the inbuilt method
        $token = $user->createToken("api-token")->plainTextToken;

        return response()->json([
            "token"=> $token,
        ]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            "message"=> "Logged out succesfully",
        ]);
    }
}
