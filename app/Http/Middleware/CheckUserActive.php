<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->active != 1) {

            // Revoca todos los tokens del usuario si existe
            if ($user && method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            return response()->json([
                'success' => false,
                'message' => 'User is not active. Tokens revoked.'
            ], 403); // Forbidden
        }

        return $next($request);
    }
}
