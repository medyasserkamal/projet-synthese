@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="width: 100%;">
    <div class="mb-2 d-flex align-center justify-between">
        <div>
            <h1 style="margin: 0; font-family: 'Outfit', sans-serif; font-size: 2rem;">Tableau de Bord Administration</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">Statistiques globales et activités de modération.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Articles Card -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border-radius: var(--radius-lg); transition: var(--transition-bounce);">
            <div style="background: rgba(14, 165, 233, 0.1); color: var(--primary-color); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-feather="file-text" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">Total Articles</div>
                <div style="font-size: 2rem; font-weight: 800; line-height: 1.2;">{{ $articlesCount }}</div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border-radius: var(--radius-lg); transition: var(--transition-bounce);">
            <div style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-feather="users" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">Total Utilisateurs</div>
                <div style="font-size: 2rem; font-weight: 800; line-height: 1.2;">{{ $usersCount }}</div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border-radius: var(--radius-lg); transition: var(--transition-bounce);">
            <div style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-feather="tag" style="width: 28px; height: 28px;"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">Catégories</div>
                <div style="font-size: 2rem; font-weight: 800; line-height: 1.2;">{{ $categoriesCount }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Widgets -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="card" style="padding: 1.5rem; border-radius: var(--radius-lg);">
            <h3 style="margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">Liens Rapides</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-view" style="justify-content: flex-start; padding: 0.75rem 1.25rem;">
                    <i data-feather="file-text"></i> Modérer les Articles
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-view" style="justify-content: flex-start; padding: 0.75rem 1.25rem; color: var(--accent-green) !important; border-color: rgba(16, 185, 129, 0.2); background: rgba(16, 185, 129, 0.05);">
                    <i data-feather="users" style="color: var(--accent-green);"></i> Gérer les Utilisateurs (Vérification)
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-view" style="justify-content: flex-start; padding: 0.75rem 1.25rem; color: var(--accent-red) !important; border-color: rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.05);">
                    <i data-feather="tag" style="color: var(--accent-red);"></i> Gérer les Catégories (CRUD)
                </a>
            </div>
        </div>

        <div class="card" style="padding: 1.5rem; border-radius: var(--radius-lg);">
            <h3 style="margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">Nouveaux Membres</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentUsers as $user)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600; font-size: 0.9rem;">
                                    {{ $user->name }}
                                    @if($user->is_verified)
                                        @include('partials.verified-badge', ['size' => '12px'])
                                    @endif
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->created_at->format('d/m') }}</span>
                    </div>
                @empty
                    <p style="font-size: 0.85rem; color: var(--text-muted);">Aucun utilisateur récent.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Articles Card -->
    <div class="card" style="padding: 1.5rem; border-radius: var(--radius-lg);">
        <h3 style="margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">Dernières Publications</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 0.75rem 0.5rem; color: var(--text-muted);">Titre</th>
                        <th style="padding: 0.75rem 0.5rem; color: var(--text-muted);">Auteur</th>
                        <th style="padding: 0.75rem 0.5rem; color: var(--text-muted);">Date</th>
                        <th style="padding: 0.75rem 0.5rem; color: var(--text-muted);">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentArticles as $article)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.75rem 0.5rem; font-weight: 600;">
                                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" style="color: inherit;">
                                    {{ Str::limit($article->title, 40) }}
                                </a>
                            </td>
                            <td style="padding: 0.75rem 0.5rem;">{{ $article->user ? $article->user->name : 'Inconnu' }}</td>
                            <td style="padding: 0.75rem 0.5rem;">{{ $article->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 0.75rem 0.5rem;">
                                @if($article->is_blocked)
                                    <span style="background-color: rgba(239, 68, 68, 0.2); color: var(--accent-red); padding: 0.2rem 0.6rem; border-radius: var(--radius-pill); font-size: 0.75rem; font-weight: 700;">Bloqué</span>
                                @else
                                    <span style="background-color: rgba(16, 185, 129, 0.2); color: var(--accent-green); padding: 0.2rem 0.6rem; border-radius: var(--radius-pill); font-size: 0.75rem; font-weight: 700;">Actif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">Aucun article publié récemment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
