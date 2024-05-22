<?php

namespace App\Http\Controllers;

use App\Models\ServiceAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceAccountController extends Controller
{
    public function issueToken(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'service_id' => 'required',
            'service_secret' => 'required'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 403);
        }

        $serviceAccount = ServiceAccount::where('service_id', $request->service_id)
            ->where('service_secret', $request->service_secret)
            ->first();

        if (!$serviceAccount) {
            return response([
                'message' => 'Invalid service id or secret'
            ], 403);
        }

        $token = $serviceAccount->createToken('product-service')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token issued successfully',
            'data' => [
                'token' => $token,
                'type' => 'Bearer',
                'expires_at' => now()->addWeek()->toDateTimeString(),
            ]
        ]);
    }

    public function refreshToken(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'service_id' => 'required',
            'secret' => 'required'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 403);
        }

        $serviceAccount = ServiceAccount::where('service_id', $request->service_id)
            ->where('secret', $request->secret)
            ->first();

        if (!$serviceAccount) {
            return response([
                'message' => 'Invalid service id or secret'
            ], 403);
        }

        $serviceAccount->tokens()->delete();

        $token = $serviceAccount->createToken('product-service')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
                'type' => 'Bearer',
                'expires_at' => now()->addWeek()->toDateTimeString(),
            ]
        ]);
    }
}
