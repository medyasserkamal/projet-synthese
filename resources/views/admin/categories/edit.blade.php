@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 600px; margin: 0 auto;">
    <div class="d-flex align-center mb-2" style="gap: 1rem;">
        <a href="{{ route('admin.categories.index') }}" class="pulse-hover" style="color: var(--text-muted); background: var(--surface-color); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid var(--border-color);">
            <i data-feather="arrow-left" style="width: 20px;"></i>
        </a>
        <h1 style="margin: 0; font-family: 'Outfit', sans-serif;">Modifier la Catégorie</h1>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="card" style="padding: 2rem; border-radius: var(--radius-lg);">
        @csrf
        @method('PUT')
        
        <div class="mb-2">
            <label for="name" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Nom de la catégorie</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" placeholder="Technologie, Voyage, Cuisine..." required oninput="generateSlug(this.value)">
            @error('name')
                <p style="color: var(--accent-red); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="slug" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Slug (URL)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" placeholder="technologie" required>
            @error('slug')
                <p style="color: var(--accent-red); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="description" style="display: block; font-weight: 700; margin-bottom: 0.5rem;">Description</label>
            <textarea name="description" id="description" rows="5" placeholder="Articles sur les dernières innovations technologiques..." style="resize: vertical;">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p style="color: var(--accent-red); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
            <a href="{{ route('admin.categories.index') }}" class="btn" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-color) !important;">Annuler</a>
            <button type="submit" class="btn">
                <i data-feather="check" style="width: 18px; height: 18px;"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    feather.replace();

    function generateSlug(text) {
        const slug = text.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // remove accents
            .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes
        document.getElementById('slug').value = slug;
    }
</script>
@endsection
