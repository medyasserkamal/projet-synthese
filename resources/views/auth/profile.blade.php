@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 900px; margin: 0 auto;">
    
    @if(session('success'))
        <div class="card" style="background: rgba(16, 185, 129, 0.1); border-color: #10b981; color: #065f46; margin-bottom: 1rem; padding: 1rem;">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- En-tête du profil -->
    <div class="card" style="padding: 2.5rem; margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100px; background: linear-gradient(90deg, var(--primary-color), var(--accent-blue)); opacity: 0.1; z-index: 0;"></div>
        
        <div class="d-flex align-center" style="position: relative; z-index: 1; gap: 2rem; flex-wrap: wrap;">
            <div style="position: relative;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" id="avatar-preview" alt="{{ $user->name }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--surface-color); box-shadow: var(--shadow-md);">
                @else
                    <div id="avatar-placeholder" style="width: 120px; height: 120px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800; border: 4px solid var(--surface-color); box-shadow: var(--shadow-md);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <!-- Bouton Stylo pour changer l'avatar -->
                <label for="avatar-input" class="pulse-hover" style="position: absolute; bottom: 5px; right: 5px; background: var(--primary-color); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid var(--surface-color); box-shadow: var(--shadow-sm); transition: var(--transition-bounce);">
                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                </label>
            </div>

            <div style="flex: 1;">
                <h1 style="margin: 0; font-size: 2rem; display: inline-flex; align-items: center; gap: 6px;">
                    {{ $user->name }}
                    @if($user->is_verified)
                        @include('partials.verified-badge', ['size' => '24px'])
                    @endif
                </h1>
                <p style="color: var(--text-muted); margin-bottom: 0.5rem;">@ {{ Str::slug($user->name, '_') }}</p>
                
                <div class="d-flex" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="text-center">
                        <div style="font-weight: 800; font-size: 1.2rem;">{{ $user->followings->count() }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Abonnements</div>
                    </div>
                    <div class="text-center">
                        <div style="font-weight: 800; font-size: 1.2rem;">{{ $user->followers->count() }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Abonnés</div>
                    </div>
                    <div class="text-center">
                        <div style="font-weight: 800; font-size: 1.2rem;">{{ $user->favorites->count() }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Favoris</div>
                    </div>
                </div>

                @if($user->bio)
                    <p style="font-size: 1rem; line-height: 1.5; color: var(--text-color); max-width: 600px; margin: 0;">
                        {{ $user->bio }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="d-grid" style="grid-template-columns: 1fr 1.5fr; gap: 2rem;">
        
        <!-- Colonne Gauche : Paramètres -->
        <div>
            <div class="card" style="padding: 1.5rem;">
                <h3 style="margin-top: 0;">Modifier mon profil</h3>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Input file caché -->
                    <input type="file" name="avatar" id="avatar-input" style="display: none;" onchange="previewImage(this)">

                    <div class="mb-1">
                        <label style="font-size: 0.85rem; font-weight: 600;">Nom complet</label>
                        <input type="text" name="name" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-1">
                        <label style="font-size: 0.85rem; font-weight: 600;">Bio</label>
                        <textarea name="bio" rows="3" placeholder="Parlez-nous de vous...">{{ $user->bio }}</textarea>
                    </div>

                    <button type="submit" class="nav-btn pulse-hover" style="width: 100%; border: none;">Enregistrer les modifications</button>
                </form>
            </div>
        </div>

        <!-- Colonne Droite : Listes -->
        <div class="d-flex flex-column" style="gap: 2rem;">
            
            <!-- Favoris -->
            <div class="widget-box" style="margin: 0; padding: 1.5rem;">
                <h3 class="widget-title">Mes Favoris</h3>
                @forelse($user->favorites as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="d-flex align-center mb-1 pulse-hover" style="gap: 1rem; color: var(--text-color); text-decoration: none;">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" style="width: 50px; height: 50px; border-radius: var(--radius-sm); object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); background: var(--border-color); display: flex; align-items: center; justify-content: center;">
                                <i data-feather="image" style="width: 20px; opacity: 0.5;"></i>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $article->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $article->category->name ?? 'Général' }}</div>
                        </div>
                    </a>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.85rem;">Aucun article en favori.</p>
                @endforelse
            </div>

            <!-- Abonnements -->
            <div class="widget-box" style="margin: 0; padding: 1.5rem;">
                <h3 class="widget-title">Mes Abonnements</h3>
                <div class="d-flex flex-wrap" style="gap: 1rem;">
                    @forelse($user->followings as $following)
                        <div class="text-center" style="width: 80px;">
                            @if($following->avatar)
                                <img src="{{ asset('storage/' . $following->avatar) }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-bottom: 0.25rem;">
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.25rem; font-weight: 700;">
                                    {{ strtoupper(substr($following->name, 0, 1)) }}
                                </div>
                            @endif
                            <div style="font-size: 0.75rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $following->name }}</div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 0.85rem;">Vous ne suivez personne.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Section Mes Articles -->
    <div class="card mt-2 animate-fade-in" style="padding: 2rem; animation-delay: 0.1s;">
        <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="file-text" style="color: var(--primary-color);"></i>
            Mes Articles
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            @forelse($user->articles as $article)
                <div class="article-card" style="display: flex; flex-direction: column; height: 100%;">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="article-image" style="height: 150px;">
                    @else
                        <div style="height: 150px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                            <i data-feather="image"></i>
                        </div>
                    @endif
                    <div class="article-content" style="flex: 1; display: flex; flex-direction: column; padding: 1rem;">
                        <h4 style="margin-bottom: 0.5rem; font-size: 1rem;">{{ $article->title }}</h4>
                        <div style="margin-top: auto; display: flex; gap: 0.25rem; justify-content: flex-end; padding-top: 0.5rem; border-top: 1px solid var(--border-color);">
                            <a href="{{ route('articles.show', $article->slug) }}" class="icon-btn icon-btn-edit" style="color: var(--primary-color);" title="Voir">
                                <i data-feather="eye"></i>
                            </a>
                            <a href="{{ route('articles.edit', $article->id) }}" class="icon-btn icon-btn-edit" title="Modifier">
                                <i data-feather="edit"></i>
                            </a>
                            <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Supprimer ?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn icon-btn-delete" title="Supprimer">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--text-muted);">
                    Vous n'avez pas encore publié d'articles. <a href="{{ route('articles.create') }}" style="color: var(--primary-color); font-weight: 600;">Commencer à écrire</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    feather.replace();

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Remplacer l'image ou le placeholder par la nouvelle image
                var preview = document.getElementById('avatar-preview');
                var placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    var img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.style.width = '120px';
                    img.style.height = '120px';
                    img.style.borderRadius = '50%';
                    img.style.objectFit = 'cover';
                    img.style.border = '4px solid var(--surface-color)';
                    img.style.boxShadow = 'var(--shadow-md)';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
                
                // Soumettre automatiquement le formulaire pour enregistrer l'avatar
                setTimeout(function() {
                    document.getElementById('profile-form').submit();
                }, 500);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
