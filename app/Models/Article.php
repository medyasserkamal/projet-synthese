<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'content', 'image', 'status', 'is_blocked'];

    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Utilisateurs qui ont aimé cet article
    public function likes()
    {
        return $this->belongsToMany(User::class, 'article_user_like');
    }

    // Utilisateurs qui ont mis cet article en favoris
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'article_user_favorite');
    }
}
