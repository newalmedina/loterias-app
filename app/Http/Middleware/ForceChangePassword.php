<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceChangePassword
{
    public function handle(Request $request, Closure $next)
    {
        dd("dsadsa");
        if (Auth::check() && Auth::user()->change_password) {
            if (!$request->routeIs('change-password.*')) {
                return redirect()->route('change-password.form');
            }
        }

        return $next($request);
    }
}
