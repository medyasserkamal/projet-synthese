@extends('layouts.app')

@section('content')
<article class="card" style="max-width: 800px; margin: 0 auto;">
    @if($article->image)
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" style="width: 100%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 2rem;">
    @endif

    <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <span style="font-size: 0.9rem; color: var(--primary-color); font-weight: 600; text-transform: uppercase;">
            {{ $article->category ? $article->category->name : 'Général' }}
        </span>
        <h1 style="font-size: 2.5rem; margin: 0.5rem 0;">{{ $article->title }}</h1>
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.9rem;">
                <a href="{{ route('profile.public', $article->user->id) }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: inherit;">
                    @if($article->user && $article->user->avatar)
                        <img src="{{ asset('storage/' . $article->user->avatar) }}" alt="{{ $article->user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">
                            {{ $article->user ? strtoupper(substr($article->user->name, 0, 1)) : 'Z' }}
                        </div>
                    @endif
                    <span style="display: inline-flex; align-items: center; gap: 4px;">Par <strong style="color: var(--primary-color);">{{ $article->user ? $article->user->name : 'Auteur' }}</strong>
                        @if($article->user && $article->user->is_verified)
                            @include('partials.verified-badge', ['size' => '14px'])
                        @endif
                    </span>
                </a>
                @if($article->user && auth()->id() != $article->user->id)
                <form action="{{ route('users.follow', $article->user->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="follow-btn" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">
                        {{ auth()->check() && auth()->user()->followings->contains($article->user->id) ? 'Suivi' : 'Suivre' }}
                    </button>
                </form>
                @endif
                <span style="opacity: 0.6;">&bull;</span>
                <span style="opacity: 0.6;">Le {{ $article->created_at->format('d/m/Y') }}</span>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                @auth
                    <!-- Formulaire Like -->
                    <form action="{{ route('articles.like', $article->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="pulse-hover" style="display: flex; align-items: center; gap: 0.5rem; color: {{ auth()->user()->likes->contains($article->id) ? 'var(--accent-red)' : 'var(--text-muted)' }};">
                            <i data-feather="heart" style="fill: {{ auth()->user()->likes->contains($article->id) ? 'var(--accent-red)' : 'none' }};"></i>
                            <span>{{ $article->likes->count() }}</span>
                        </button>
                    </form>

                    <!-- Formulaire Favoris -->
                    <form action="{{ route('articles.favorite', $article->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="pulse-hover" style="display: flex; align-items: center; gap: 0.5rem; color: {{ auth()->user()->favorites->contains($article->id) ? 'var(--primary-color)' : 'var(--text-muted)' }};">
                            <i data-feather="bookmark" style="fill: {{ auth()->user()->favorites->contains($article->id) ? 'var(--primary-color)' : 'none' }};"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="pulse-hover" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                        <i data-feather="heart"></i>
                        <span>{{ $article->likes->count() }}</span>
                    </a>
                    <a href="{{ route('login') }}" class="pulse-hover" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                        <i data-feather="bookmark"></i>
                    </a>
                @endauth

                <!-- Partager -->
                <button onclick="window.open('https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}', '_blank')" class="pulse-hover" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                    <i data-feather="share-2"></i>
                </button>

                @auth
                    @if(auth()->id() == $article->user_id)
                        <a href="{{ route('articles.edit', $article->id) }}" class="icon-btn icon-btn-edit" title="Modifier">
                            <i data-feather="edit"></i>
                        </a>
                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-delete" title="Supprimer">
                                <i data-feather="trash-2"></i>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div style="font-size: 1.1rem; line-height: 1.8;">
        {!! $article->content !!}
    </div>
</article>

<section style="max-width: 800px; margin: 3rem auto;">
    <h3>Commentaires ({{ $article->comments->count() }})</h3>
    
    @auth
        <div class="card" style="margin-bottom: 2rem;">
            <h4>Ajouter un commentaire</h4>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">
                <textarea name="content" rows="3" placeholder="Votre commentaire..." required></textarea>
                <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                    <button type="submit" class="btn" style="padding: 0.6rem 1.5rem;">
                        <i data-feather="send" style="width: 18px; height: 18px; margin-left: 2px;"></i>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="card text-center" style="margin-bottom: 2rem; padding: 2rem;">
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Vous devez être connecté pour publier un commentaire.</p>
            <a href="{{ route('login') }}" class="btn">Se connecter</a>
        </div>
    @endauth
    @foreach($article->comments->where('parent_id', null) as $comment)
        <div class="card animate-fade-in" style="padding: 1.5rem; margin-bottom: 2rem; border-left: 4px solid var(--primary-color); width: 100%; border-radius: var(--radius-lg);">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.25rem;">
                @if($comment->user && $comment->user->avatar)
                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                        {{ $comment->user ? strtoupper(substr($comment->user->name, 0, 1)) : 'Z' }}
                    </div>
                @endif
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <a href="{{ route('profile.public', $comment->user->id) }}" style="text-decoration: none; color: inherit;">
                            <span style="display: inline-flex; align-items: center; gap: 4px;">
                                <strong style="color: var(--primary-color); font-size: 1.1rem; letter-spacing: 0.5px;">{{ $comment->user ? $comment->user->name : 'Utilisateur' }}</strong>
                                @if($comment->user && $comment->user->is_verified)
                                    @include('partials.verified-badge', ['size' => '14px'])
                                @endif
                            </span>
                        </a>
                        <span style="font-size: 0.85rem; color: var(--text-muted); background: var(--bg-color); padding: 0.25rem 0.75rem; border-radius: var(--radius-pill);">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="margin: 0; font-size: 0.95rem; color: var(--text-color); text-align: left;">{{ $comment->content }}</p>
                    
                    <div style="display: flex; gap: 2.5rem; margin-top: 1.25rem; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                        <!-- Like Comment -->
                        <form action="{{ route('comments.like', $comment->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="pulse-hover" style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; color: {{ auth()->check() && $comment->likes->contains(auth()->id()) ? 'var(--accent-red)' : 'var(--text-muted)' }}; font-weight: 600;">
                                <i data-feather="heart" style="width: 18px; height: 18px; fill: {{ auth()->check() && $comment->likes->contains(auth()->id()) ? 'var(--accent-red)' : 'none' }};"></i>
                                <span>{{ $comment->likes->count() }}</span>
                            </button>
                        </form>

                        <!-- Reply Toggle -->
                        <button onclick="toggleReplyForm({{ $comment->id }})" class="pulse-hover" style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">
                            <i data-feather="message-square" style="width: 18px; height: 18px;"></i>
                            <span>Répondre</span>
                        </button>
                    </div>

                    <!-- Formulaire de réponse (caché par défaut) -->
                    <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 1.5rem; background: var(--bg-color); padding: 1.25rem; border-radius: var(--radius-lg);">
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="content" rows="2" placeholder="Votre réponse..." required style="font-size: 0.9rem; padding: 0.8rem;"></textarea>
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.5rem;">
                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})" class="btn" style="background: var(--bg-color); color: var(--text-muted); font-size: 0.75rem; padding: 0.4rem 1rem;">Annuler</button>
                                <button type="submit" class="btn" style="font-size: 0.75rem; padding: 0.4rem 1.5rem;">
                                    <i data-feather="send" style="width: 14px; height: 14px; margin-left: 1px;"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Réponses imbriquées -->
            @if($comment->replies->count() > 0)
                <div style="margin-left: 4.5rem; margin-top: 1.5rem; padding-left: 1.5rem; border-left: 3px solid var(--border-color);">
                    @foreach($comment->replies as $reply)
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                            @if($reply->user && $reply->user->avatar)
                                <img src="{{ asset('storage/' . $reply->user->avatar) }}" alt="{{ $reply->user->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                    {{ $reply->user ? strtoupper(substr($reply->user->name, 0, 1)) : 'Z' }}
                                </div>
                            @endif
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                                        <strong style="color: var(--primary-color); font-size: 0.95rem;">{{ $reply->user ? $reply->user->name : 'Utilisateur' }}</strong>
                                        @if($reply->user && $reply->user->is_verified)
                                            @include('partials.verified-badge', ['size' => '12px'])
                                        @endif
                                    </span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="margin: 0; font-size: 0.95rem; color: var(--text-color); text-align: left; line-height: 1.5;">{{ $reply->content }}</p>
                                
                                <div style="display: flex; gap: 1.5rem; margin-top: 0.75rem;">
                                    <form action="{{ route('comments.like', $reply->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: {{ auth()->check() && $reply->likes->contains(auth()->id()) ? 'var(--accent-red)' : 'var(--text-muted)' }};">
                                            <i data-feather="heart" style="width: 14px; height: 14px; fill: {{ auth()->check() && $reply->likes->contains(auth()->id()) ? 'var(--accent-red)' : 'none' }};"></i>
                                            <span>{{ $reply->likes->count() }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</section>

<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
