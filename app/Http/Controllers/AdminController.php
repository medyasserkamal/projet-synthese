<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the administration dashboard.
     */
    public function dashboard()
    {
        $articlesCount = Article::count();
        $usersCount = User::count();
        $categoriesCount = ArticleCategory::count();

        // Récupérer les éléments récents pour enrichir le tableau de bord
        $recentArticles = Article::with(['user', 'category'])->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'articlesCount',
            'usersCount',
            'categoriesCount',
            'recentArticles',
            'recentUsers'
        ));
    }

    /**
     * Display a listing of the users.
     */
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user verification status.
     */
    public function toggleVerify(User $user)
    {
        $user->is_verified = !$user->is_verified;
        $user->save();

        $message = $user->is_verified 
            ? "L'utilisateur {$user->name} a été vérifié avec succès." 
            : "La marque de vérification de l'utilisateur {$user->name} a été retirée.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Toggle article blocked status.
     */
    public function toggleBlock(Article $article)
    {
        $article->is_blocked = !$article->is_blocked;
        $article->save();

        $message = $article->is_blocked 
            ? "L'article \"{$article->title}\" a été bloqué avec succès." 
            : "L'article \"{$article->title}\" a été débloqué avec succès.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan(User $user)
    {
        // Empêcher l'administrateur de s'auto-bannir
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Vous ne pouvez pas vous bannir vous-même.']);
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $message = $user->is_banned 
            ? "L'utilisateur {$user->name} a été banni avec succès." 
            : "L'utilisateur {$user->name} a été débanni avec succès.";

        return redirect()->back()->with('success', $message);
    }
}
