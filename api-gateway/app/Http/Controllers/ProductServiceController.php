<?php

namespace App\Http\Controllers;

use App\Models\InterServiceTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ProductServiceController extends Controller
{

    public function getProducts(Request $request)
    {
        try {
            $tokens = $this->getTokens();
            if (!$tokens) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get tokens'
                ], 500);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getTokens()->token,
            ])->get(env('PRODUCT_SERVICE_URL') . '/products');

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    private function getTokens()
    {
        $tokens = Cache::get('inter_service_tokens');
        if (empty($tokens)) {
            $tokens = InterServiceTokens::where('issuer_service_id', env('PRODUCT_SERVICE_ID'))->get();

            if ($tokens->isEmpty() || $tokens->first()->api_token_expires_at < now()) {
                $request = Http::post(env('PRODUCT_SERVICE_URL')  . '/service-accounts/token', [
                    'service_id' => env('PRODUCT_SERVICE_ID'),
                    'service_secret' => env('PRODUCT_SERVICE_SECRET'),
                ]);

                $response = $request->json();

                if (!isset($response['data']['token'])) {
                    return [];
                }

                $tokens = InterServiceTokens::updateOrCreate(
                    ['issuer_service_id' => env('PRODUCT_SERVICE_ID')],
                    [
                        'token' => $response['data']['token'],
                        // convert the expires_in to a timestamp to store in the database
                        'api_token_expires_at' => $response['data']['expires_at']
                    ]
                );

                // Cache the tokens with the time left before it expires
                Cache::put('inter_service_tokens', $tokens, now()->diffInSeconds($response['data']['expires_at']));
            }
        }

        return $tokens->first();
    }
}
