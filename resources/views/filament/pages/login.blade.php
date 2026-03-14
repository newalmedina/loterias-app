<div>
    <div class="container">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="{{ asset('logos/logo.png') }}" alt="Logo">
        </div>

        <!-- Formulario -->
        <form wire:submit.prevent="submit">
            <h2>Iniciar Sesión</h2>

            <!-- Email o Username -->
            <input
                type="text"
                wire:model="login"
                placeholder="Email o Username"
                required
            >

            <!-- Password -->
            <input
                type="password"
                wire:model="password"
                placeholder="Contraseña"
                required
            >

            <!-- Remember me -->
            <label class="inline-flex">
                <input type="checkbox" wire:model="remember">
                Recordarme
            </label>

            <!-- Error -->
            @error('login')
                <p class="error-message">{{ $message }}</p>
            @enderror

            <!-- Botón -->
            <button type="submit">
                Entrar
            </button>
        </form>
    </div>

    <style>
    /* Reset básico */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f3f4f6; /* gris claro */
    }

    .container {
        width: 400px; /* ancho fijo mayor */
        height: 600px; /* altura fija */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Logo */
    .logo-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .logo-wrapper img {
        height: 120px; /* logo más grande */
        width: auto;
        object-fit: contain;
    }

    /* Formulario */
    form {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        height: 100%; /* ocupa toda la altura del contenedor */
        justify-content: center;
    }

    form h2 {
        text-align: center;
        font-size: 2rem; /* más grande */
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: #1e3a8a;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 1rem; /* más grande */
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.3);
    }

    label.inline-flex {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 1rem;
    }

    label.inline-flex input[type="checkbox"] {
        margin-right: 0.5rem;
        width: 18px;
        height: 18px;
    }

    /* Error */
    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Botón */
    button {
        width: 100%;
        background-color: #1d4ed8;
        color: #fff;
        padding: 1rem; /* más grande */
        font-size: 1.1rem;
        font-weight: 600;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 10px rgba(0,0,0,0.15);
    }

    button:hover {
        background-color: #2563eb;
        transform: scale(1.05);
    }

    @media(max-width: 480px){
        .container {
            width: 90%;
            height: auto;
        }
        form {
            padding: 1.5rem;
        }
        .logo-wrapper img {
            height: 90px;
        }
    }
    </style>
</div>
