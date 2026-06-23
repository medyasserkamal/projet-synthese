<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function toggleLike(Article $article)
    {
        auth()->user()->likes()->toggle($article->id);
        return back();
    }

    public function toggleFavorite(Article $article)
    {
        auth()->user()->favorites()->toggle($article->id);
        return back();
    }

    public function toggleFollow(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id !== $user->id) {
            $currentUser->followings()->toggle($user->id);
        }
        
        return back();
    }
}
