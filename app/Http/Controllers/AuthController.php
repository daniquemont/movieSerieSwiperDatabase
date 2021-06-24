<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken($request->name)->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status_code' => 400, 
                'message' => 'Bad Request'
            ]);
        }
        
        $user = Auth::user();
        print_r($user);
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'message' => "U bent ingelogd!" 
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->revoke();
        
        return response()->json([
            'status_code' => 200, 
            'message' => 'Successfully log out'
        ]);

    }
}
