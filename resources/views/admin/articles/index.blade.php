@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-family: 'Outfit', sans-serif;">Gestion des Articles</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Modérez les publications, modifiez le contenu ou bloquez des articles.</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-view">
                <i data-feather="arrow-left"></i> Tableau de bord
            </a>
            <a href="{{ route('articles.create') }}" class="btn">
                <i data-feather="plus"></i> Créer un Article
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.15); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="overflow-x: auto; border-radius: var(--radius-lg);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem;">Titre</th>
                    <th style="padding: 1rem;">Catégorie</th>
                    <th style="padding: 1rem;">Auteur</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem;">Modération</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                <tr style="border-bottom: 1px solid var(--border-color); background: {{ $article->is_blocked ? 'rgba(239, 68, 68, 0.02)' : 'inherit' }};">
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600;">{{ $article->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Publié le {{ $article->created_at->format('d/m/Y') }}</div>
                    </td>
                    <td style="padding: 1rem;">{{ $article->category ? $article->category->name : 'Général' }}</td>
                    <td style="padding: 1rem; display: flex; align-items: center; gap: 0.5rem; height: 100%;">
                        @if($article->user)
                            <span>{{ $article->user->name }}</span>
                            @if($article->user->is_verified)
                                @include('partials.verified-badge', ['size' => '14px'])
                            @endif
                        @else
                            <span style="color: var(--text-muted);">Inconnu</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <span style="background-color: {{ $article->status == 'published' ? 'rgba(14, 165, 233, 0.2)' : 'rgba(156, 163, 175, 0.2)' }}; color: {{ $article->status == 'published' ? 'var(--primary-color)' : 'inherit' }}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        @if($article->is_blocked)
                            <span style="background-color: rgba(239, 68, 68, 0.2); color: var(--accent-red); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="slash" style="width: 12px; height: 12px;"></i> Bloqué
                            </span>
                        @else
                            <span style="background-color: rgba(16, 185, 129, 0.2); color: var(--accent-green); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i data-feather="check" style="width: 12px; height: 12px;"></i> Actif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            <!-- Bouton Block / Unblock -->
                            <form action="{{ route('admin.articles.toggle-block', $article->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $article->is_blocked ? 'btn-edit' : 'btn-delete' }}" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.25rem;" title="{{ $article->is_blocked ? 'Débloquer' : 'Bloquer' }}">
                                    @if($article->is_blocked)
                                        <i data-feather="unlock" style="width: 12px; height: 12px;"></i> Débloquer
                                    @else
                                        <i data-feather="slash" style="width: 12px; height: 12px;"></i> Bloquer
                                    @endif
                                </button>
                            </form>

                            <!-- Voir -->
                            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="icon-btn icon-btn-edit" style="color: var(--primary-color);" title="Voir en direct">
                                <i data-feather="eye"></i>
                            </a>

                            <!-- Modifier -->
                            <a href="{{ route('articles.edit', $article->id) }}" class="icon-btn icon-btn-edit" title="Modifier">
                                <i data-feather="edit"></i>
                            </a>

                            <!-- Supprimer -->
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn icon-btn-delete" title="Supprimer">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="padding: 1rem;">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
