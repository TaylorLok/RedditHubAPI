<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } 
        else {
            return response()->json(['message' => 'Invalid credentials, verify your email and password'], 401);
        }

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

}
