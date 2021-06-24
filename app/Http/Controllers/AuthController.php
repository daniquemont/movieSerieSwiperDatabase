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
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // if($validator->fails()){
        //     return response()->json(['status_code' => 400, 
        //     'message' => 'Bad Request']);
        // }

        // $user = new User();
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = bcrypt($request->password);
        // $user->save();

        // return response()->json([
        //     'status_code' => 200,
        //     'message' => 'User created successfully'
        // ]);

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
        

        // $credentials = request(['email', 'password']);
        // if(!Auth::attempt($credentials)){
        //     return response()->json([
        //         'status_code' => 500,
        //         'message' => 'Unauthorized'
        //     ]);
        // }

        // $tokenResult = $user->createToken('authToken')->plainTextToken;
        // return response()->json([
        //     'status_code' => 200,
        //     'message' => $tokenResult
        // ]);

        // $fields = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string'
        // ]);

        // $user = User::where('email', $request->email)->first();
        
        // if (! $user || ! Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect.'],
        //     ]);
        // }

        // $token = $user->createToken($request->email)->plainTextToken;

        // return response()->json([
        //     'status_code' => 200,
        //     'token' => $token
        // ]);

        // if($fields->fails()){
        //     return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
        // }
        
        //check password
        // if(!$user::attempt($credentials)){
        //     return response()->json([
        //         'status_code' => 500,
        //         'message' => 'Bad creds'
        //     ]);
        // }

        // $user = Auth::user();

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
        // $user = auth()->user();
        // $token = $user->createToken('authToken');
        // $accessToken = $token->accessToken;

        // return response()->json([
        //     'status_code' => 200,
        //     'token' => $accessToken
        // ]);
        // $user = Auth::user();

        $user = User::where('email', $request->email)->first();
        
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'status_code' => 200,
            'message' => "U bent ingelogd!" 
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->revoke();
        
        return response()->json([
            'status_code' => 200, 
            'message' => 'Successfully log out'
        ]);

    }
}
