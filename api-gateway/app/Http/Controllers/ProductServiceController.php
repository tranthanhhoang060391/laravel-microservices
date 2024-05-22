<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductServiceController extends Controller
{

    public function getProducts(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . '1|9v5Y4rMPb0YDOD6bsRe8dGuvWobyQfMcwbRYpp5X0ac31407',
            ])->get('http://localhost:3001/api/products');

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Failed to connect', 'exception' => $e->getMessage()], 500);
        }
    }
}
