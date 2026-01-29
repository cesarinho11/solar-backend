<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {


        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        // Usuario inactivo
        if ($user->estatus != 1) {
            return response()->json(['error' => 'Usuario inactivo'], 403);
        }
        // dump($credentials);
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas', $credentials], 401);
        }

        // $user = auth()->user();
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'tipo' => $user->tipo
            ]
        ]);

        // return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
