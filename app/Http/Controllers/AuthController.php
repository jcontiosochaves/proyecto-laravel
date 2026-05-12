<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
{
    try {
        // Esto forzará a JWT a validar el token contra la blacklist
        if (! $user = auth('api')->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($user);
    } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
        return response()->json(['error' => 'Token invalidado'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => 'No autorizado'], 401);
    }
}

    public function logout()
{
    auth('api')->logout(true); 
    return response()->json(['message' => 'Logged out']);
}
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}