@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-family: 'Outfit', sans-serif;">Gestion des Utilisateurs</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Gérez les comptes utilisateurs, les badges de vérification et les permissions.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-view">
            <i data-feather="arrow-left"></i> Tableau de bord
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.15); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.15); color: var(--accent-red); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card" style="overflow-x: auto; border-radius: var(--radius-lg);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem;">Utilisateur</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Rôle</th>
                    <th style="padding: 1rem;">Statut de Vérification</th>
                    <th style="padding: 1rem;">Statut du Compte</th>
                    <th style="padding: 1rem; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <span style="font-weight: 600;">{{ $user->name }}</span>
                            @if($user->is_verified)
                                @include('partials.verified-badge', ['size' => '14px'])
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1rem;">{{ $user->email }}</td>
                    <td style="padding: 1rem;">
                        @if($user->isAdmin())
                            <span style="background-color: rgba(14, 165, 233, 0.2); color: var(--primary-color); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                                Admin
                            </span>
                        @else
                            <span style="background-color: rgba(156, 163, 175, 0.2); color: var(--text-muted); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                                Membre
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($user->is_verified)
                            <span style="color: var(--primary-color); font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="check" style="width: 16px; height: 16px;"></i> Vérifié
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-weight: 500;">
                                Non vérifié
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        @if($user->is_banned)
                            <span style="color: var(--accent-red); font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="slash" style="width: 16px; height: 16px;"></i> Suspendu
                            </span>
                        @else
                            <span style="color: var(--accent-green); font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="check-circle" style="width: 16px; height: 16px;"></i> Actif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end; align-items: center; width: 100%;">
                            <form action="{{ route('admin.users.toggle-verify', $user->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $user->is_verified ? 'btn-unfollow' : 'btn-follow' }}" style="padding: 0.4rem 1rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; height: 32px;">
                                    {{ $user->is_verified ? 'Dé-vérifier' : 'Vérifier' }}
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('{{ $user->is_banned ? 'Voulez-vous réactiver ce compte ?' : 'Êtes-vous sûr de vouloir suspendre ce compte ?' }}')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm" style="padding: 0.4rem 1rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; height: 32px; background-color: {{ $user->is_banned ? 'var(--accent-green)' : 'var(--accent-red)' }}; color: white; border: none; border-radius: var(--radius-sm);">
                                        {{ $user->is_banned ? 'Réactiver' : 'Bannir' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="padding: 1rem;">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
