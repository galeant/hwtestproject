<?php

namespace App\Http\Response;

class AuthTransformer
{

    public static function register($data, $msg = 'Success')
    {

        return response()->json([
            'code' => 200,
            'message' => $msg,
            'result' => [
                'name' => $data->name,
                'email' => $data->email
            ]
        ], 200);
    }

    public static function login($user, $token, $msg = 'Success')
    {
        return response()->json([
            'message' => $msg,
            'result' => [
                'user' => [
                    'email' => $user->email,
                    'name' => $user->name
                ],
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
        ]);
    }
}
