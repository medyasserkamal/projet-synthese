<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        Comment::create([
            'article_id' => $request->article_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        return back()->with('success', 'Votre commentaire a été posté.');
    }

    public function like(Comment $comment)
    {
        $user = auth()->user();
        
        if ($user->commentLikes()->where('comment_id', $comment->id)->exists()) {
            $user->commentLikes()->detach($comment->id);
        } else {
            $user->commentLikes()->attach($comment->id);
        }

        return back();
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() == $comment->user_id) {
            $comment->delete();
        }
        return back()->with('success', 'Commentaire supprimé.');
    }
}
