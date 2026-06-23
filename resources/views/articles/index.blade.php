@extends('layouts.app')

@section('content')
    @if(isset($isExplore) && $isExplore)
        <div class="mb-2 d-flex align-center" style="gap: 1rem;">
            <div style="background: var(--primary-color); color: white; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                <i data-feather="trending-up" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 1.75rem;">Explorer les Tendances</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Découvrez les articles les plus populaires de la communauté.</p>
            </div>
        </div>
    @endif

    @if(isset($isBookmarks) && $isBookmarks)
        <div class="mb-2 d-flex align-center" style="gap: 1rem;">
            <div style="background: var(--primary-color); color: white; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
                <i data-feather="bookmark" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 1.75rem;">Mes Signets</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Retrouvez ici tous les articles que vous avez sauvegardés.</p>
            </div>
        </div>
    @endif
<!-- Header du Feed -->
<div class="feed-header animate-fade-in">
    <h1 class="feed-title">Pour vous</h1>
    <div class="d-flex" style="gap: 1rem;">
        <span style="font-weight: 600; color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; cursor: pointer;">Récent</span>
        <span style="font-weight: 600; color: var(--text-muted); cursor: pointer;">Suivis</span>
    </div>
</div>

<!-- Liste des Articles -->
@forelse($articles as $index => $article)
    <!-- Article Featured (Le premier) ou Standard -->
    <article class="article-card {{ $index === 0 ? 'featured' : '' }} animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s;" onclick="window.location='{{ route('articles.show', $article->slug) }}'">
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
        @else
            <!-- Image placeholder générique si pas d'image -->
            <div class="article-image" style="background: linear-gradient(45deg, var(--border-color), var(--bg-color)); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                <i data-feather="image" style="width: 48px; height: 48px; opacity: 0.5;"></i>
            </div>
        @endif
        
        <div class="article-content">
            @if($article->category)
                <a href="{{ route('home', ['category' => $article->category->slug]) }}" class="tag">{{ $article->category->name }}</a>
            @else
                <span class="tag">Général</span>
            @endif
            <h2 class="article-title">{{ $article->title }}</h2>
            <p class="article-excerpt">{{ Str::limit(strip_tags($article->content), $index === 0 ? 150 : 100) }}</p>
            
            <div class="article-meta">
                <a href="{{ route('profile.public', $article->user->id) }}" class="author-info" onclick="event.stopPropagation()" style="text-decoration: none; color: inherit;">
                    @if($article->user && $article->user->avatar)
                        <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="{{ $article->user->name }}" class="avatar" style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <div class="avatar" style="width: 32px; height: 32px; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; border-radius: 50%;">
                            {{ $article->user ? strtoupper(substr($article->user->name, 0, 1)) : 'Z' }}
                        </div>
                    @endif
                    <div>
                        <div class="author-name" style="display: flex; align-items: center; gap: 4px;">
                            {{ $article->user ? $article->user->name : 'Auteur Zarticle' }}
                            @if($article->user && $article->user->is_verified)
                                @include('partials.verified-badge', ['size' => '14px'])
                            @endif
                        </div>
                        <div class="article-date">{{ $article->created_at->format('d M Y') }} &bull; {{ rand(3, 10) }} min de lecture</div>
                    </div>
                </a>
                <div style="display: flex; gap: 0.5rem; color: var(--text-muted);">
                    @auth
                        <form action="{{ route('articles.like', $article->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="d-flex align-center pulse-hover" style="gap: 0.25rem; color: {{ auth()->user()->likes->contains($article->id) ? 'var(--accent-red)' : 'var(--text-muted)' }};">
                                <i data-feather="heart" style="width: 16px; fill: {{ auth()->user()->likes->contains($article->id) ? 'var(--accent-red)' : 'none' }};"></i> <span style="font-size: 0.8rem;">{{ $article->likes->count() }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="d-flex align-center pulse-hover" style="gap: 0.25rem; color: var(--text-muted);">
                            <i data-feather="heart" style="width: 16px;"></i> <span style="font-size: 0.8rem;">{{ $article->likes->count() }}</span>
                        </a>
                    @endauth

                    <a href="{{ route('articles.show', $article->slug) }}#comments" class="d-flex align-center pulse-hover" style="gap: 0.25rem; color: var(--text-muted);">
                        <i data-feather="message-circle" style="width: 16px;"></i> <span style="font-size: 0.8rem;">{{ $article->comments->count() }}</span>
                    </a>
                </div>
            </div>
        </div>
    </article>
@empty
    <div class="widget-box text-center" style="margin-top: 2rem;">
        <i data-feather="inbox" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
        <h3>Aucun article n'a été publié pour le moment.</h3>
        <p style="color: var(--text-muted);">Revenez plus tard ou soyez le premier à écrire !</p>
    </div>
@endforelse

<div class="mt-2">
    {{ $articles->links() }}
</div>
@endsection
