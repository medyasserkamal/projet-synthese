@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Gestion des Catégories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-edit">
        <i data-feather="plus"></i> Créer une Catégorie
    </a>
</div>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color);">
                <th style="padding: 1rem;">Nom</th>
                <th style="padding: 1rem;">Slug</th>
                <th style="padding: 1rem;">Description</th>
                <th style="padding: 1rem; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 1rem; font-weight: 600;">{{ $category->name }}</td>
                <td style="padding: 1rem;">{{ $category->slug }}</td>
                <td style="padding: 1rem;">{{ Str::limit($category->description, 50) }}</td>
                <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="icon-btn icon-btn-edit" title="Modifier">
                        <i data-feather="edit"></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn icon-btn-delete" title="Supprimer">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
