@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 800px; margin: 0 auto;">
    
    <div class="d-flex align-center mb-2" style="gap: 1rem;">
        <a href="{{ route('articles.show', $article->slug) }}" class="pulse-hover" style="color: var(--text-muted); background: var(--surface-color); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid var(--border-color);">
            <i data-feather="arrow-left" style="width: 20px;"></i>
        </a>
        <h1 style="margin: 0;">Modifier l'article</h1>
    </div>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="card" style="padding: 2rem;">
        @csrf
        @method('PUT')
        
        <!-- Zone d'upload d'image -->
        <div class="mb-2">
            <label style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Image de couverture</label>
            <div id="image-dropzone" style="width: 100%; height: 250px; border: 2px dashed {{ $article->image ? 'var(--primary-color)' : 'var(--border-color)' }}; border-radius: var(--radius-md); border-style: {{ $article->image ? 'solid' : 'dashed' }}; display: flex; flex-column; align-items: center; justify-content: center; cursor: pointer; position: relative; overflow: hidden; background: var(--bg-color); transition: var(--transition-smooth);">
                <div id="dropzone-prompt" class="text-center" style="{{ $article->image ? 'display: none;' : '' }}">
                    <i data-feather="image" style="width: 40px; height: 40px; color: var(--primary-color); opacity: 0.5; margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Cliquez ou glissez une image ici</p>
                </div>
                <img id="image-preview" src="{{ $article->image ? asset('storage/' . $article->image) : '#' }}" alt="Aperçu" style="{{ $article->image ? 'display: block;' : 'display: none;' }} width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
                <input type="file" name="image" id="article-image" style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewArticleImage(this)">
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Laissez vide pour conserver l'image actuelle.</p>
            @error('image')
                <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="title" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Titre de l'article</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" placeholder="Comment j'ai découvert Laravel..." required style="font-size: 1.2rem; font-weight: 600;">
            @error('title')
                <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="slug" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">URL personnalisée (Slug)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}" placeholder="mon-bel-article" required>
            @error('slug')
                <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="category_id" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Catégorie</label>
            <select name="category_id" id="category_id" required>
                <option value="">Sélectionnez une catégorie</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="content" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Contenu de l'article</label>
            <textarea name="content" id="content" rows="15" placeholder="Racontez votre histoire..." required style="resize: vertical; line-height: 1.6;">{{ old('content', $article->content) }}</textarea>
            @error('content')
                <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="status" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Statut</label>
            <select name="status" id="status" required>
                <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>Publié</option>
                <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
            </select>
        </div>

        <div class="d-flex justify-between align-center">
            <button type="submit" class="nav-btn pulse-hover" style="border: none; padding: 0.8rem 2rem; font-size: 1rem;">
                Enregistrer les modifications
            </button>
        </div>
    </form>
    
    <div class="card mt-2" style="border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.02);">
        <h3 style="color: var(--accent-red); margin-bottom: 1rem;">Zone de danger</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">Une fois supprimé, vous ne pourrez plus récupérer cet article.</p>
        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article définitivement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-unfollow" style="background: var(--accent-red); color: white; border: none; padding: 0.75rem 1.5rem;">
                Supprimer l'article définitivement
            </button>
        </form>
    </div>
</div>

<script>
    feather.replace();

    function previewArticleImage(input) {
        const preview = document.getElementById('image-preview');
        const prompt = document.getElementById('dropzone-prompt');
        const dropzone = document.getElementById('image-dropzone');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                prompt.style.display = 'none';
                dropzone.style.borderStyle = 'solid';
                dropzone.style.borderColor = 'var(--primary-color)';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
