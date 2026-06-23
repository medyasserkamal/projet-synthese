@extends('layouts.app')

@section('content')
<div class="feed-header mb-2">
    <h1 class="feed-title">Résultats pour "{{ $query }}"</h1>
</div>

<!-- Section Utilisateurs -->
<section class="search-section animate-fade-in">
    <h2 class="search-section-title">
        <i data-feather="users"></i> Utilisateurs
    </h2>
    <div class="search-grid">
        @forelse($users as $user)
            <div class="user-card" onclick="window.location='{{ route('profile.public', $user->id) }}'" style="cursor: pointer;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar">
                @else
                    <div class="avatar" style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="user-card-info">
                    <span class="user-card-name">{{ $user->name }}</span>
                    <div class="user-card-stats">{{ $user->followers_count }} abonnés &bull; {{ $user->articles_count }} articles</div>
                </div>
                @auth
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.follow', $user->id) }}" method="POST" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="follow-btn">
                                {{ auth()->user()->followings->contains($user->id) ? 'Suivi' : 'Suivre' }}
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="follow-btn" onclick="event.stopPropagation()">Suivre</a>
                @endauth
            </div>
        @empty
            <p style="color: var(--text-muted);">Aucun utilisateur trouvé.</p>
        @endforelse
    </div>
</section>

<!-- Section Articles -->
<section class="search-section animate-fade-in" style="animation-delay: 0.1s;">
    <h2 class="search-section-title">
        <i data-feather="file-text"></i> Articles
    </h2>
    <div class="articles-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @forelse($articles as $article)
            <article class="article-card" onclick="window.location='{{ route('articles.show', $article->slug) }}'" style="cursor: pointer;">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image" style="height: 180px;">
                @endif
                <div class="article-content">
                    <a href="{{ route('home', ['category' => $article->category->slug ?? '']) }}" class="tag" onclick="event.stopPropagation()">
                        {{ $article->category->name ?? 'Général' }}
                    </a>
                    <h3 class="article-title">
                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                    <p class="article-excerpt">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                    <div class="article-meta">
                        <a href="{{ route('profile.public', $article->user->id) }}" class="author-info" style="text-decoration: none;" onclick="event.stopPropagation()">
                            @if($article->user->avatar)
                                <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="{{ $article->user->name }}" class="avatar" style="width: 24px; height: 24px;">
                            @else
                                <div class="avatar" style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.6rem;">
                                    {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="author-name">{{ $article->user->name }}</span>
                        </a>
                        <span class="article-date">{{ $article->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </article>
        @empty
            <p style="color: var(--text-muted);">Aucun article trouvé.</p>
        @endforelse
    </div>
</section>
@endsection
