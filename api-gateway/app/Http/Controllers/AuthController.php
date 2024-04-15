<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function issueToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $user->createToken('api-token')->plainTextToken;
    }


    public function revokeToken(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Tokens revoked'
        ]);
    }

    public function refreshToken(Request $request)
    {
        $request->user()->tokens()->delete();

        return $request->user()->createToken('api-token')->plainTextToken;
    }
}
