<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZARTICLE - Plateforme Humaine</title>
    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;800&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <!-- Icônes (Feather Icons ou similaire via CDN) -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="app-container">
    
    <!-- 4. NAVIGATION GAUCHE -->
    <aside class="left-nav">
        <a href="/" class="brand-logo" style="color: var(--text-color);">
            <svg width="160" height="40" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                <!-- Lightning Bolt Z -->
                <path d="M10 30 L50 5 L40 25 L60 25 L20 55 L30 35 L10 35 Z" fill="#0ea5e9" />
                <!-- Text "ARTICLE" - Color adapts to theme -->
                <text x="70" y="42" font-family="monospace" font-size="28" font-weight="bold" fill="currentColor" letter-spacing="2">ARTICLE</text>
            </svg>
        </a>
        
        <nav class="nav-menu">
            <a href="/" class="nav-item active">
                <i data-feather="home"></i>
                <span>Accueil</span>
            </a>
            <a href="{{ route('articles.explore') }}" class="nav-item {{ request()->routeIs('articles.explore') ? 'active' : '' }}">
                <i data-feather="compass"></i>
                <span>Explorer</span>
            </a>
            <a href="{{ route('notifications.index') }}" class="nav-item {{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                <i data-feather="bell"></i>
                <span>Notifications</span>
                @auth
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span style="background: var(--accent-red); color: white; font-size: 0.65rem; padding: 0.1rem 0.4rem; border-radius: 10px; margin-left: auto;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                @endauth
            </a>
            <a href="{{ route('articles.bookmarks') }}" class="nav-item {{ request()->routeIs('articles.bookmarks') ? 'active' : '' }}">
                <i data-feather="bookmark"></i>
                <span>Signets</span>
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i data-feather="sliders"></i>
                        <span>Administration</span>
                    </a>
                @endif
            @endauth
            <a href="#" class="nav-item" onclick="toggleTheme(); return false;">
                <i data-feather="moon" id="theme-icon"></i>
                <span id="theme-text">Mode Sombre</span>
            </a>

            @guest
                <a href="{{ route('login') }}" class="nav-item">
                    <i data-feather="log-in"></i>
                    <span>Connexion</span>
                </a>
                <a href="{{ route('register') }}" class="nav-item">
                    <i data-feather="user-plus"></i>
                    <span>Inscription</span>
                </a>
            @else
                <a href="{{ route('profile.show') }}" class="nav-item">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="avatar" style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span>{{ auth()->user()->name }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: inherit; font-family: inherit;">
                        <i data-feather="log-out"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            @endguest
        </nav>
        
        <a href="{{ route('articles.create') }}" class="nav-btn pulse-hover" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Écrire un article</a>
    </aside>

    <!-- 6. FEED PRINCIPAL -->
    <main class="feed">
        @yield('content')
    </main>

    <!-- 10. SIDEBAR DROITE -->
    <aside class="right-sidebar">
        <!-- 11. Barre de recherche -->
        <form action="{{ route('search') }}" method="GET" class="search-bar">
            <i data-feather="search" style="color: var(--text-muted); width: 18px;"></i>
            <input type="text" name="search" placeholder="Rechercher sur Zarticle..." value="{{ request('search') }}">
        </form>

        <!-- 13. Trending Items -->
        <div class="widget-box animate-fade-in">
            <h3 class="widget-title">Tendances pour vous</h3>
            @forelse($trending as $item)
                <a href="{{ route('articles.show', $item->slug) }}" class="trending-item">
                    <span class="trending-meta">{{ $item->category->name ?? 'Général' }} &bull; {{ $item->likes_count }} j'aime</span>
                    <span class="trending-title">{{ $item->title }}</span>
                </a>
            @empty
                <p style="font-size: 0.85rem; color: var(--text-muted);">Aucune tendance pour le moment.</p>
            @endforelse
        </div>

        <!-- NEW: Category Widget -->
        <div class="widget-box animate-fade-in" style="animation-delay: 0.05s;">
            <h3 class="widget-title">Parcourir par catégories</h3>
            <div class="category-list">
                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->slug]) }}" class="tag">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 14. Sources à suivre -->
        <div class="widget-box animate-fade-in" style="animation-delay: 0.1s;">
            <h3 class="widget-title">Créateurs à suivre</h3>
            @forelse($creators as $creator)
                <div class="follow-item">
                    <a href="{{ route('profile.public', $creator->id) }}" class="d-flex align-center" style="gap: 0.5rem; text-decoration: none;">
                        @if($creator->avatar)
                            <img src="{{ asset('storage/' . $creator->avatar) }}" alt="{{ $creator->name }}" class="avatar">
                        @else
                            <div class="avatar" style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                {{ strtoupper(substr($creator->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $creator->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">@ {{ Str::slug($creator->name, '_') }}</div>
                        </div>
                    </a>
                    @auth
                        <form action="{{ route('users.follow', $creator->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="follow-btn">
                                {{ auth()->user()->followings->contains($creator->id) ? 'Suivi' : 'Suivre' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="follow-btn">Suivre</a>
                    @endauth
                </div>
            @empty
                <p style="font-size: 0.85rem; color: var(--text-muted);">Aucun créateur à suggérer.</p>
            @endforelse
        </div>

        <!-- 15. Newsletter Widget -->
        <div class="widget-box newsletter-widget animate-fade-in" style="animation-delay: 0.2s;">
            <h3 class="widget-title" style="margin-bottom: 0.5rem;">Ne manquez rien !</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Recevez les meilleurs articles de la semaine directement dans votre boîte mail.</p>
            <input type="email" class="newsletter-input" placeholder="Votre adresse email">
            <button class="newsletter-btn pulse-hover">S'abonner</button>
        </div>

        <!-- 16. Footer sidebar -->
        <footer class="footer-links">
            <a href="#">Conditions</a>
            <a href="#">Confidentialité</a>
            <a href="#">Politique de cookies</a>
            <a href="#">Accessibilité</a>
            <a href="#">Infos publicitaires</a>
            <span>&copy; 2026 ZARTICLE.</span>
        </footer>
    </aside>

</div>

<script>
    feather.replace(); // Initialisation des icônes

    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeUI(newTheme);
    }

    function updateThemeUI(theme) {
        const themeText = document.getElementById('theme-text');
        const themeIcon = document.getElementById('theme-icon');
        
        if(theme === 'dark') {
            themeText.textContent = 'Mode Clair';
            themeIcon.setAttribute('data-feather', 'sun');
        } else {
            themeText.textContent = 'Mode Sombre';
            themeIcon.setAttribute('data-feather', 'moon');
        }
        feather.replace();
    }

    // Retain theme from local storage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeUI(savedTheme);
    } else {
        updateThemeUI('dark'); // dark default
    }
</script>
</body>
</html>
