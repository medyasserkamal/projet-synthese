<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Article;
use App\Models\User;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            // Récupérer les articles tendances (basé sur le nombre de likes ou simplement les plus récents pour cet exemple)
            $trending = Article::where('status', 'published')
                ->where('is_blocked', false)
                ->withCount('likes')
                ->orderBy('likes_count', 'desc')
                ->take(3)
                ->get();

            // Récupérer des créateurs à suivre (exclure l'utilisateur actuel si connecté)
            $creatorsQuery = User::query();
            if (auth()->check()) {
                $creatorsQuery->where('id', '!=', auth()->id());
            }
            $creators = $creatorsQuery->take(3)->get();

            $categories = \App\Models\ArticleCategory::all();

            $view->with('trending', $trending)
                 ->with('creators', $creators)
                 ->with('categories', $categories);
        });
    }
}
