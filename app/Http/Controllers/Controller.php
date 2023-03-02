<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function respondWithToken($token, bool $remember)
    {
        $expires = Carbon::now();

        if ($remember) {
            $expires->addWeeks(1);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_at' => $expires->toDateString()
        ], 200);
    }
}
