<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'user' => $user,
            'status' => 'success',
            'message' => 'Login successfull',
            'data' => [
                'token' => $this->generateToken($user),
                'type' => 'Bearer',
                'expires_at' => now()->addWeek()->toDateTimeString(),
            ]
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

    public function tokenRevoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Tokens revoked'
        ]);
    }

    public function tokenRefresh(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'token' => $this->generateToken($request->user()),
            'type' => 'Bearer',
            'expires_at' => now()->addWeek()->toDateTimeString()
        ]);
    }
}
