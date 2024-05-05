<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        $data = $request->only(['email', 'password']);
        if (auth()->attempt($data)) {
            $token = $request->user()->createToken('token')->plainTextToken;
            $user = $request->user();
            $i = [
                'name' => $user->name,
                'email' => $request->email,
                'accessToken' => $token,
            ];

            return response()->json(['message' => 'Login success', 'user' => $i], 200);
        }

        return response()->json(['message' => 'Email or password incorrect',], 401);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout success',], 401);

    }
}


