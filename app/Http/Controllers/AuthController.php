<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(
                [
                    'message' => 'User registered successfully',
                    'token' => $token
                ],
                201
            );
        } catch (Exception $e) {
            return response()->json(['message' => 'User registration failed'], 500);
        }
    }
    //login
    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            //find user
            $user = User::where('email', $request->email)->first();
            //create token
            $token = $user->createToken('auth_token')->plainTextToken;
            //return response
            return response()->json(['message' => 'Login successful', 'token' => $token], 200); 
        }catch(Exception $e){
            return response()->json(['message' => 'Login failed'], 500);
        }
       
    }
    //logout
    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout successful'], 200);
        }catch(Exception $e){
            return response()->json(['message' => 'Logout failed'], 500);
        }
    }
   //user info
   public function userInfo(){
    try{
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }catch(Exception $e){
        return response()->json(['message' => 'User info failed'], 500);
    }
   }

}//end class
