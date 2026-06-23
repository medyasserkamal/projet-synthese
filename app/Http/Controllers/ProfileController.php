<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['followings', 'followers', 'favorites']);
        return view('auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar si existant
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function publicProfile(User $user)
    {
        $user->loadCount(['followers', 'followings', 'articles']);
        $articles = $user->articles()->where('status', 'published')->latest()->paginate(12);
        
        return view('users.show', compact('user', 'articles'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->unreadNotifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function readNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->route('articles.show', $notification->data['article_slug']);
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
