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

            // Intentamos obtener el usuario
            $user = \App\Models\User::where($loginField, $request->login)->first();

            if (!$user || !\Hash::check($request->password, $user->password)) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Validamos si el usuario está activo
            if ($user->active != 1) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Usuario inactivo. Contacta con soporte.'
                ], 403);
            }

            // Creamos el token
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
                'message' => $e->getMessage()
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

    // VALIDAR TOKEN
    public function validateToken(Request $request)
    {
        try {
            $user = $request->user(); // Laravel verifica automáticamente el token

            if (!$user) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }

            // Verificar si el usuario está activo
            if ($user->active != 1) {
                // Revocar el token actual
                $request->user()->currentAccessToken()->delete();

                return response()->json([
                    'code' => 401,
                    'message' => 'Usuario inactivo'
                ], 401);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Token válido',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error al validar el token'
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
