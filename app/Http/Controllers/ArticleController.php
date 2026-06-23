<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Notifications\NewArticlePublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'comments', 'likes'])->where('status', 'published')->where('is_blocked', false);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $articles = $query->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    public function explore()
    {
        // Récupérer les articles triés par le total des réactions (Popularité)
        $articles = Article::with(['user', 'category', 'comments', 'likes'])
            ->where('status', 'published')
            ->where('is_blocked', false)
            ->withCount(['likes', 'comments', 'favorites'])
            ->orderByRaw('(likes_count + comments_count + favorites_count) DESC')
            ->paginate(12);

        $isExplore = true;
        return view('articles.index', compact('articles', 'isExplore'));
    }

    public function bookmarks()
    {
        // Récupérer les articles mis en favoris par l'utilisateur connecté
        $articles = auth()->user()->favorites()
            ->with(['user', 'category', 'comments', 'likes'])
            ->latest()
            ->paginate(10);

        $isBookmarks = true;
        return view('articles.index', compact('articles', 'isBookmarks'));
    }

    public function show($slug)
    {
        $article = Article::with(['user', 'category', 'comments.user'])->where('slug', $slug)->firstOrFail();
        
        // Si l'article est bloqué, seul l'auteur ou un administrateur peut le voir
        if ($article->is_blocked) {
            if (!auth()->check() || (auth()->id() !== $article->user_id && !auth()->user()->isAdmin())) {
                abort(403, "Cet article a été bloqué par la modération.");
            }
        }
        
        return view('articles.show', compact('article'));
    }

    public function adminIndex()
    {
        $articles = Article::with(['user', 'category'])->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = ArticleCategory::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:article_categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 'published'; // Par défaut publié pour les utilisateurs
        
        // Génération automatique du slug
        $data['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . uniqid();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create($data);

        // Envoyer des notifications aux abonnés
        $followers = auth()->user()->followers;
        Notification::send($followers, new NewArticlePublished($article));

        return redirect()->route('home')->with('success', 'Votre article a été publié avec succès !');
    }

    public function edit(Article $article)
    {
        if (auth()->id() !== $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $categories = ArticleCategory::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if (auth()->id() !== $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug,'.$article->id.'|max:255',
            'category_id' => 'nullable|exists:article_categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour avec succès.');
        }

        return redirect()->route('articles.show', $article->slug)->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article)
    {
        if (auth()->id() !== $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $article->delete();

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.articles.index')->with('success', 'Article supprimé avec succès.');
        }

        return redirect()->route('home')->with('success', 'Article supprimé avec succès.');
    }
}
