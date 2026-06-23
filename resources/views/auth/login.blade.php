@extends('layouts.app')

@section('content')
<div class="d-flex justify-center align-center" style="min-height: 70vh;">
    <div class="card animate-fade-in" style="width: 100%; max-width: 450px; padding: 2.5rem;">
        <div class="text-center mb-2">
            <h1 class="brand-logo" style="justify-content: center; margin-bottom: 0.5rem; color: var(--text-color);">
                <svg width="200" height="60" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 30 L50 5 L40 25 L60 25 L20 55 L30 35 L10 35 Z" fill="#0ea5e9" />
                    <text x="70" y="42" font-family="monospace" font-size="28" font-weight="bold" fill="currentColor" letter-spacing="2">ARTICLE</text>
                </svg>
            </h1>
            <p style="color: var(--text-muted);">Connectez-vous pour interagir avec la communauté.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label style="font-weight: 600; font-size: 0.9rem;">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="email@exemple.com">
                @error('email') <span style="color: var(--accent-red); font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-1">
                <label style="font-weight: 600; font-size: 0.9rem;">Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <div class="d-flex align-center mb-2" style="gap: 0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0;">
                <label for="remember" style="font-size: 0.85rem; color: var(--text-muted); cursor: pointer;">Se souvenir de moi</label>
            </div>

            <button type="submit" class="nav-btn pulse-hover" style="width: 100%; border: none;">Se connecter</button>
        </form>

        <div class="text-center mt-2" style="font-size: 0.9rem;">
            <span style="color: var(--text-muted);">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" style="font-weight: 700;">Créer un compte</a>
        </div>
    </div>
</div>
@endsection
