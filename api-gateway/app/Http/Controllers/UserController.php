<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if (User::where('email', $fields['email'])->exists()) {
            return response([
                'message' => 'User already exists'
            ], 409);
        }

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken(
            'api-token',
            ['*'],
            now()->addWeek()
        )->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function profile(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        return response($user, 200);
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = $request->user();
        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->save();

        return response($user, 200);
    }
}
