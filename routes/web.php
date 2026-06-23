<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SocialController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

// Articles
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/explore', [ArticleController::class, 'explore'])->name('articles.explore');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store')->middleware('auth');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::middleware('auth')->group(function () {
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// Profil Utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/bookmarks', [ArticleController::class, 'bookmarks'])->name('articles.bookmarks');
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [ProfileController::class, 'readNotification'])->name('notifications.read');
    Route::post('/notifications/mark-as-read', [ProfileController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');
});

// Recherche et Profils Publics
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/users/{user}', [ProfileController::class, 'publicProfile'])->name('profile.public');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Interactions Sociales
Route::middleware('auth')->group(function () {
    Route::post('/articles/{article}/like', [SocialController::class, 'toggleLike'])->name('articles.like');
    Route::post('/articles/{article}/favorite', [SocialController::class, 'toggleFavorite'])->name('articles.favorite');
    Route::post('/users/{user}/follow', [SocialController::class, 'toggleFollow'])->name('users.follow');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Espace d'administration sécurisé
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Modération des articles
    Route::get('articles-list', [ArticleController::class, 'adminIndex'])->name('articles.index');
    Route::resource('articles', ArticleController::class)->except(['show', 'index']);
    Route::patch('articles/{article}/toggle-block', [\App\Http\Controllers\AdminController::class, 'toggleBlock'])->name('articles.toggle-block');

    // Gestion des catégories
    Route::resource('categories', ArticleCategoryController::class)->except(['show']);

    // Gestion des utilisateurs
    Route::get('users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::patch('users/{user}/toggle-verify', [\App\Http\Controllers\AdminController::class, 'toggleVerify'])->name('users.toggle-verify');
    Route::patch('users/{user}/toggle-ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('users.toggle-ban');
});
