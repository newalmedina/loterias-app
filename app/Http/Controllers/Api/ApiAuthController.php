<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class ApiAuthController extends Controller
{

    // LOGIN
    public function login(Request $request)
    {
        try {
            // Validación
            $request->validate([
                'login'    => 'required|string', // email o username
                'password' => 'required|string'
            ]);

            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Intentamos autenticación
            if (!Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Credenciales incorrectas'
                ]);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'code' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'message' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage() // ⚠️ usar getMessage() en lugar de e->errors()
            ]);
        }
    }

    // INFORMACIÓN DEL USUARIO
    public function userInformation(Request $request)
    {
        $user = $request->user();
        $center = $user->center;
        if (!$center) {
            return response()->json([
                'code' => 402,
                'message' => 'Notienes centros asignados'
            ], 402);
        }
        // dd($center);
        try {
            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Ocurrió un error al obtener la información del usuario'
            ], 500);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // Borra todos los tokens del usuario actual
        $request->user()->tokens()->delete();

        Auth::logout(); // cerrar sesión
        return response()->json(['message' => 'Logout exitoso']);
    }
}
