<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $user->password = bcrypt($request->password);
        $user->change_password = 0;
        $user->saveQuietly(); // evita eventos que cierren sesión

        // 🔹 Refresca la sesión del usuario
        Auth::setUser($user);

        return redirect('/')->with('success', 'Contraseña cambiada correctamente');
    }
}
