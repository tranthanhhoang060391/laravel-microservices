<?php

namespace App\Http\Controllers;

use App\Models\ServiceAccount;
use App\Models\InterServiceTokens;
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

        return response()->json([
            'status' => 'success',
            'message' => 'Token issued successfully',
            'data' => $this->generateToken($serviceAccount)
        ]);
    }

    private function generateToken($serviceAccount)
    {
        $serviceAccount->tokens()->delete();
        InterServiceTokens::where('receiver_service_id', $serviceAccount->service_id)->delete();

        $expiresAt = now()->addWeek();
        $token = $serviceAccount->createToken(
            'api-token',
            ['*'],
            $expiresAt
        )->plainTextToken;

        InterServiceTokens::updateOrCreate([
            'receiver_service_id' => $serviceAccount->service_id,
            'token' => $token,
            'api_token_expires_at' => $expiresAt
        ]);

        return [
            'token' => $token,
            'expires_at' => $expiresAt,
            'type' => 'Bearer'
        ];
    }
}
