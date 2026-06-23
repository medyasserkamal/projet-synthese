<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        
        if (!$query) {
            return redirect()->route('home');
        }

        // Rechercher des utilisateurs
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('bio', 'like', "%{$query}%")
            ->withCount(['followers', 'articles'])
            ->take(10)
            ->get();

        // Rechercher des articles
        $articles = Article::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('search', compact('users', 'articles', 'query'));
    }
}
