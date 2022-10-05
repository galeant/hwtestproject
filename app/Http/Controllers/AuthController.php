<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Response\AuthTransformer;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
            return AuthTransformer::register($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where([
                'email' => $request->email
            ])->first();
            if ($user == NULL) {
                throw new \Exception('User Not found');
            }
            if (!Hash::check($request->password, $user->password)) {
                throw new \Exception('Wrong password');
            }
            $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            return AuthTransformer::login($user, $token);

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
