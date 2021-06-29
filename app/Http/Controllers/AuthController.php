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

        // $fields = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
            
        // ]);

        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // $token = $user->createToken($request->name)->plainTextToken;

        // $response = [
        //     'user' => $user,
        //     'token' => $token
        // ];

        // return response($response, 201);
        // return response()->json([
        //     'user' => $user,
        //     // 'token' => $token
        // ]);

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
        dd(
            // User::find(Auth::id('user_id')) ?? '?',
            // User::find(Auth::id()) ?? '?',
            User::find(Auth::user()) ?? '?',
            // User::find(Auth::user()->id()) ?? '?',
            // User::find(Auth::user()->id('user_id')) ?? '?',
            // $token ?? '?'
        );
        
        $user = User::where('email', $request->email)->first(); //'tokenable_id' cannot be null

        if($user){
            if(Hash::check($request->password, $user->password)){
                // $users = Auth::user();//error: createToken() on null
               
                // $token = $users->createToken('token')->accessToken;
                // $token = $user->createToken('token')->accessToken;

                // $users = User::find(Auth::id('user_id'));
                // $test = User::find(Auth::user());
                
                // $token = $users->createToken('token')->accessToken;
               
                
                $cookie = cookie('jwt', $token, 60 * 24); // 1 dag

                return response()->json([
                    'message' => "U bent ingelogd!",
                    'token' => $token 
                ], 200)->withCookie($cookie);
                // return response()->json([
                //     'status_code' => 200,
                //     'token' => $token
                // ]);
            }else{
                $response = ['message' => 'Password mismatch'];
                return response($response, 422);
            }
        }else{
            $response = ['message' => 'User does not exist'];
            return response($response, 422);
        }

        // $users = Auth::user();
        // // print_r($user);
        // $token = $users->createToken('token')->plainTextToken;

        

        
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->revoke();
        
        return response()->json([
            'status_code' => 200, 
            'message' => 'Successfully log out'
        ]);

    }

    
}
