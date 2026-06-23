@extends('layouts.app')

@section('content')
<div class="profile-header animate-fade-in">
    <div class="profile-cover"></div>
    <div class="profile-info-container">
        <div class="profile-avatar-wrapper">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar-big">
            @else
                <div class="profile-avatar-big" style="background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 3rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            
            <div class="profile-actions">
                @auth
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.follow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ auth()->user()->followings->contains($user->id) ? 'btn-unfollow' : 'btn-follow' }}">
                                {{ auth()->user()->followings->contains($user->id) ? 'Se désabonner' : 'S\'abonner' }}
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-follow">S'abonner</a>
                @endauth
            </div>
        </div>

        <h1 class="profile-name" style="display: flex; align-items: center; gap: 6px;">
            {{ $user->name }}
            @if($user->is_verified)
                @include('partials.verified-badge', ['size' => '24px'])
            @endif
        </h1>
        <div class="profile-handle">@ {{ Str::slug($user->name, '_') }}</div>
        
        @if($user->bio)
            <p class="profile-bio">{{ $user->bio }}</p>
        @else
            <p class="profile-bio" style="color: var(--text-muted); font-style: italic;">Aucune biographie disponible.</p>
        @endif

        <div class="profile-stats-row">
            <div class="profile-stat">
                <b>{{ $user->followings_count }}</b> <span>abonnements</span>
            </div>
            <div class="profile-stat">
                <b>{{ $user->followers_count }}</b> <span>abonnés</span>
            </div>
            <div class="profile-stat">
                <b>{{ $user->articles_count }}</b> <span>articles</span>
            </div>
        </div>
    </div>
</div>

<div class="profile-tabs">
    <a href="#" class="profile-tab active">Articles</a>
    <a href="#" class="profile-tab">À propos</a>
</div>

<div class="articles-grid animate-fade-in" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @forelse($articles as $article)
        <article class="article-card" onclick="window.location='{{ route('articles.show', $article->slug) }}'" style="cursor: pointer;">
            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
            @endif
            <div class="article-content">
                <a href="{{ route('home', ['category' => $article->category->slug ?? '']) }}" class="tag" onclick="event.stopPropagation()">
                    {{ $article->category->name ?? 'Général' }}
                </a>
                <h3 class="article-title" style="font-size: 1.1rem;">
                    {{ $article->title }}
                </h3>
                <div class="article-meta" style="border: none; padding: 0; margin-top: 1rem;">
                    <div class="d-flex align-center" style="gap: 1rem;">
                        <span class="article-date">{{ $article->created_at->format('d M Y') }}</span>
                        <div class="d-flex align-center" style="gap: 0.5rem; color: var(--text-muted); font-size: 0.8rem;">
                            <i data-feather="heart" style="width: 14px;"></i> {{ $article->likes->count() }}
                        </div>
                    </div>
                    
                    @auth
                        @if(auth()->id() == $article->user_id)
                            <div class="d-flex align-center" style="gap: 0.25rem;" onclick="event.stopPropagation()">
                                <a href="{{ route('articles.edit', $article->id) }}" class="icon-btn icon-btn-edit" style="width: 28px; height: 28px;" title="Modifier">
                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Supprimer ?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn-delete" style="width: 28px; height: 28px;" title="Supprimer">
                                        <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </article>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <p style="color: var(--text-muted);">Cet utilisateur n'a pas encore publié d'articles.</p>
        </div>
    @endforelse
</div>

<div class="mt-2">
    {{ $articles->links() }}
</div>
@endsection
