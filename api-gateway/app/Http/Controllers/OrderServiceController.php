<?php

namespace App\Http\Controllers;

use App\Models\InterServiceTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OrderServiceController extends Controller
{
    public function getOrders(Request $request)
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
            ])->get(env('ORDER_SERVICE_URL') . '/orders');

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    public function createOrder(Request $request)
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
            ])->post(env('ORDER_SERVICE_URL') . '/order/create', $request->all());

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    public function getOrder($id)
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
            ])->get(env('ORDER_SERVICE_URL') . '/order/' . $id);

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    public function updateOrder(Request $request, $id)
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
            ])->put(env('ORDER_SERVICE_URL') . '/order/update/' . $id, $request->all());

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    public function deleteOrder($id)
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
            ])->delete(env('ORDER_SERVICE_URL') . '/order/delete/' . $id);

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    public function getUserOrders($user_id)
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
            ])->get(env('ORDER_SERVICE_URL') . '/order/user/' . $user_id);

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }

    private function getTokens()
    {
        $tokens = Cache::get('inter_service_token_order');

        if (empty($tokens)) {
            $tokens = InterServiceTokens::where('issuer_service_id', env('ORDER_SERVICE_ID'))->get();

            if ($tokens->isEmpty() || $tokens->api_token_expires_at < now()) {
                $request = Http::post(env('ORDER_SERVICE_URL')  . '/service-accounts/token', [
                    'service_id' => env('API_GATEWAY_SERVICE_ID'),
                    'service_secret' => env('API_GATEWAY_SERVICE_SECRET'),
                ]);

                $response = $request->json();

                if (!isset($response['data']['token'])) {
                    return [];
                }

                $tokens = InterServiceTokens::updateOrCreate(
                    ['issuer_service_id' => env('ORDER_SERVICE_ID')],
                    [
                        'token' => $response['data']['token'],
                        // convert the expires_in to a timestamp to store in the database
                        'api_token_expires_at' => $response['data']['expires_at']
                    ]
                );

                // Cache the tokens with the time left before it expires
                Cache::put('inter_service_token_order', $tokens, now()->diffInSeconds($response['data']['expires_at']));
            }
        }

        return $tokens;
    }
}
