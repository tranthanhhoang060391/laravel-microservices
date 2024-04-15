<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function create(Request $request)
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
        return response([
            'token' => $this->generateToken($user),
            'type' => 'Bearer',
            'expires_at' => now()->addWeek()->toDateTimeString()
        ]);
    }

    private function generateToken($user)
    {
        return $user->createToken(
            'api-token',
            ['*'],
            now()->addWeek()
        )->plainTextToken;
    }

    public function revoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Tokens revoked'
        ]);
    }

    public function refresh(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'token' => $this->generateToken($request->user()),
            'type' => 'Bearer',
            'expires_at' => now()->addWeek()->toDateTimeString()
        ]);
    }
}
