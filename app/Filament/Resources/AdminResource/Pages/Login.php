<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Page
{
    protected static string $view = 'filament.pages.login';
    protected static string $layout = 'layouts.guest';
    protected static ?string $slug = 'login';

    public $login;
    public $password;
    public $remember = false;

    public function submit()
    {
        $this->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('email', $this->login)
            ->orWhere('username', $this->login)
            ->first();

        // Verifica si el usuario existe, la contraseña y si está activo
        if (! $user || ! \Hash::check($this->password, $user->password) || ! $user->active) {
            throw ValidationException::withMessages([
                'login' => $user && !$user->active
                    ? 'Tu cuenta no está activa.' // Mensaje si no está activo
                    : __('auth.failed'),       // Mensaje general
            ]);
        }

        Auth::login($user, $this->remember);

        return redirect()->intended('/admin');
    }
}
