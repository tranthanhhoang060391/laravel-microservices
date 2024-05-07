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
            'secret' => 'required'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 403);
        }


    }
}
