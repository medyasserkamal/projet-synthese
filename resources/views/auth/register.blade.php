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
            <p style="color: var(--text-muted);">Rejoignez-nous pour partager vos idées.</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label style="font-weight: 600; font-size: 0.9rem;">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Votre nom">
                @error('name') <span style="color: var(--accent-red); font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-1">
                <label style="font-weight: 600; font-size: 0.9rem;">Adresse Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@exemple.com">
                @error('email') <span style="color: var(--accent-red); font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-1">
                <label style="font-weight: 600; font-size: 0.9rem;">Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••">
                @error('password') <span style="color: var(--accent-red); font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label style="font-weight: 600; font-size: 0.9rem;">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <button type="submit" class="nav-btn pulse-hover" style="width: 100%; border: none;">Créer mon compte</button>
        </form>

        <div class="text-center mt-2" style="font-size: 0.9rem;">
            <span style="color: var(--text-muted);">Déjà inscrit ?</span>
            <a href="{{ route('login') }}" style="font-weight: 700;">Se connecter</a>
        </div>
    </div>
</div>
@endsection
